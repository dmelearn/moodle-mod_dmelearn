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
 * Log definitions
 * @package       mod_dmelearn
 * @author        Chris Barton
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once 'elmo_web_service_hash.php';
// BC to put the lms seeting back to ELMO content page.
include_once './lmssettings.php';
// Include guzzle and the libs we need.
include_once './vendor/autoload.php';
include_once 'navigation.php';
include_once './include/constants.php';
include_once './include/functions.php';
// A caching class.
include_once './include/cache.php';

use Guzzle\Http\Client;
use Guzzle\Http\Exception\MultiTransferException;
use mod_dmelearn\navigation\Navigation;
use mod_dmelearn\cache\Cache;

// Setup Guzzle to the web services end point.
$client = new Client(API_URL);
$return = new StdClass();

try {
    // print_r(API_URL . '/' . API_KEY_COURSES . $public_key);
    $request = course_request(
        $client,
        (API_URL . '/' . API_KEY_COURSES . $public_key),
        make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
    );
    $page_request = $request->json();
    $return->result = $page_request;
    echo json_encode($return);

} catch (Guzzle\Common\Exception\RuntimeException $e) {
    $return->result = false;
    $return->message = $e->getMessage();
    echo json_encode($return);
}