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
//
// This plug-in is based on mod_journal by David Monllaó (https://moodle.org/plugins/view/mod_journal).

/**
 * @package       mod_dmelearn
 * @author        Kien Vu, AJ Dunn
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/config.php");
// Require gradelib to update grades correctly with grade_update function.
require_once($CFG->libdir.'/gradelib.php');

// Check if Request id is valid.
if (!isset($_REQUEST["id"]) || !$_REQUEST["id"]) {
    print_error('invalidaccessparameter', 'dmelearn');
}

// This is a registration made to Client, be sure to register each client.
// Send the public key with each request.
$public_key = get_config('mod_dmelearn', 'elmopublickey');
// Use the secret key to generate tokens.
$secret_key = get_config('mod_dmelearn', 'elmosecretkey');
// Name of the registered application.
$app_name = get_config('mod_dmelearn', 'elmoappname');
// Elmo url.
$ELMO_ENV = get_config('mod_dmelearn', 'elmourl');

if (!isset($ELMO_ENV, $public_key, $secret_key, $app_name)) {
    print_error('Invalid mod_dmelearn plugin settings', 'dmelearn');
}

// Set $elearnid to the id 'integer' from the REQUEST.
$elearnid = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

require_login();

$elmo = $DB->get_record('dmelearn', array('id' => $elearnid));

if (!$elmo){
    print_error('Course does not exist', 'dmelearn');
}

require_course_login($elmo->course);
$course = $elmo->coursepath;

// SOME USER DATA needed to make a request.
$firstname  = $USER->firstname;
$lastname   = $USER->lastname;
$email      = $USER->email;
$payroll    = $USER->idnumber;

// Make a token - Use the Elmo_web_service_hash lib to do this.
$token = Elmo_web_service_hash::generate($firstname, $lastname, $email, $secret_key);
$lmscontenturl = "{$CFG->wwwroot}/mod/dmelearn/content/?id={$elearnid}";
// Generate Moodle menu.
$lmscourse = $DB->get_record('course', array('id' => $elmo->course));

$lmsmenu = '
<div class="lmsmenu">
  <div class="lmsmenuleft">
    <i class="icon-home"></i> <a href="' . $CFG->wwwroot . '"> Home</a> <i class="icon-double-angle-right"></i> <i class="icon-tasks"></i><a href="' . $CFG->wwwroot.'/course/view.php?id=' . $elmo->course . '"> ' . $lmscourse->fullname . '</a>
  </div>
  <div class="lmsmenuright">
    You are logged in as ' . $USER->firstname . ' ' . $USER->lastname . ' (<a href="' . $CFG->wwwroot . '/login/logout.php">Log out</a>)
  </div>
</div>
<style>
  .lmsmenu{
    background: #F5F5F5;
    padding: 8px 0px;
    padding-top: 8px;
    box-shadow: 0px 3px 5px #8A8A8A;
    /*border-bottom: 1px solid #B9B9B9;*/
    float: left;
    width: 100%;
  }
  .lmsmenuleft{
    float: left;
    padding-left: 20px;
  }
  .lmsmenuright{
    float: right;
    padding-right: 20px;
  }
  .lmsmenu img{
    vertical-align: inherit;
  }
</style>
';

/**
 * Checks the progress of the page given from the client, if different than the
 * Moodle db progress then it will update the Moodle db with the correct progress.
 *
 * @param $eid
 * @param $course_info
 */
