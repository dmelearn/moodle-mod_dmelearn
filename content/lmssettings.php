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
 * @package     mod_dmelearn
 * @author      Kien Vu, AJ Dunn
 * @copyright   2015 - 2022 WCHN Digital Learning & Design, 2015 BrightCookie (http://www.brightcookie.com.au)
 * @since       1.1.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

include_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
// Require gradelib to update grades correctly with grade_update function.
require_once($CFG->libdir . '/gradelib.php');

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

if (!$elmo) {
    print_error('Course does not exist', 'dmelearn');
}

require_course_login($elmo->course);
$course = $elmo->coursepath;
// Number of months to accept already completed ELMO courses.
$timeframemonths = $elmo->timeframemonths;

// Earliest Year to accept previously completed course data from ELMO.
$preventearlierthanyear = $elmo->preventearlierthanyear;

// SOME USER DATA needed to make a request.
$firstname  = $USER->firstname;
$lastname   = $USER->lastname;
$email      = $USER->email;
$payroll    = $USER->idnumber;

$lmscontenturl = "{$CFG->wwwroot}/mod/dmelearn/content/?id={$elearnid}";
// Generate Moodle menu.
$lmscourse = $DB->get_record('course', array('id' => $elmo->course));

$lmsmenu = '
<div class="lmsmenu">
  <div class="lmsmenuleft">
    <i class="icon-home"></i> <a href="' . $CFG->wwwroot . '"> Home</a> <i class="icon-double-angle-right"></i> <i class="icon-tasks"></i><a href="' . $CFG->wwwroot . '/course/view.php?id=' . $elmo->course . '"> ' . $lmscourse->fullname . '</a>
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
 * @param $elearnid the id number in the url
 * @param boolean $course_complete is the course completed
 * @param $percentage - percentage of course progress
 */
function check_progress_page($elearnid, $course_complete, $percentage = 0) {
    global $DB, $USER, $COURSE, $lmscontenturl;

    // Get the latest elmo user data from dmelearn_entries.
    $edata = $DB->get_record_sql(
        "SELECT *
          FROM {dmelearn_entries}
          WHERE dmelearn = ?
          AND userid = ?
          ORDER BY id DESC
          LIMIT 1
          OFFSET 0",
        array($elearnid, $USER->id)
    );

    // The first time an activity is started there will not be a record found.
    if (!$edata) {
        // A record in dmelearn_entries does not already exist.
        // Record the last page visited.
        $trackdata = new StdClass();
        $trackdata->page = "";
        $trackdata->module = "";
        // Create a record for dmelearn_entries.
        $edata = new StdClass();
        $edata->dmelearn = $elearnid;
        $edata->userid = $USER->id;
        $edata->grade = $percentage;
        $edata->trackdata = json_encode($trackdata);
        $edata->modified = time();
        // Insert the record.
        $DB->insert_record('dmelearn_entries', $edata);
    }

    // Get the 'last page visited' data out of 'dmelearn_entries'.
    $trackdata = json_decode($edata->trackdata);
    if (!is_object($trackdata)) {
        // Make a new blank trackdata object.
        $trackdata = new StdClass();
        $trackdata->page = "";
        $trackdata->module = "";
    }

    if (!isset($_REQUEST["page"]) && isset($trackdata->page) && $trackdata->page != ""
        && isset($trackdata->module) && $trackdata->module != "") {
        // No requested page or module in URL but $trackdata (last visited page) contains a module and page.
        echo '<script>window.location.href="' . $lmscontenturl . '&module=' . $trackdata->module
            .'&page=' . $trackdata->page . '";</script>';
        die();
    } else if (isset($_REQUEST["module"]) && $_REQUEST["module"] != "" && isset($_REQUEST["page"])
        && $_REQUEST["page"] != "") {
        // Set trackdata to be equal to the requested page and module.
        $trackdata->page = filter_var($_REQUEST['page'], FILTER_SANITIZE_STRING);
        $trackdata->module = filter_var($_REQUEST['module'], FILTER_SANITIZE_STRING);
        $edata->trackdata = json_encode($trackdata);
        $edata->modified = time();
        // Get percentage complete and update record.
        $edata->grade = $percentage;
        $DB->update_record('dmelearn_entries', $edata);

        update_the_gradebook($elearnid, $course_complete, $percentage);
    } else {
        if ($percentage > 0) {
            update_the_gradebook($elearnid, $course_complete, $percentage);
        }
    }
}

/**
 * Updates Moodles grades with the current course grades
 *
 * @global $COURSE
 * @global $USER
 * @global $DB
 * @param $elearnid
 * @param $course_complete
 * @param $percentage
 */
function update_the_gradebook($elearnid, $course_complete, $percentage) {
    global $COURSE, $USER, $DB;

    // Handle the Gradebook.
    $params = array($elearnid, $COURSE->id);
    $sql = "SELECT id, scaleid, grademin, grademax
            FROM {grade_items}
            WHERE itemtype = 'mod'
            AND itemmodule = 'dmelearn'
            AND iteminstance = ?
            AND courseid = ?
            LIMIT 1
            OFFSET 0";
    // Just get useful data out of grade_items.
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

        // Check if record exists in grade_grades.
        if (!$grade_grades) {
            // This is for moodle 2.7 or older.
            // Record in grade_grades does not exist.
            $grade_grades = new StdClass();
            $grade_grades->itemid = $grade_item->id;
            $grade_grades->userid = $USER->id;
            $grade_grades->rawgrademax = 100;
            $grade_grades->rawgrademin = 0;
            $grade_grades->rawscaleid = $grade_item->scaleid;
            $grade_grades->usermodified = $USER->id;
            $grade_grades->timecreated = time();
            $grade_grades->timemodified = time();

            if ($course_complete == 1) {
                // Set grade to 100% complete.
                $grade_grades->rawgrade = 100;
            } else {
                // Set grade to % complete.
                $grade_grades->rawgrade = round(($percentage / 100) * $grade_grades->rawgrademax, 5);
            }
            grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $elearnid, 0, $grade_grades);
        }

        // Record in grade_grades already exists.
        $grade_grades->timemodified = time();

        // Updating Grades
        if ($course_complete == 1 && $grade_grades->rawgrade != $grade_grades->rawgrademax) {
            // API says complete but gradebook does not reflect this yet.
            // Set raw grade to 100% complete.
            $grade_grades->rawgrade = $grade_grades->rawgrademax;
            $grade_grades->overridden = 0; // Clear any overridden activity module grades.
            grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $elearnid, 0, $grade_grades);

            // Update the course completions as complete
            update_course_completion_status($COURSE->id, cmid_from_elearnid($elearnid), $USER->id, true);

        } else if ($course_complete != 1 && $grade_grades->rawgrade == $grade_grades->rawgrademax) {
            // API says NOT complete but gradebook does not reflect this.
            // User has manually reset DM Assessments in Activity Module.

            // Get Current time in international format
            $currentTime = \DateTime::createFromFormat( 'U', time());
            $formattedString = $currentTime->format( 'c' );

            // Set Grade back to null and add Feedback about reset time.
            $grade_grades->rawgrade = null;
            $grade_grades->feedback = 'User reset at ' . $formattedString . '.';
            $grade_grades->overridden = 0; // Clear any overridden activity module grades.
            grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $elearnid, 0, $grade_grades);

            // Update the course completions as incomplete
            update_course_completion_status($COURSE->id, cmid_from_elearnid($elearnid), $USER->id, false);

            // Reset Just happened.
            //@todo clear_completion_dates(true);

        } else {
            // Grade as percentage of rawgrademax.
            $grade_of_rawgrademax = round(($percentage / 100) * $grade_grades->rawgrademax, 5);
            // Update if needed.
            if ($grade_grades->rawgrade != $grade_of_rawgrademax) {
                $grade_grades->rawgrade = $grade_of_rawgrademax;
                $complete = true;
                if ($percentage != '100') {
                    $grade_grades->rawgrade = null;
                    $complete = false;
                }
                //$grade_grades->overridden = time();
                grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $elearnid, 0, $grade_grades);

                // Update the course completions as incomplete.
                update_course_completion_status($COURSE->id, cmid_from_elearnid($elearnid), $USER->id, $complete);

            } else if ($grade_of_rawgrademax == 0) {
                $grade_grades->rawgrade = null;
                //$grade_grades->overridden = time();
                grade_update('mod/dmelearn', $COURSE->id, 'mod', 'dmelearn', $elearnid, 0, $grade_grades);

                // Update the course completions as incomplete.
                update_course_completion_status($COURSE->id, cmid_from_elearnid($elearnid), $USER->id, false);
            }
        }
    }
}

