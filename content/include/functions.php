<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Basic Methods for requesting data in ELMO Web Services
 *
 * @uses Guzzle Make sure you have Guzzle loaded first
 * @see Also make sure that Elmo_web_service_hash class is loaded too
 * @author      Chris Barton, AJ Dunn
 * @copyright   2015 - 2022 WCHN Digital Learning & Design
 * @since         1.0.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

/**
 * Generate the relevant headers with a fresh timestamp
 *
 * @see   See Documentation for all the required and optional headers
 * @param string $public_key
 * @param string $app_name
 * @param string $first_name
 * @param string $last_name
 * @param string $email
 * @param string $payroll
 * @param string $secret_key
 * @return array $header    makes an up-to-date header
 */
function make_header($public_key, $app_name, $first_name, $last_name, $email, $payroll, $secret_key) {

    /* Trim to prevent issues sending data via header */
    $first_name = str_replace('|', '', trim($first_name));
    $last_name = str_replace('|', '', trim($last_name));
    $email = str_replace('|', '', trim($email));
    $payroll = str_replace('|', '', trim($payroll));

    return $header = array(
        // Note that the API will reject requests if the timestamp is older than 60 seconds,
        // So you will need to reset it with each request.
        'X-ELMO-TIMESTAMP' => time(),
        'X-ELMO-API-KEY' => $public_key,
        'X-ELMO-APP-NAME' => $app_name,
        'X-ELMO-USER' => $first_name . '|' . $last_name, // Use a pipe char to separate first name and last name.
        'X-ELMO-EMAIL' => $email,
        'X-ELMO-PAYROLL' => $payroll,
        // Make a token -- use the Elmo_web_service_hash lib to do this.
        'X-ELMO-TOKEN' => Elmo_web_service_hash::generate($first_name, $last_name, $email, $secret_key)
    );
}

/**
 * Course Request
 *
 * @param object $client Pass the Guzzle Client reference
 * @param string $path API URI path (api/courses/courseName)
 * @param array $headers Request Headers -- see documentation for all required headers
 * @return object Returns the Guzzle response object ready for manipulation
 * @throws moodle_exception
 */
function course_request(&$client, $path, $headers) {
    global $CFG;

    try {
        return $client->get(
            $path,
            [
                'headers' => $headers,
                'proxy' => make_guzzle_proxy_config()
            ]
        );
    } catch (GuzzleHttp\Exception\ServerException $e) {
        // 500 level errors.
        if (isset($CFG->debug) && !$CFG->debug == 0) {
            echo $e->getMessage();
        }
        // Throw Moodle Exception.
        throw new moodle_exception('course_request_internal_error', 'dmelearn');
    }
}

/**
 * Validate questions request
 *
 * @param object $client Pass the Guzzle Client reference
 * @param string $path API URI path (api/courses/courseName)
 * @param array $headers Request Headers -- see documentation for all required headers
 * @param null $post_data json data to post to page
 * @return object Returns the Guzzle response object ready for manipulation
 * @throws moodle_exception
 */
function validate_question_request(&$client, $path, $headers, $post_data = null) {
    try {
        return $client->post(
            $path,
            [
                'headers' => $headers,
                'json' => $post_data,
                'proxy' => make_guzzle_proxy_config()
            ]
        );
    } catch (GuzzleHttp\Exception\RequestException $e) {
        // Networking error, Throw a Moodle Exception.
        throw new moodle_exception('validatenotavailable', 'dmelearn');
    }
}

/**
 * Load Interactive Activity Storage request
 *
 * @param object $client Pass the Guzzle Client reference
 * @param string $path API URI path
 * @param array $headers Request Headers -- see documentation for all required headers
 * @return object Returns the Guzzle response object ready for manipulation
 * @throws moodle_exception
 */
function load_activity_storage_request(&$client, $path, $headers) {
    try {
        return $client->get(
            $path,
            [
                'headers' => $headers,
                'proxy' => make_guzzle_proxy_config()
            ]
        );
    } catch (GuzzleHttp\Exception\RequestException $e) {
        // Networking error, Throw a Moodle Exception.
        throw new moodle_exception('load_activity_storage_internal_error', 'dmelearn');
    }
}

/**
 * Set Interactive Activity Storage request
 *
 * @param object $client Pass the Guzzle Client reference
 * @param string $path API URI path
 * @param array $headers Request Headers -- see documentation for all required headers
 * @param null $post_data json data to post to page
 * @return object Returns the Guzzle response object ready for manipulation
 * @throws moodle_exception
 */
