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

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");

// TODO: Add more comments.
if (!$USER->id && $USER->id < 2) {
    die();
}

$firstname = $USER->firstname;
$lastname = $USER->lastname;
$email = $USER->email;
$payroll = $USER->idnumber;

// This is a registration made to a Client, be sure to register each client.

// Send the public key with each request.
$public_key = get_config('mod_dmelearn', 'elmopublickey');
// Use the secret key to generate tokens.
$secret_key = get_config('mod_dmelearn', 'elmosecretkey');
// Name of the registered application.
$app_name = get_config('mod_dmelearn', 'elmoappname');
$ELMO_ENV = get_config('mod_dmelearn', 'elmourl');

require_once('elmo_web_service_hash.php');
require_once('./vendor/autoload.php');
require_once('./include/constants.php');
require_once('./include/functions.php');

use GuzzleHttp\Client;

$client = new Client();

// Get all the data we need to make a request.
$path = filter_var($_GET['request'], FILTER_SANITIZE_STRING);
$data = array('data' => filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING));

// Workout what the request actually is for.
// Regex sucks in PHP, strip the first slash if it exists.
if (substr($path, 0, 1) == '/') {
    $path = substr ($path, 1, strlen ($path));
}
$path_explode = explode('/', $path);
$request_path = $path_explode[0];

$response;
// What type of request?
switch ($request_path) {
    // TODO: Add more matches here when needed.
    case 'validate_question':
        if (count($path_explode) > 1) {
            $id = $path_explode[1];
        }
        try{
            $response = validate_question_request(
                $client,
                (API_URL . API_VALIDATE . $id),
                make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key),
                $data
            );
        } catch (GuzzleHttp\Exception\TransferException $e) {
            exit();
        }
        // Feels bad man but you have json while you json.
        $response = $response->json();
        exit(json_encode($response['Response']));
    default:
        $domain_info = parse_url($ELMO_ENV);
        $domain = $domain_info["host"];

        if ($path_explode[2] == $domain) {
            echo get_ajax_content($path);
        }
    break;
}