function check_progress_page($eid, $course_info) {
    global $CFG, $DB, $USER, $COURSE, $lmscontenturl;

    // Get the last elmo user data.
    $edata = $DB->get_record_sql("SELECT *
                                  FROM {dmelearn_entries}
                                  WHERE dmelearn = ?
                                  AND userid = ?
                                  ORDER BY id DESC
                                  LIMIT 1
                                  OFFSET 0", array($eid, $USER->id));
    if (!$edata) {
        $edata = new StdClass();
        $trackdata = new StdClass();
        $trackdata->page = "";
        $trackdata->module = "";
        $edata->dmelearn = $eid;
        $edata->userid = $USER->id;
        $edata->grade = 0;
        $edata->trackdata = json_encode($trackdata);
        $edata->modified = time();
        $edata->id = $DB->insert_record('dmelearn_entries', $edata);
    } else {
        $trackdata = json_decode($edata->trackdata);
        if (!$trackdata) {
            $trackdata = new StdClass();
            $trackdata->page = "";
            $trackdata->module = "";
        }
        // The request module and page are both not set and the moodle $trackdata page and module both exist.
        if (!isset($_REQUEST["module"]) && !isset($_REQUEST["page"]) && isset($trackdata->page) && $trackdata->page != "" && isset($trackdata->module)
            && $trackdata->module != "") {
            // This was intially a confirm prompt which redirected on comfirmation but did nothing when canceled.
            echo '<script>window.location.href="' . $lmscontenturl . '&module=' . $trackdata->module . '&page=' . $trackdata->page . '";</script>';
            die();
        } else if (isset($_REQUEST["module"]) && !isset($_REQUEST["page"]) && isset($trackdata->page) && $trackdata->page != "" && isset($trackdata->module)
            && $trackdata->module != "") {
            // The request module exists but page does not exist and the moodle $trackdata page and module both exist.
            echo '<script>window.location.href="' . $lmscontenturl . '&module=' . $trackdata->module . '&page=' . $trackdata->page . '";</script>';
            die();
        } else if (isset($_REQUEST["module"]) && $_REQUEST["module"] != "" && isset($_REQUEST["page"]) && $_REQUEST["page"] != "") {
            $trackdata->page = filter_var($_REQUEST['page'], FILTER_SANITIZE_STRING);
            $trackdata->module = filter_var($_REQUEST['module'], FILTER_SANITIZE_STRING);
            $edata->trackdata = json_encode($trackdata);
            $edata->modified = time();

            // If the grade in Moodle db is not complete BUT the info from ELMO API is complete then it updates the Moodle db.
            if ($edata->grade == 0 && isset($course_info["course_complete"]) && $course_info["course_complete"] == 1) {
                $edata->grade = 100;
                $DB->update_record('dmelearn_entries', $edata);
            } // If the moodle database says complete but the info from elmo is not complete (e.g. course reset) the it will update the database with that info.
            else if ($edata->grade == 100 && !isset($course_info["course_complete"])) {
                $edata->grade = 0;
                $DB->update_record('dmelearn_entries', $edata);
            } // If the moodle db and ELMO API are both the same grades then we still want to update the database with the latest page that the user is on.
            else {
                $DB->update_record('dmelearn_entries', $edata);
            }
            // Upgrade the grade item.
            if (isset($course_info["course_complete"])) {
                $params = array($eid, $COURSE->id);
                $sql = "SELECT *
                        FROM {grade_items}
                        WHERE itemtype = 'mod'
                        AND itemmodule = 'dmelearn'
                        AND iteminstance = ?
                        AND courseid = ?
                        LIMIT 1
                        OFFSET 0";
                $grade_item = $DB->get_record_sql($sql, $params);
                // Check if record exists in grade_items.
                if ($grade_item) {
                    // Record in grade_item exists.
                    $params = array($grade_item->id, $USER->id);
                    $sql = "SELECT *
                            FROM {grade_grades}
                            WHERE itemid = ?
                            AND userid = ?
                            LIMIT 1
                            OFFSET 0";
                    $grade_grades = $DB->get_record_sql($sql, $params);
                    // Check if record exists in $grade_grades.
                    if (!$grade_grades) {
                        // Record in grade_grades does not exist.
                        $grade_grades = new StdClass();
                        $grade_grades->itemid = $grade_item->id;
                        $grade_grades->userid = $USER->id;

                        $grade_grades->rawgrademax = $grade_item->grademax;
                        $grade_grades->rawgrademin = $grade_item->grademin;
                        $grade_grades->rawscaleid = $grade_item->scaleid;
                        $grade_grades->usermodified = $USER->id;

                        $grade_grades->timecreated = time();
                        $grade_grades->timemodified = time();
                        if ($course_info["course_complete"] == 1) {
                            // Set grade to 100% complete.
                            $grade_grades->rawgrade = $grade_item->grademax;
                            $grade_grades->finalgrade = $grade_item->grademax;
                        } else {
                            // Set grade to 0% complete.
                            $grade_grades->rawgrade = $grade_item->grademin;
                            $grade_grades->finalgrade = $grade_item->grademin;
                        }
                        $grade_grades->id = $DB->insert_record("grade_grades", $grade_grades);
                    } else {
                        // Record in grade_grades exists.
                        $grade_grades->timemodified = time();
                        if ($course_info["course_complete"] == 1 && $grade_grades->rawgrade != $grade_item->grademax) {
                            // Set grade to 100% complete.
                            $grade_grades->rawgrade = $grade_item->grademax;
                            grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $eid, 0, $grade_grades);
                        } else if ($course_info["course_complete"] != 1 && $grade_grades->rawgrade != $grade_item->grademin) {
                            // Set grade to 0% complete.
                            $grade_grades->rawgrade = $grade_item->grademin;
                            $grade_grades->overridden = time();
                            grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $eid, 0, $grade_grades);
                        }
                    }
                }
            }
        }
    }
}