function set_activity_storage_request(&$client, $path, $headers, $post_data = null) {
    try {
        return $client->post(
            $path,
            [
                'headers' => $headers,
                'json' => $post_data,
                'proxy' => make_guzzle_proxy_config()
            ]
        );
    } catch (GuzzleHttp\Exception\RequestException $e) {
        // Networking error, Throw a Moodle Exception.
        throw new moodle_exception('set_activity_storage_internal_error', 'dmelearn');
    }
}

/**
 * Determines if a ELMO url actually exists based on file_exists and some PERL modules like
 * URI && LWP::Simple
 *
 * @param string $file URL to check
 * @return bool Does URL exist?
 */
function elmo_url_exists($file) {
    // Clamp it from spitting errors on invalid URLS.
    $file_headers = @get_headers($file);
    return $file_headers[0] != ('HTTP/1.1 404 Not Found' || 'HTTP/1.0 404 Not Found');
}

/**
 * Make a url based on the ELMO api conventions
 *
 * @param string $course Course
 * @param string $m Module
 * @param string $p Page
 */
function make_api_url($course, $m, $p) {
    // BC: changed to add id parameter.
    // Used in assessment summary page.
    echo $url = "?id={$_REQUEST["id"]}&course={$course}&module={$m}&page={$p}";
}

/**
 * Determines what scripts to load
 *
 * @param string $module
 * @param string $page
 * @param array $data
 */
function elmo_parse_config_page_scripts($module, $page, $data) {
    if (($module == $data['module'] ) && ( $page == $data['page'] )) {
        $src = (string) ELMO_WEB_COURSE_JAVASCRIPT . $data['script'];
        echo $script = "<script src='{$src}.js' type='text/javascript'></script>";
    }
}

/**
 * BC function to get over ajax COR
 *
 * @param $url
 * @return mixed
 */
function get_ajax_content($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    $path_explode = explode('/', $url);
    $result = preg_replace_callback('/src="([^"]+)"/i', function ($matches) {
        global $path_explode;
        if (strpos($matches[0], "http://") !== 0) {
            return str_replace('src="', 'src="http://' . $path_explode[2].'/', $matches[0]);
        } else {
            return ("21".$matches[0]);
        }
    }, $result);
    return $result;
}

/**
 * Check if this version of the dmelearn plugin can display a course of a given supported version number.
 *
 * @param int|string $version_num course version to check
 * @return bool is version supported?
 */
function support_course_num($version_num) {
    // Array containing the course version numbers supported by this dmelearn plugin version.
    $supported = array(1, 2, 2.1, 3, 4);

    return in_array($version_num, $supported);
}

/**
 * Use proxy settings from Site Administration if set.
 *
 * @return string|null configuration text for proxy setting for curl
 */
function make_guzzle_proxy_config() {
    global $CFG;
    $proxy = null;

    if (isset($CFG->proxyhost) && !empty($CFG->proxyhost) && !is_proxybypass(API_URL)) {
        $proxy = ''; // Add Protocol (Could also be http:// or sock5://). Empty string should work for both.
        if (!empty($CFG->proxyuser) && !empty($CFG->proxypassword)) {
            $proxy .= $CFG->proxyuser . ':' . $CFG->proxypassword . '@';
        }
        $proxy .= $CFG->proxyhost;
        if (!empty($CFG->proxyport)) {
            $proxy .= ':' . $CFG->proxyport;
        }
    }
    return $proxy;
}

/**
 * Updating the completion status of an Activity Module. Run when completed or reset.
 * 
 * @param int $courseID course id
 * @param int $courseModuleID course module id
 * @param int $userID user id
 * @param bool|null $courseCompleted known module completion status
 * @return bool
 */
function update_course_completion_status($courseID, $courseModuleID, $userID, $courseCompleted = null) {
    global $DB;

    // Get the Course.
    $course = $DB->get_record('course', array('id' => $courseID));

    if (!(isset($course)) || !(isset($courseModuleID))) {
        return false;
    }

    // Get the Course Module.
    $courseModule = get_coursemodule_from_id('dmelearn', $courseModuleID, $courseID);

    if (!(isset($courseModule))) {
        return false;
    }

    // Update completion state.
    $completion = new completion_info($course);

    if (is_object($completion)) {
        if ($completion->is_enabled($courseModule)) {
            if ($courseCompleted === true) {
                if (defined('COMPLETION_COMPLETE')) {
                    $ccomplete = COMPLETION_COMPLETE;
                } else {
                    $ccomplete = 1;
                }
                $completion->update_state(
                    $courseModule,
                    $ccomplete,
                    $userID
                );
            }
            if ($courseCompleted === false) {
                if (defined('COMPLETION_INCOMPLETE')) {
                    $cincomplete = COMPLETION_INCOMPLETE;
                } else {
                    $cincomplete = 0;
                }
                $completion->update_state(
                    $courseModule,
                    $cincomplete,
                    $userID
                );
            }
        }
    }
    return true;
}
