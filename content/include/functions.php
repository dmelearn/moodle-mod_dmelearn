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
 * Basic Methods for requesting data in ELMO Web Services
 * 
 * @uses Guzzle Make sure you have Guzzle loaded first
 * @see Also make sure that Elmo_web_service_hash class is loaded too
 * @author        Chris Barton
 * @copyright     2015 Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

/**
 * Generate the relevant headers with a fresh timestamp
 *
 * @see   See Documentation for all the required and optional headers
 * @param $public_key
 * @param $app_name
 * @param $firstname
 * @param $lastname
 * @param $email
 * @param $payroll
 * @param $secret_key
 * @return array $header    Makes an up-to-date header
 */
function make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key) {
    return $header = array(
        // Note that the API will reject requests if the timestamp is older than 60 seconds,
        // So you will need to reset it with each request.
        'X-ELMO-TIMESTAMP' => time(),
        'X-ELMO-API-KEY' => $public_key,
        'X-ELMO-APP-NAME' => $app_name,
        'X-ELMO-USER' => $firstname . '|' . $lastname, // Use a pipe char to separate first name and last name.
        'X-ELMO-EMAIL' => $email,
        'X-ELMO-PAYROLL' => $payroll,
        // Make a token -- use the Elmo_web_service_hash lib to do this.
        'X-ELMO-TOKEN' => Elmo_web_service_hash::generate($firstname, $lastname, $email, $secret_key)
    );
}

/**
 * Course Request
 *
 * @param object $client Pass the Guzzle Client reference
 * @param string $path API URI path (api/courses/courseName)
 * @param array $headers Request Headers -- see documentation for all required headers
 * @return object Returns the Guzzle response object ready for manipulation
 */

function course_request(&$client, $path, $headers) {

    try {
        return $client->get($path, $headers, array())->send();
    } catch (MultiTransferException $e) {
        echo "The following exceptions were encountered:\n";
        foreach ($e as $exception) {
            echo $exception->getMessage() . "\n";
        }

        echo "The following requests failed:\n";
        foreach ($e->getFailedRequests() as $request) {
            echo $request . "\n\n";
        }

        echo "The following requests succeeded:\n";
        foreach ($e->getSuccessfulRequests() as $request) {
            echo $request . "\n\n";
        }
    }
}

/**
 * Validate questions request
 *
 * @param $client
 * @param $path
 * @param $headers
 * @param null $post_data
 * @return mixed
 */
function validate_question_request(&$client, $path, $headers, $post_data = null) {
    try {
        return $client->post($path, $headers, $post_data)->send();
    } catch (MultiTransferException $e) {
        echo "The following exceptions were encountered:\n";
        foreach ($e as $exception) {
            echo $exception->getMessage() . "\n";
        }

        echo "The following requests failed:\n";
        foreach ($e->getFailedRequests() as $request) {
            echo $request . "\n\n";
        }

        echo "The following requests succeeded:\n";
        foreach ($e->getSuccessfulRequests() as $request) {
            echo $request . "\n\n";
        }
    }
}

/**
 * Determines if a ELMO url actually exists based on file_exists and some PERL modules like
 * URI && LWP::Simple
 */
function elmo_url_exists($file) {
    // Clamp it from spitting errors on invalid URLS.
    $file_headers = @get_headers($file);
    return ($file_headers[0] == ('HTTP/1.1 404 Not Found' || 'HTTP/1.0 404 Not Found') ) ? false : true;
}

/**
 * Make a url based on the ELMO api conventions
 */
function make_api_url($course, $m, $p) {
    // BC: changed to add id parameter.
    // Used in assessment summary page.
    echo $url = "?id={$_REQUEST["id"]}&course={$course}&module={$m}&page={$p}";
}

/**
 * Determines what scripts to load
 *
 * @param $module
 * @param $page
 * @param $data
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
function get_ajax_content($url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    $path_explode = explode('/', $url);
    $result = preg_replace_callback('/src="([^"]+)"/i', function ($matches) {
        global $path_explode;
        if(strpos($matches[0],"http://")!==0){
            return str_replace('src="','src="http://'.$path_explode[2].'/',$matches[0]);
        } else{
            return ("21".$matches[0]);
        }
    }, $result);
	echo $content;
	return $result;
}