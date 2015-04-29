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
 * @package       mod_dmelearn
 * @author        Chris Barton, CJ Faulkner
 * @copyright     2015 Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once(dirname(dirname(dirname(dirname(__FILE__))))."/config.php");

// TODO: Add Comments
if(!$USER->id && $USER->id<2){
    die();
}

$firstname  = $USER->firstname;
$lastname   = $USER->lastname;
$email      = $USER->email;
$payroll    = $USER->idnumber;

// This is a registration made to Client, be sure to register each client.

// Send the public key with each request.
$public_key = get_config('mod_dmelearn', 'elmopublickey');
// Use the secret key to generate tokens.
$secret_key = get_config('mod_dmelearn', 'elmosecretkey');
// Name of the registered application.
$app_name = get_config('mod_dmelearn', 'elmoappname');
$ELMO_ENV = get_config('mod_dmelearn', 'elmourl');

include_once 'elmo_web_service_hash.php';
include_once './vendor/autoload.php';
include_once './include/constants.php';
include_once './include/functions.php';

use Guzzle\Http\Client;
use Guzzle\Http\Exception\MultiTransferException;

$client = new Client(API_URL);

// Get all the data we need to make a request.
$data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

// Get the assessment id from the AJAX call.
$course_path = $data['course_path'];
$user_id = $data['user_id'];

try {
    $request = course_request(
        $client,
        (API_URL . '/' . API_RESET . $course_path . '/' . $user_id),
        make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
    );
    $page_request = $request->json();
    exit($page_request);
    die();
    
} catch (Guzzle\Common\Exception\RuntimeException $e) {
    // Dump Guzzle exception message if debug is enabled in Moodle.
    if( isset($CFG->debug) && !$CFG->debug == 0){
        echo "The following exceptions were encountered:\n";
        echo $e->getMessage();
        echo $request->getMessage();
    }

}