/**
 * If a learn has chosen to reset DM Assessment Questions
 * the Moodle Administrators may want to track the new completion dates
 * for their course completions.
 *
 * @todo make this optional in activity module settings
 * @param bool $purge_completion should the completion cache be cleared?
 */
function clear_completion_dates($purge_completion = false) {
    global $COURSE, $USER, $DB;

    $completion = new completion_info($COURSE);
    if ($completion->is_enabled()) {
        return;
    }

    // Handle Course Completion.
    $params = array('course' => $COURSE->id, 'userid' => $USER->id);
    $values = $DB->get_record('course_completions', $params);

    $timenow = time();

    if (isset($values->id)) {
        $course_completion_id = $values->id;
        $course_completion_timestarted = $values->timestarted;
        $course_completion_timeenrolled = $values->timeenrolled;

        $params['id'] = $course_completion_id;
        $params['timecompleted'] = null;
        $params['reaggregate'] = $timenow;

        if ($course_completion_timestarted > 0) {
            $params['timestarted'] = $timenow;
        } else if ($course_completion_timeenrolled > 0) {
            $params['timeenrolled'] = $timenow;
        }
        $DB->update_record('course_completions', $params);
    }

    // Handle Course Completion Criteria.
    $params2 = array('course' => $COURSE->id, 'userid' => $USER->id);
    $criterion = $DB->get_records('course_completion_crit_compl', $params2);

    foreach ((array)$criterion as $id => $criteria) {
        $crit_params = array();
        $crit_compl_id = $criteria->id;
        $criteria_id = $criteria->criteriaid;
        $timecompleted = $criteria->timecompleted; // need to be NULLed.

        $compl_criteria = $DB->get_record('course_completion_criteria', array('id' => $criteria_id));
        $crit_params['id'] = $crit_compl_id;
        $crit_params['timecompleted'] = null;

        // Reset Time for Activity Completion.
        if ($compl_criteria->module === 'dmelearn'
            && $compl_criteria->criteriatype == COMPLETION_CRITERIA_TYPE_ACTIVITY
        ) {
            $DB->update_record('course_completion_crit_compl', $crit_params);
        }

        // Reset time for Activity Completion.
        if ($compl_criteria->module === null
            && $compl_criteria->gradepass > 0
            && $compl_criteria->criteriatype == COMPLETION_CRITERIA_TYPE_GRADE
        ) {
            $crit_params['gradefinal'] = null;
            // We want to set timecompleted to null.
            $DB->update_record('course_completion_crit_compl', $crit_params);
        }
    }

    // @todo make this optional in activity module settings.
    if ($purge_completion) {
        cache::make('core', 'completion')->purge();
    }
}

/**
 * Get Course Module ID
 * 
 * @return int|null
 */
function cmid_from_elearnid($elearnid) {
    global $DB, $COURSE;

    $instance_id = $elearnid;
    $course_id = $COURSE->id;
    $module_name = 'dmelearn';

    $module = $DB->get_record('modules', array(
        'name' => $module_name
    ));

    if (!isset($module->id)) {
        return null;
    }

    $module_id = $module->id;

    $cm = $DB->get_record('course_modules', array(
        'course' => $course_id,
        'module' => $module_id,
        'instance' => $instance_id
    ));

    if (isset($cm->id)) {
        return $cm->id;
    }
    return null;
}
