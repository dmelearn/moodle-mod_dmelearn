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
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// STANDARD MODULE FUNCTIONS.

/**
 * Saves a new instance of a dmelearn record into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $elmo Submitted data from the form in mod_form.php
 * @return int The id of the newly inserted dmelearn record
 */
function dmelearn_add_instance(stdClass $elmo) {
    global $DB;

    $elmo->timemodified = time();

    $elmo->id = $DB->insert_record('dmelearn', $elmo);
    dmelearn_grade_item_update($elmo);

    return $elmo->id;
}

/**
 * Updates an instance of dmelearn in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $elmo An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function dmelearn_update_instance(stdClass $elmo) {
    global $DB;

    $elmo->timemodified = time();
    $elmo->id = $elmo->instance;

    $result = $DB->update_record('dmelearn', $elmo);
    dmelearn_grade_item_update($elmo);

    dmelearn_update_grades($elmo, 0, false);

    return $result;
}

/**
 * Removes an instance of dmelearn from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function dmelearn_delete_instance($id) {
    global $DB;
    $result = true;

    if (!$elmo = $DB->get_record('dmelearn', array('id' => $id))) {
        return false;
    }

    if (!$DB->delete_records('dmelearn_entries', array('dmelearn' => $elmo->id))) {
        $result = false;
    }

    if (!$DB->delete_records('dmelearn', array('id' => $elmo->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() for more info.
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if the feature is supported, null if unknown
 */
function dmelearn_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_RATE:
            return false;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        case FEATURE_GROUPMEMBERSONLY:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * @return array
 */
function dmelearn_get_view_actions() {
    return array('view', 'view all', 'view responses');
}

/**
 * @return array
 */
