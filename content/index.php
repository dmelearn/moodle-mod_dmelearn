<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Client Application
 *
 * This is a client to give an example to implement and consume ELMO Web Services.
 * It uses Guzzle, Composer and a few ELMO external classes.
 * The Elmo classes have no dependencies.
 *
 * Note: Guzzle 5.3 requires PHP 5.4.0+
 *
 * @package   mod_dmelearn
 * @author    Chris Barton, AJ Dunn, CJ Faulkner
 * @copyright 2015 Chris Barton, Digital Media e-learning
 * @version   1.5.0
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// Include guzzle and the libs we need.
require_once('elmo_web_service_hash.php');

// BC to put the lms setting back to ELMO content page.
require_once('./lmssettings.php');

require_once('./vendor/autoload.php');
require_once('navigation.php');

require_once('./include/constants.php');
require_once('./include/functions.php');

// A caching class.
require_once('./include/cache.php');

use GuzzleHttp\Client;
use mod_dmelearn\navigation\Navigation;
use mod_dmelearn\cache\Cache;

// Setup Guzzle to the web services end point.
$client = new Client();

/**
 * Using Query Strings we navigate between courses/modules/pages
 * and the client application will make requests to these API endpoints
 * for us.
 *
 * @note : Remember to secure this
 */
$module = (string)(isset($_GET['module'])) ? filter_var($_GET['module'], FILTER_SANITIZE_STRING) : null;
$page = (string)(isset($_GET['page'])) ? filter_var($_GET['page'], FILTER_SANITIZE_STRING) : null;

// Make Requests to course first to get all user information/scripts/etc.
// We have course = courseName, make a request for information on the course.
try {
    // Check if this Moodle Activity's DM course has to be reset after a certain amount of months.
    $limitbymonths = '/';

    if ($preventearlierthanyear > 2000) {
        // A value of 0 or null means this is not used.
        // Include the minimum year completion date in the API URL.
        $limitbymonths .= '0/' . $preventearlierthanyear;
    } else if ($timeframemonths >= 1) {
        // Include the amount of months in the API URL.
        $limitbymonths .= $timeframemonths;
    }

    $request = course_request(
        $client,
        (API_URL . API_COURSES . $course . $limitbymonths),
        make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
    );
    $course_request = $request->json();

} catch (GuzzleHttp\Exception\ClientException $e) {
    // Check if we are unauthorised 'HTTP Error 401'.
    if ($e->getResponse()->getStatusCode() == '401') {
        // This Moodle is not authorised to access this course.
        $unauthorised = true;
        include_once('include/noAccess.php');
        die();
    } else {
        // We are authorised but another issue has occurred.
        // Dump Guzzle exception message if debug is enabled in Moodle.
        if (isset($CFG->debug) && !$CFG->debug == 0) {
            echo $e->getMessage();
        }
        // Throw Moodle Exception.
        throw new moodle_exception('course_client_exception', 'dmelearn');
    }
} catch (GuzzleHttp\Exception\RequestException $e) {
    if (isset($CFG->debug) && !$CFG->debug == 0) {
        echo $e->getMessage();
    }
    // Throw Moodle Exception.
    throw new moodle_exception('course_request_exception', 'dmelearn');
}

// Check if this plugin can support the course version.
$course_version = isset($course_request["configuration"]["course_version"]) ? $course_request["configuration"]["course_version"] : 1;

if (!support_course_num($course_version)) {
    include_once('include/noAccess.php');
    die();
}

if (isset($module) && !isset($page)) {
    // We have a module but need to get the FIRST page.
} else if (!isset($module) && !isset($page)) {
    // We need to get module and page to make a page_request.
    if ($course_request['user']['last_visited']) {
        // We want to request the following page.
        $module = $course_request['user']['last_visited']['module'];
        $page = $course_request['user']['last_visited']['page'];
    } else {
        // We need to get the FIRST module and page.
        $module = key($course_request['navigation']);
        $page = key($course_request['navigation'][$module]['pages']);
    }
}

