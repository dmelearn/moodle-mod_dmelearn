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
 * @package    mod_dmelearn
 * @author     Chris Barton, AJ Dunn
 * @copyright  2015 Chris Barton, Digital Media e-learning
 * @since      1.0.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once('elmo_web_service_hash.php');

// BrightCookie to put the lms setting back to ELMO content page.
$ELMO_ENV = get_config('mod_dmelearn', 'elmourl');

// Include guzzle and the libs we need.
require_once('vendor/autoload.php');
require_once('navigation.php');
require_once('include/constants.php');
require_once('include/functions.php');
// A caching class.
require_once('include/cache.php');
// Header data - example its hardwired so change it.

use GuzzleHttp\Client;
use mod_dmelearn\navigation\Navigation;
use mod_dmelearn\cache\Cache;

/**
 * @return StdClass
 */
function get_key_courses() {
    global $CFG, $USER;

    // Some User data needed to make a request.
    $firstname  = $USER->firstname;
    $lastname   = $USER->lastname;
    $email      = $USER->email;
    $payroll    = $USER->idnumber;

    $public_key = get_config('mod_dmelearn', 'elmopublickey');
    $secret_key = get_config('mod_dmelearn', 'elmosecretkey');
    $app_name = get_config('mod_dmelearn', 'elmoappname');

    // Setup Guzzle to the web services end point.
    $client = new Client();
    $return = new StdClass();
    try {
         $request = course_request(
             $client,
             (API_URL . API_KEY_COURSES . $public_key),
             make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
         );
         $page_request = $request->json();
         $return->result = $page_request;

    } catch (GuzzleHttp\Exception\RequestException $e) {
        $return->result = false;
        $return->message = $e->getMessage();
    }

    return $return;
}