function dmelearn_get_post_actions() {
    return array('add entry', 'update entry', 'update feedback');
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 *
 * @todo BC: should add missing $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param $mod The course module info object or record
 * @param stdClass $elmo The dmelearn instance record
 * @return stdClass|null
 */
function dmelearn_user_outline($course, $user, $mod, $elmo) {
    global $DB;

    if ($entry = $DB->get_record('dmelearn_entries', array('userid' => $user->id, 'dmelearn' => $elmo->id))) {
        $result->time = $entry->modified;
        return $result;
    }

    return null;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course The current course record
 * @param stdClass $user The record of the user we are generating report for
 * @param cm_info $mod Course module info
 * @param stdClass $elmo The module instance record
 */
function dmelearn_user_complete($course, $user, $mod, $elmo) {

    global $DB, $OUTPUT;

    if ($entry = $DB->get_record('dmelearn_entries', array('userid' => $user->id, 'dmelearn' => $elmo->id))) {

        echo $OUTPUT->box_start();

        if ($entry->modified) {
            echo "<p><font size=\"1\">" . get_string("lastedited") . ": " . userdate($entry->modified) . "</font></p>";
        }

        echo $OUTPUT->box_end();

    } else {
        print_string('noentry', 'dmelearn');
    }
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return bool
 */
function dmelearn_cron() {
    global $CFG, $USER, $DB;

    return true;
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in newmodule activities and print it out.
 *
 * @param $course The course record
 * @param $isteacher Should we display full names
 * @param $timestart Print activity since this timestamp
 * @return bool True if anything was printed, otherwise false
 */
function dmelearn_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG, $DB, $OUTPUT;

    if (!get_config('dmelearn', 'showrecentactivity')) {
        return false;
    }

    $content = false;
    $elmos = null;

    // Log table should not be used here.
    $select = "time > ? AND
               course = ? AND
               module = 'dmelearn' AND
               (action = 'add entry' OR action = 'update entry')";
    if (!$logs = $DB->get_records_select('log', $select, array($timestart, $course->id), 'time ASC')) {
        return false;
    }

    $modinfo = & get_fast_modinfo($course);
    foreach ($logs as $log) {
        // Get elmo info.  It will be needed later.
        $j_log_info = dmelearn_log_info($log);

        $coursemodule = $modinfo->instances['dmelearn'][$j_log_info->id];
        if (!$coursemodule->uservisible) {
            continue;
        }

        if (!isset($elmos[$log->info])) {
            $elmos[$log->info] = $j_log_info;
            $elmos[$log->info]->time = $log->time;
            $elmos[$log->info]->url = str_replace('&', '&amp;', $log->url);
        }
    }

    if ($elmos) {
        $content = true;
        echo $OUTPUT->heading(get_string('newdmelearnentries', 'dmelearn') . ':', 3);
        foreach ($elmos as $elmo) {
            print_recent_activity_note($elmo->time, $elmo, $elmo->name,
                $CFG->wwwroot . '/mod/dmelearn/' . $elmo->url);
        }
    }

    return $content;
}

/**
 * Returns the users with data in one dmelearn
 * (users with records in dmelearn_entries, students and teachers)
 *
 * @param $elearnid
 * @return mixed
 */
function dmelearn_get_participants($elearnid) {

    global $DB;

    // Get students.
    $students = $DB->get_records_sql("SELECT DISTINCT u.id
                                      FROM {user} u,
                                      {dmelearn_entries} dme
                                      WHERE dme.dmelearn = ?
                                      AND u.id = dme.userid", array($elearnid));

    // Get teachers.
    $teachers = $DB->get_records_sql("SELECT DISTINCT u.id
                                      FROM {user} u,
                                      {dmelearn_entries} dme
                                      WHERE dme.dmelearn = ?
                                      AND u.id = dme.teacher", array($elearnid));

    // Add teachers to students.
    if ($teachers) {
        foreach ($teachers as $teacher) {
            $students[$teacher->id] = $teacher;
        }
    }
    // Return students array (it contains an array of unique users).
    return ($students);
}

/**
 * This function returns if a scale is being used by one dmelearn
 *
 * @param int $elearnid ID of an instance of this module
 * @param int $scaleid ID of the scale
 * @return bool True if the scale is used by the given newmodule instance
 */
function dmelearn_scale_used($elearnid, $scaleid) {
    global $DB;
    $return = false;

    $rec = $DB->get_record('dmelearn', array('id' => $elearnid, 'grade' => -$scaleid));

    if (!empty($rec) && !empty($scaleid)) {
        $return = true;
    }

    return $return;
}

/**
 * Checks if scale is being used by any instance of dmelearn
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any elmo
 */
function dmelearn_scale_used_anywhere($scaleid) {
    global $DB;

    if ($scaleid and $DB->get_records('dmelearn', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Implementation of the function for printing the form elements that control
 * whether the course reset functionality affects the dmelearn.
 *
 * @param object $mform form passed by reference
 */
function dmelearn_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'dmelearnheader', get_string('modulenameplural', 'dmelearn'));
    $mform->addElement('advcheckbox', 'reset_dmelearn', get_string('removemessages', 'dmelearn'));
}

/**
 * Course reset form defaults.
 *
 * @param object $course
 * @return array
 */
function dmelearn_reset_course_form_defaults($course) {
    return array('reset_dmelearn'=>1);
}

/**
 * Removes all entries
 *
 * @param object $data
 * @return array
 */
function dmelearn_reset_userdata($data) {

    global $CFG, $DB;

    $status = array();
    if (!empty($data->reset_elmo)) {

        $sql = "SELECT dm.id
                FROM {dmelearn} dm
                WHERE dm.course = ?";
        $params = array($data->courseid);

        $DB->delete_records_select('dmelearn_entries', 'dmelearn IN ($sql)', $params);

        $status[] = array('component' => get_string('modulenameplural', 'dmelearn'),
                          'item' => get_string('removeentries', 'dmelearn'),
                          'error' => false);
    }

    return $status;
}

/**
 * @param $courses
 * @param $htmlarray
 * @return array
 */
function dmelearn_print_overview($courses, &$htmlarray) {

    global $USER, $CFG, $DB;

    if (!get_config('dmelearn', 'overview')) {
        return array();
    }

    if (empty($courses) || !is_array($courses) || count($courses) == 0) {
        return array();
    }

    if (!$elmos = get_all_instances_in_courses('dmelearn', $courses)) {
        return array();
    }

    $strelmo = get_string('modulename', 'dmelearn');

    $timenow = time();

    foreach ($elmos as $elmo) {

        $courses[$elmo->course]->format = $DB->get_field('course', 'format', array('id' => $elmo->course));

        if ($courses[$elmo->course]->format == 'weeks' AND $elmo->days) {

            $coursestartdate = $courses[$elmo->course]->startdate;

            $elmo->timestart = $coursestartdate + (($elmo->section - 1) * 608400);
            if (!empty($elmo->days)) {
                $elmo->timefinish = $elmo->timestart + (3600 * 24 * $elmo->days);
            } else {
                $elmo->timefinish = 9999999999;
            }
            $elmoopen = ($elmo->timestart < $timenow && $timenow < $elmo->timefinish);

        } else {
            $elmoopen = true;
        }

        if ($elmoopen) {
            $str = '<div class="elmo overview"><div class="name">' .
                   $strelmo . ': <a ' . ($elmo->visible ? '' : ' class="dimmed"') .
                   ' href="' . $CFG->wwwroot . '/mod/dmelearn/view.php?id=' . $elmo->coursemodule . '">' .
                   $elmo->name . '</a></div></div>';

            if (empty($htmlarray[$elmo->course]['dmelearn'])) {
                $htmlarray[$elmo->course]['dmelearn'] = $str;
            } else {
                $htmlarray[$elmo->course]['dmelearn'] .= $str;
            }
        }
    }
}

/**
 * @param $elmo
 * @param int $userid optional
 * @return bool
 */
function dmelearn_get_user_grades($elmo, $userid = 0) {

    global $DB;

    if (!$elmo) {
        return false;
    } else {
        // $elmo supplied.
        if ($userid) { // User ID supplied.
            $params = array($elmo->id, $userid);
            $sql = "SELECT userid,
                modified AS datesubmitted,
                grade AS rawgrade
                FROM {dmelearn_entries}
                WHERE dmelearn = ?
                AND userid = ?";
            $grades = $DB->get_records_sql($sql, $params);
        } else { // User ID Not supplied.
            $params = array($elmo->id);
            $sql = "SELECT userid,
                modified AS datesubmitted,
                grade AS rawgrade
                FROM {dmelearn_entries}
                WHERE dmelearn = ?";
            $grades = $DB->get_records_sql($sql, $params);
        }

        if ($grades) {
            foreach ($grades as $key => $grade) {
                $grades[$key]->id = $grade->userid;
            }
        } else {
            return false;
        }

        return $grades;
    }
}

/**
 * Update dmelearn grades
 *
 * @param object optional $elmo If it is null, updates all
 * @param int $userid If is false al users
 * @param boolean $nullifnone Return null if grade does not exist
 */
function dmelearn_update_grades($elmo = null, $userid = 0, $nullifnone = true) {

    global $CFG, $DB;

    if (!function_exists('grade_update')) {
        // Workaround for buggy PHP versions.
        require_once($CFG->libdir . '/gradelib.php');
    }

    if ($elmo != null) {
        if ($grades = dmelearn_get_user_grades($elmo, $userid)) {
            dmelearn_grade_item_update($elmo, $grades);
        } else if ($userid && $nullifnone) {
            $grade = new object();
            $grade->userid   = $userid;
            $grade->rawgrade = null;
            dmelearn_grade_item_update($elmo, $grade);
        } else {
            dmelearn_grade_item_update($elmo);
        }
    } else {
        $sql = "SELECT dm.*, cm.idnumber
                AS cmidnumber
                FROM {course_modules} cm
                JOIN {modules} m
                ON m.id = cm.module
                JOIN {dmelearn} dm
                ON cm.instance = dm.id
                WHERE m.name = 'dmelearn'";
        if ($recordset = $DB->get_records_sql($sql)) {
            foreach ($recordset as $elmo) {
                if ($elmo->grade != false) {
                    dmelearn_update_grades($elmo);
                } else {
                    dmelearn_grade_item_update($elmo);
                }
            }
        }
    }
}

/**
 * Create grade item for given dmelearn
 *
 * @param object $elmo object with extra cmidnumber
 * @param mixed optional $grades array/object of grade(s); 'reset' means reset grades in gradebook
 * @return mixed|int 0 if ok, error code otherwise
 */
function dmelearn_grade_item_update($elmo, $grades = null) {
    global $CFG;
    if (!function_exists('grade_update')) {
        // Workaround for buggy PHP versions.
        require_once($CFG->libdir . '/gradelib.php');
    }

    if (array_key_exists('cmidnumber', $elmo)) {
        $params = array('itemname' => $elmo->name, 'idnumber' => $elmo->cmidnumber);
    } else {
        $params = array('itemname' => $elmo->name);
    }

    if ($elmo->grade > 0) {
        $params['gradetype']  = GRADE_TYPE_VALUE;
        $params['grademax']   = $elmo->grade;
        $params['grademin']   = 0;
        $params['multfactor'] = 1.0;

    } else if ($elmo->grade < 0) {
        $params['gradetype'] = GRADE_TYPE_SCALE;
        $params['scaleid']   = -($elmo->grade);

    } else {
        $params['gradetype']  = GRADE_TYPE_NONE;
        $params['multfactor'] = 1.0;
    }

    if ($grades === 'reset') {
        $params['reset'] = true;
        $grades = null;
    }

    return grade_update('mod/dmelearn', $elmo->course, 'mod', 'dmelearn', $elmo->id, 0, $grades, $params);
}

/**
 * Delete grade item for given dmelearn
 *
 * @param object $elmo
 * @return mixed|object grade_item
 */
function dmelearn_grade_item_delete($elmo) {
    global $CFG;

    require_once($CFG->libdir . '/gradelib.php');

    return grade_update('mod/dmelearn', $elmo->course, 'mod', 'dmelearn', $elmo->id, 0, null, array('deleted' => 1));
}

// SQL FUNCTIONS.

/**
 * @param $elmo
 * @param $currentgroup
 * @return null
 */
function dmelearn_get_users_done($elmo, $currentgroup) {
    global $DB;

    // Group users.
    if ($currentgroup != 0) {
        $params = array($currentgroup, $elmo->id);
        $sql = "SELECT u.*
                FROM {dmelearn_entries} dme
                JOIN {user} u
                ON dme.userid = u.id
                JOIN {groups_members} gm
                ON gm.userid = u.id
                AND gm.groupid = ?
                WHERE dme.dmelearn = ?
                ORDER BY dme.modified DESC";
        $elmos = $DB->get_records_sql($sql, $params);

    } else {
        $params = array($elmo->id);
        $sql = "SELECT u.*
            FROM {dmelearn_entries} dme
            JOIN {user} u
            ON dme.userid = u.id
            WHERE dme.dmelearn = ?
            ORDER BY dme.modified DESC";
        $elmos = $DB->get_records_sql($sql, $params);
    }

    $coursemodule = dmelearn_get_coursemodule($elmo->id);
    if (!$elmos || !$coursemodule) {
        return null;
    }

    // Remove unenrolled participants.
    foreach ($elmos as $key => $user) {

        $context = get_context_instance(CONTEXT_MODULE, $coursemodule->id);

        $canadd = has_capability('mod/dmelearn:addentries', $context, $user);
        $entriesmanager = has_capability('mod/dmelearn:manageentries', $context, $user);

        if (!$entriesmanager and !$canadd) {
            unset($elmos[$key]);
        }
    }

    return $elmos;
}

/**
 * Counts all the dmelearn entries (optionally in a given group).
 *
 * @param $elmo
 * @param int $groupid
 * @return int
 */
function dmelearn_count_entries($elmo, $groupid = 0) {

    global $DB;

    $coursemodule = dmelearn_get_coursemodule($elmo->id);
    $context = get_context_instance(CONTEXT_MODULE, $coursemodule->id);

    if ($groupid) { // How many in a particular group?

        $params = array($elmo->id, $groupid);
        $sql = "SELECT DISTINCT u.id
                FROM {dmelearn_entries} dme
                JOIN {groups_members} g
                ON g.userid = dme.userid
                JOIN {user} u
                ON u.id = g.userid
                WHERE dme.dmelearn = ?
                AND g.groupid = ?";
        $elmos = $DB->get_records_sql($sql, $params);

    } else { // Count all the entries from the whole course.

        $params = array($elmo->id);
        $sql = "SELECT DISTINCT u.id
                FROM {dmelearn_entries} dme
                JOIN {user} u
                ON u.id = dme.userid
                WHERE dme.dmelearn = ?";
        $elmos = $DB->get_records_sql($sql, $params);
    }

    if (!$elmos) {
        return 0;
    }

    // Remove unenrolled participants.
    foreach ($elmos as $key => $user) {

        $canadd = has_capability('mod/dmelearn:addentries', $context, $user);
        $entriesmanager = has_capability('mod/dmelearn:manageentries', $context, $user);

        if (!$entriesmanager && !$canadd) {
            unset($elmos[$key]);
        }
    }

    return count($elmos);
}

/**
 * @param $log
 * @return mixed
 */
function dmelearn_log_info($log) {
    global $DB;

    $params = array($log->info);
    $sql = "SELECT dm.*, u.firstname, u.lastname
            FROM {dmelearn} dm
            JOIN {dmelearn_entries} dme
            ON dme.dmelearn = dm.id
            JOIN {user} u
            ON u.id = dme.userid
            WHERE dme.id = ?";
    return $DB->get_record_sql($sql);
}

/**
 * Returns the dmelearn instance course_module id
 *
 * @param integer $elearnid
 * @return object
 */
function dmelearn_get_coursemodule($elearnid) {
    global $DB;
    return $DB->get_record_sql("SELECT cm.id
                                FROM {course_modules} cm
                                JOIN {modules} m
                                ON m.id = cm.module
                                WHERE cm.instance = ?
                                AND m.name = 'dmelearn'", array($elearnid));
}