// Make a page request.
try {
    // If the page is already cached.
    $cached = Cache::retrieve("{$course}_{$module}_{$page}");

    if (!$cached) {
        // Make a new request.
        $request = course_request(
            $client,
            (API_URL . API_COURSES . $course . '/' . API_MODULES . $module . '/' . API_PAGES . $page),
            make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
        );
        $page_request = $request->json();
        // Attempt to cache the page.
        if (isset($page_request['content'])) {
            Cache::caching("{$course}_{$module}_{$page}", $page_request['content']);
        }
    } else {
        $page_request['content'] = $cached;
    }
} catch (GuzzleHttp\Exception\ClientException $e) {
    if ($e->getResponse()->getStatusCode() == '404') {
        // 404 - Page not found on the API providers site.
        // We need to get the FIRST module and page.
        $module = key($course_request['navigation']);
        $page = key($course_request['navigation'][$module]['pages']);

        if (isset($page) && $page != "" && isset($module) && $module != "") {
            // Take user to a working page.
            echo '<script>window.location.href="' . $lmscontenturl . '&module=' . $module . '&page=' . $page . '";</script>';
        } else {
            throw new moodle_exception('pagenotfound', 'dmelearn');
        }
        die();
    } else if ($e->getResponse()->getStatusCode() == '400') {
        // This will happen when no page is specified in the request URL.
        // lmssettings.php will redirect the users to the last saved page.
        if (isset($CFG->debug) && !$CFG->debug == 0) {
            echo 'Note: Moodle debugging is enabled.<br>';
        }
        echo 'Redirecting ...';
    } else {
        // Dump Guzzle exception message if debug is enabled in moodle.
        if (isset($CFG->debug) && !$CFG->debug == 0) {
            echo $e->getMessage();
        }
        throw new moodle_exception('page_client_exception', 'dmelearn');
    }
}

// Setup the navigation using the supplied mod_dmelearn navigation class.
$navigation = new Navigation(
    array(
        'show_summary' => $course_request['configuration']['include_assessment_summary'],
        'navigation' => $course_request['navigation'],
        'site_url' => $_SERVER['PHP_SELF'],
        'course' => $course,
        'module' => $module,
        'page' => $page,
        'course_version' => $course_version
    )
);

// Handling previous and next button urls.
// Gets the nav button info from the page request.
$nav_buttons = $page_request['nav_btn'];

// Splits the minus string into module & page and sets the previous url array.
if (isset($nav_buttons['minus'])) {
    $prev = explode('/', $nav_buttons['minus']);
    $previous_url = array('module' => $prev[0], 'page' => $prev[1]);
} else {
    $previous_url = false;
}

// Splits the minus string into module & page and sets the next url array.
if (isset($nav_buttons['plus'])) {
    $next = explode('/', $nav_buttons['plus']);
    $next_url = array('module' => $next[0], 'page' => $next[1]);
} else {
    $next_url = false;
}

// BC: Check progress page.
check_progress_page($elearnid, $course_request["course_complete"], $page_request['data']['cert_data']['percentage']);

/**
 * DEFINE COURSE SPECIFIC CONSTANTS
 */
define('ELMO_WEB_COURSE_JAVASCRIPT', ELMO_WEB_BASE_COURSES . $course . '/js/');
define('ELMO_WEB_COURSE_RESOURCES', ELMO_WEB_BASE_COURSES . $course . '/resources/');
define('ELMO_WEB_COURSE_IMAGES', ELMO_WEB_COURSE_RESOURCES . 'images/');

$courseConstants = array(
    'course_js' => ELMO_WEB_COURSE_JAVASCRIPT,
    'course_resources' => ELMO_WEB_COURSE_RESOURCES,
    'course_img' => ELMO_WEB_COURSE_IMAGES
);

// Create new Plates Instance Loaded From Composer.
$plates = new League\Plates\Engine(__DIR__ . '/plates');

$page = array(
    'course_data' => $course_request,
    'page_data' => $page_request,
    'constants' => $frontEndConstants,
    'content_url' => $lmscontenturl,
    'page' => $page,
    'module' => $module,
    'course_constants' => $courseConstants,
    'lmsmenu' => $lmsmenu,
    'wwwroot' => $CFG->wwwroot,
    'fullname' => $lmscourse->fullname,
    'first_name' => $USER->firstname,
    'last_name' => $USER->lastname,
    'course_id' => $elmo->course,
    'mod_id' => $elearnid,
    'previous_url' => $previous_url,
    'next_url' => $next_url,
    'navigation' => $navigation->make(),
    'lmscontenturl' => $lmscontenturl
);

// Check if API returned saying the course must be reset first.
$tf_has_expired = isset($course_request["tf_has_expired"]) ? $course_request["tf_has_expired"] : false;

if ($tf_has_expired) {
    // Course that requires reset before it can be viewed.
    $page['timeframemonths'] = $timeframemonths;
    // Output Template.
    $plates->addData($page);
    echo $plates->render('base_reset');
    die();
} else {
    // Course that can be viewed.
    $plates->addData($page);
    echo $plates->render('base');
    die();
}
