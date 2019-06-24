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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

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
 * @since     1.5.0
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use mod_dmelearn\content\Content;
use mod_dmelearn\navigation\Navigation;

// Load Composer Packages
require_once __DIR__ . '/vendor/autoload.php';

// BC to put the lms setting back to ELMO content page.
require_once __DIR__ . '/lms_settings.php';

// Includes
require_once __DIR__ . '/include/constants.php';
require_once __DIR__ . '/include/functions.php';
require_once __DIR__ . '/elmo_web_service_hash.php';
require_once __DIR__ . '/content.php';
require_once __DIR__ . '/navigation.php';
require_once __DIR__ . '/include/cache.php';

/**
 * Using Query Strings to navigate between courses/modules/pages
 * The client application will make requests to the ELMO API endpoints.
 */
$module = (string)isset($_GET['module']) ? filter_var($_GET['module'], FILTER_SANITIZE_STRING) : null;
$page = (string)isset($_GET['page']) ? filter_var($_GET['page'], FILTER_SANITIZE_STRING) : null;
$about = (string)isset($_GET['about']) ? filter_var($_GET['about'], FILTER_SANITIZE_STRING) : null;

// Content Creator
$content = new Content($module, $page);

// If we want to load About Page instead of Course Page
if (!empty($about)) {
    $content->loadAboutPage([
        'course_id' => $elmo->course, // Course ID
        // URLs
        'www_root' => $CFG->wwwroot, // Moodle Home Page
        'content_url' => $lms_content_url, // Current Moodle Activity URL
        'lms_content_url' => $lms_content_url, // Current Moodle Activity URL
        'mod_id' => $elearnid, // Moodle Activity ID
        // Current User Info
        'full_name' => $lms_course->fullname,
        'first_name' => $USER->firstname,
        'last_name' => $USER->lastname,
    ]);
    exit();
}

// Course Data
$course_request = $content->getCourseData($course);

// Check if this plugin can support the course version.
$course_version = isset($course_request['configuration']['course_version']) ? $course_request['configuration']['course_version'] : 1;

if (!support_course_num($course_version)) {
    include_once __DIR__ . '/include/noAccess.php';
    exit();
}

$foundFirstPage = [
    'module' => isset($course_request['navigation']) && $course_request['navigation'] !== false ? key($course_request['navigation']) : null,
    'page' => isset($course_request['navigation'][$module]['pages']) && $course_request['navigation'] !== false ? key($course_request['navigation'][$module]['pages']) : null
];

if (isset($module) && !isset($page)) {
    // We have a module but need to get the FIRST page.
} elseif (!isset($module) && !isset($page) && isset($course_request)) {
    // We need to get module and page to make a page_request.
    if ($course_request['user']['last_visited']) {
        // We want to request the following page.
        $module = $course_request['user']['last_visited']['module'];
        $page = $course_request['user']['last_visited']['page'];
    } else {
        // We need to get the FIRST module and page.
        $module = $foundFirstPage['module'];
        $page = $foundFirstPage['page'];
    }
}

// Make a page request.
$page_request = $content->getPageData($course, $module, $page, $foundFirstPage);

// Setup the navigation using the supplied mod_dmelearn navigation class.
$navigation = new Navigation([
    'show_summary' => $course_request['configuration']['include_assessment_summary'],
    'navigation' => $course_request['navigation'],
    'site_url' => $_SERVER['PHP_SELF'],
    'course' => $course,
    'module' => $module,
    'page' => $page,
    'course_version' => $course_version
]);

// Handling previous and next button urls.
// Gets the nav button info from the page request.
$nav_buttons = $page_request['nav_btn'];
$previous_url = $navigation->getPrevURL($nav_buttons);
$next_url = $navigation->getNextURL($nav_buttons);

// BC: Check progress page.
check_progress_page($elearnid, $course_request['course_complete'], $page_request['data']['cert_data']['percentage']);

/**
 * DEFINE COURSE SPECIFIC CONSTANTS
 */
$websiteName = $course_request['website_name'] ?: 'dmelearn';
$frontEndConstants['base_img'] = ELMO_WEB_BASE_URL . 'assets/sites/' . $websiteName . '/img/';
$coursesFolder = isset($course_request['courses_dir']) ? $course_request['courses_dir'] : 'courses/';
$courseDirectory = isset($course_request['course_directory']) ? $course_request['course_directory'] : $course;
define('ELMO_WEB_COURSE_JAVASCRIPT', ELMO_WEB_BASE_URL . $coursesFolder . $courseDirectory . '/js/');
define('ELMO_WEB_COURSE_RESOURCES', ELMO_WEB_BASE_URL . $coursesFolder . $courseDirectory . '/resources/');
define('ELMO_WEB_COURSE_IMAGES', ELMO_WEB_COURSE_RESOURCES . 'images/');

$frontEndConstants['brand_name'] = $course_request['brand_name'] ?: 'Digital Media';
$courseConstants = [
    'course_js' => ELMO_WEB_COURSE_JAVASCRIPT,
    'course_resources' => ELMO_WEB_COURSE_RESOURCES,
    'course_img' => ELMO_WEB_COURSE_IMAGES
];

// Data being loaded onto page
$pageData = [
    'course_id' => $elmo->course, // Course ID
    // Page being displayed
    'template_version' => '02', // TODO: Remove HardCode
    'page_title' => (isset($course_request['name']) ? $course_request['name'] . ': ' : '') . "{$frontEndConstants['brand_name']} Course",
    'module' => $module, // Course Module
    'page' => $page, // Course Page

    // Prev and Next Module/Page
    'previous_url' => $previous_url,
    'next_url' => $next_url,

    // Data to display
    'course_data' => $course_request, // Course Data
    'page_data' => $page_request, // Page Data

    // URLs
    'www_root' => $CFG->wwwroot, // Moodle Home Page
    'content_url' => $lms_content_url, // Current Moodle Activity URL
    'lms_content_url' => $lms_content_url, // Current Moodle Activity URL
    'mod_id' => $elearnid, // Moodle Activity ID

    'constants' => $frontEndConstants, // Elmo URLs
    'course_constants' => $courseConstants, // Elmo Resource URLs

    // HTML
    'html' => [
        'lms_menu' => $lms_menu, // HTML Top Menu (to get back to moodle pages) -- built in moodle
        'navigation' => $navigation->make(), // HTML Nav Menu (to navigate to other pages)
    ],

    // Current User Info
    'full_name' => $lms_course->fullname,
    'first_name' => $USER->firstname,
    'last_name' => $USER->lastname,
];

// Check if API returned saying the course must be reset first.
$tf_has_expired = isset($course_request['tf_has_expired']) ? $course_request['tf_has_expired'] : false;
if ($tf_has_expired) {
    $pageData['timeframemonths'] = $timeframemonths;
}

// Setup the navigation using the supplied mod_dmelearn navigation class.
$content = new Content($module, $page);
$content->loadContentPage($pageData, $tf_has_expired);
