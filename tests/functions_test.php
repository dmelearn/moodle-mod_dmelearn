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
 * @package       mod_dmelearn
 * @category      phpunit
 * @author        AJ Dunn, CJ Faulkner
 * @copyright     2015 Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for functions in content/include/functions.php file.
 * Run with command: vendor/bin/phpunit mod_dmelearn_functions_testcase
 *
 * @package    mod_dmelearn
 * @copyright  2015 Digital Media e-learning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_dmelearn_functions_testcase extends advanced_testcase {

    /**
     * Prepares things before this test case is initialised.
     * @return void
     */
    protected function setUp() {
        global $CFG;

        require_once $CFG->dirroot . '/mod/dmelearn/content/include/functions.php';
        require_once $CFG->dirroot . '/mod/dmelearn/content/elmo_web_service_hash.php';

        $this->resetAfterTest(); //  Reset state back after test.

        // Add mod_dmelearn plugin settings to DB.
        $dataset = $this->createCsvDataSet(
            array(
                'config_plugins' => __DIR__ . '/fixtures/config_plugins_dataset.csv',
            )
        );
        $this->loadDataSet($dataset);
    }

    /**
     * Test make_header() function.
     */
    public function test_make_header() {
        global $USER;

        // Set current User as default Admin User.
        $this->setAdminUser();

        // Get variables to make header.
        $public_key = get_config('mod_dmelearn', 'elmopublickey');
        $app_name = get_config('mod_dmelearn', 'elmoappname');
        $firstname = $USER->firstname;
        $lastname = $USER->lastname;
        $email = $USER->email;
        $payroll = '';
        $secret_key = get_config('mod_dmelearn', 'elmosecretkey');

        // Make the header array.
        $header = make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key);

        // Check the App Name in the header array is Correct.
        $this->assertEquals($app_name, $header["X-ELMO-APP-NAME"]);
    }

    /**
     * Test course_request() function. Test that the correct Course Name is given at
     *
     * @dataProvider provide_course_path_data
     */
    public function test_course_request($coursepath, $coursename) {
        global $CFG, $USER;

        $this->setAdminUser(); // Set current User as default Admin User.
        $ELMO_ENV = get_config('mod_dmelearn', 'elmourl'); // Required to make Constants.

        require_once $CFG->dirroot . '/mod/dmelearn/content/vendor/autoload.php'; // Composer Autoloader.
        require_once $CFG->dirroot . '/mod/dmelearn/content/include/constants.php'; // Constants.

        // Create Guzzle Client.
        $client = new GuzzleHttp\Client();

        // Get variables to make header.
        $public_key = get_config('mod_dmelearn', 'elmopublickey');
        $app_name = get_config('mod_dmelearn', 'elmoappname');
        $firstname = $USER->firstname;
        $lastname = $USER->lastname;
        $email = $USER->email;
        $payroll = '';
        $secret_key = get_config('mod_dmelearn', 'elmosecretkey');

        // Test course_request function.
        $request = course_request(
            $client,
            API_URL . API_COURSES . $coursepath,
            make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
        );

        // Extract JSON from request object.
        $course_request = $request->json();

        // Test the return course names against the expected ones.
        $this->assertEquals($coursename, $course_request['name']);
    }

    /**
     * Data Provider for test_course_request().
     * Provides data from csv containing course-path and course-name.
     *
     * @return array Array of course-path and course-names to test.
     */
    public function provide_course_path_data() {
        // Get array from csv with course-path and course-name.
        $filename = __DIR__ . '/fixtures/course_names_dataset.csv';
        $data = array_map('str_getcsv', file($filename));

        // Remove the first row in csv (The line with column names).
        array_shift($data);

        return $data;
    }

    /**
     * Test validate_question_request() function with correct answers for single answer questions only.
     *
     * @dataProvider provide_validate_question_data
     */
    public function test_validate_question_request($firstname, $lastname, $email, $payroll, $question, $answer) {
        global $CFG, $USER;

        $this->setAdminUser(); // Set current User as default Admin User.
        $ELMO_ENV = get_config('mod_dmelearn', 'elmourl'); // Required to make Constants.

        require_once $CFG->dirroot . '/mod/dmelearn/content/vendor/autoload.php'; // Composer Autoloader.
        require_once $CFG->dirroot . '/mod/dmelearn/content/include/constants.php'; // Constants.

        // Get other variables to make header.
        $public_key = get_config('mod_dmelearn', 'elmopublickey');
        $app_name = get_config('mod_dmelearn', 'elmoappname');
        $secret_key = get_config('mod_dmelearn', 'elmosecretkey');

        // Make header.
        $header = make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key);

        // Create Guzzle Client.
        $client = new GuzzleHttp\Client();

        // Test data representing POST data.
        $input = array(
            "data" => array(
                "question" => $question,
                "answer" => array($answer),
                "clicked" => array("True"),
                "timestamp" => time()
            )
        );

        // Test course_request function.
        $request = validate_question_request(
            $client,
            API_URL . API_VALIDATE . $question,
            $header,
            $input
        );

        // Make JSON.
        $response = $request->json();

        // Test to see if response back acknowledged the correct answer.
        $this->assertNotNull($response["Response"]["correct"]);
    }

    /**
     * Data Provider for test_validate_question_request().
     * Provides data from csv containing single answer questions with the correct answer and user details.
     *
     * @return array Array of data
     */
    public function provide_validate_question_data() {
        // Get array from csv with users and correct answers.
        $filename = __DIR__ . '/fixtures/users_correct_answers_dataset.csv';
        $data = array_map('str_getcsv', file($filename));

        // Remove the first row in csv with column names.
        array_shift($data);

        return $data;
    }
}
