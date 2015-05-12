<?php
// This file is part of moodle-mod_dmelearn for Moodle - http://moodle.org/
//
// moodle-mod_dmelearn is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// moodle-mod_dmelearn is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with moodle-mod_dmelearn. If not, see <http://www.gnu.org/licenses/>.

/**
 * Client Application
 *
 * This is a client to give an example to implement and consume ELMO Web Services.
 * It uses Guzzle, Composer and a few ELMO external classes.
 * The Elmo classes have no dependencies.
 *
 * Note: Guzzle 3 requires PHP 5.3.3+
 *
 * @package    mod_dmelearn
 * @author     Chris Barton, AJ Dunn, CJ Faulkner
 * @copyright  2015 Chris Barton, Digital Media e-learning
 * @version    1.0.1
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

// Include guzzle and the libs we need.
require_once('elmo_web_service_hash.php');

// BC to put the lms seeting back to ELMO content page.
require_once('./lmssettings.php');

require_once('./vendor/autoload.php');
require_once('navigation.php');

require_once('./include/constants.php');
require_once('./include/functions.php');

// A caching class.
require_once('./include/cache.php');

use Guzzle\Http\Client;
use Guzzle\Http\Exception\MultiTransferException;
use mod_dmelearn\navigation\Navigation;
use mod_dmelearn\cache\Cache;

// Setup Guzzle to the web services end point.
$client = new Client(API_URL);

/**
 * Using Query Strings we navigate between courses/modules/pages
 * and the client application will make requests to these API endpoints
 * for us.
 *
 * @note : Remember to secure this
 */

$module = (string) (isset($_GET['module'])) ? filter_var($_GET['module'], FILTER_SANITIZE_STRING) : null;
$page   = (string) (isset($_GET['page'])) ? filter_var($_GET['page'], FILTER_SANITIZE_STRING) : null;

// MAKE REQUESTs to course first to get all user information/scripts/etc.
// We have course = courseName, make a request for information on the course.
try {
    $request = course_request(
        $client,
        API_URL . '/' . API_COURSES . $course,
        make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
    );
    $course_request = $request->json();
} catch (Guzzle\Common\Exception\RuntimeException $e) {
    // Check if we are Unauthorized '401'.
    if ($e->getResponse()->getStatusCode() == '401') {
        include_once('include/noAccess.php');
    } else {
        // We are Authorized but another issue has occured.
        // Dump Guzzle exception message if debug is enabled in Moodle.
        if (isset($CFG->debug) && !$CFG->debug == 0) {
            echo "The following exceptions were encountered:\n";
            echo $e->getMessage();
            echo $request->getMessage();
        }
    }
}

if (isset($module) && !isset($page)) {
    // We have a module but need to get the FIRST page.
} else if (!isset($module) && !isset($page)) {
    // We need to get module and page to make a page_request.
    if ($course_request['user']['last_visited']) {
        // We want to request the following page.
        $module = $course_request['user']['last_visited']['module'];
        $page   = $course_request['user']['last_visited']['page'];
    } else {
        // We need to get the FIRST module and page.
        $module = key($course_request['navigation']);
        $page   = key($course_request['navigation'][$module]['pages']);
    }
}

// Make a page request.
try {
    // If the page is already cached.
    $cached = Cache::retrieve("{$course}_{$module}_{$page}");

    if (!$cached)
    {
        // Make a new request.
        $request = course_request(
            $client,
            (API_URL . '/' . API_COURSES . $course . '/' . API_MODULES . $module . '/' . API_PAGES . $page),
            make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
        );
        $page_request = $request->json();
        // Attempt to cache the page.
        if (isset($page_request['content'])){
            Cache::caching("{$course}_{$module}_{$page}", $page_request['content']);
        }
    }
    else
    {
        $page_request['content'] = $cached;
    }
} catch (Guzzle\Common\Exception\RuntimeException $e) {
    if ($e->getResponse()->getStatusCode() == '404') {
        // 404 - Page not found on the API providers site.
        
        // We need to get the FIRST module and page.
        $module = key($course_request['navigation']);
        $page   = key($course_request['navigation'][$module]['pages']);
        
        if (isset($page) && $page!="" && isset($module) && $module!="") {
            echo '<script>window.location.href="'.$lmscontenturl.'&module='.$module.'&page='.$page.'";</script>';
        } else {
            echo('Oops something went wrong. Page not found.');
        }
        
        die();
    } else if ($e->getResponse()->getStatusCode() == '400') {
        // This will happen when no page is specified in the request URL.
        // lmssettings.php will redirect the users to the last saved page.
        if (isset($CFG->debug) && !$CFG->debug == 0){
             echo('Note: Moodle debugging is enabled.<br>');
        }
        echo('Redirecting ...');
    } else {
        // Dump Guzzle exception message if debug is enabled in moodle.
        if (isset($CFG->debug) && !$CFG->debug == 0){
            echo "The following exceptions were encountered:\n";
            echo $e->getMessage();
            echo $request->getMessage();
        }
    }
}
 
// Setup the navigation using the supplied mod_dmelearn navigation class.
$navigation = new Navigation(
    array(
        'show_summary'      => $course_request['configuration']['include_assessment_summary'],
        'navigation'        => $course_request['navigation'],
        'site_url'          => $_SERVER['PHP_SELF'],
        'course'            => $course,
        'module'            => $module,
        'page'              => $page
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
check_progress_page($elearnid, $course_request);

/**
 * DEFINE COURSE SPECIFIC CONSTANTS
 */
define('ELMO_WEB_COURSE_JAVASCRIPT', ELMO_WEB_BASE_COURSES . $course . '/js/');
define('ELMO_WEB_COURSE_RESOURCES', ELMO_WEB_BASE_COURSES . $course . '/resources/');
define('ELMO_WEB_COURSE_IMAGES', ELMO_WEB_COURSE_RESOURCES . 'images/');

// Below is a working template, it must be kept up-to-date.
require('template/base.php');