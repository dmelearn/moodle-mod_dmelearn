<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants.
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 *
 * All the dmelearn specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package       mod_dmelearn
 * @author        Kien Vu, AJ Dunn
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
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
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the dmelearn into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $dmelearn An object from the form.
 * @param mod_dmelearn_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function dmelearn_add_instance($dmelearn, $mform = null) {
    global $DB;

    $dmelearn->timecreated = time();
    $dmelearn->id = $DB->insert_record('dmelearn', $dmelearn);

    dmelearn_grade_item_update($dmelearn);

    return $dmelearn->id;
}

/**
 * Updates an instance of the dmelearn in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $dmelearn An object from the form in mod_form.php.
 * @param mod_dmelearn_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function dmelearn_update_instance($dmelearn, $mform = null) {
    global $DB;

    $dmelearn->timemodified = time();
    $dmelearn->id = $dmelearn->instance;

    $result = $DB->update_record('dmelearn', $dmelearn);

    dmelearn_grade_item_update($dmelearn);
    dmelearn_update_grades($dmelearn, 0, false);

    return $result;
}

/**
 * Removes an instance of the dmelearn from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function dmelearn_delete_instance($id) {
    global $DB;

    $dmelearn = $DB->get_record('dmelearn', array('id' => $id));
    if (!$dmelearn) {
        return false;
    }

    // Delete any dependent records here.
    $DB->delete_records('dmelearn_entries', array('dmelearn' => $dmelearn->id));
    $DB->delete_records('dmelearn', array('id' => $dmelearn->id));

    dmelearn_grade_item_delete($dmelearn);

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 *
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param stdClass $course The course record
 * @param stdClass $user The user record
 * @param cm_info|stdClass $mod The course module info object or record
 * @param stdClass $dmelearn The dmelearn instance record
 * @return stdClass|null
 */
function dmelearn_user_outline($course, $user, $mod, $dmelearn) {
    global $DB;

    $return = new stdClass();
    // Get modified time.
    if ($entry = $DB->get_record('dmelearn_entries', array('userid' => $user->id, 'dmelearn' => $dmelearn->id))) {
        $return->time = $entry->modified;
    } else {
        $return->time = 0;
    }
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * It is supposed to echo directly without returning a value.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $dmelearn the module instance record
 */
function dmelearn_user_complete($course, $user, $mod, $dmelearn) {
    global $DB, $OUTPUT;

    if ($entry = $DB->get_record('dmelearn_entries', array('userid' => $user->id, 'dmelearn' => $dmelearn->id))) {
        echo $OUTPUT->box_start();
        if ($entry->modified) {
            echo '<p>' . get_string('lastedited') . ': ' . userdate($entry->modified) . '</p>';
        }
        echo $OUTPUT->box_end();
    } else {
        print_string('noentry', 'dmelearn');
    }
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in dmelearn activities and print it out.
 *
 * @param stdClass $course The course record
 * @param bool $viewfullnames Should we display full names
 * @param int $timestart Print activity since this timestamp
 * @return boolean True if anything was printed, otherwise false
 */
function dmelearn_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG, $DB, $OUTPUT;

    if (!get_config('dmelearn', 'showrecentactivity')) {
        return false;
    }

    $content = false;
    $dmelearns = null;

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
        $dm_log_info = dmelearn_log_info($log);

        $coursemodule = $modinfo->instances['dmelearn'][$dm_log_info->id];
        if (!$coursemodule->uservisible) {
            continue;
        }

        if (!isset($dmelearns[$log->info])) {
            $dmelearns[$log->info] = $dm_log_info;
            $dmelearns[$log->info]->time = $log->time;
            $dmelearns[$log->info]->url = str_replace('&', '&amp;', $log->url);
        }
    }

    if ($dmelearns) {
        $content = true;
        echo $OUTPUT->heading(get_string('newdmelearnentries', 'dmelearn') . ':', 3);
        foreach ($dmelearns as $dmelearn) {
            print_recent_activity_note(
                $dmelearn->time,
                $dmelearn,
                $dmelearn->name,
                $CFG->wwwroot . '/mod/dmelearn/' . $dmelearn->url
            );
        }
    }

    return $content;
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link dmelearn_print_recent_mod_activity()}.
 *
 * Returns void, it adds items into $activities and increases $index.
 *
 * @param array $activities sequentially indexed array of objects with added 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 */
function dmelearn_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@link dmelearn_get_recent_mod_activity()}
 *
 * @param stdClass $activity activity record with added 'cmid' property
 * @param int $courseid the id of the course we produce the report for
 * @param bool $detail print detailed report
 * @param array $modnames as returned by {@link get_module_types_names()}
 * @param bool $viewfullnames display users' full names
 */
function dmelearn_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 *
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * Note that this has been deprecated in favour of scheduled task API.
 *
 * @return boolean
 */
function dmelearn_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * For example, this could be array('moodle/site:accessallgroups') if the
 * module uses that capability.
 *
 * @return array
 */
function dmelearn_get_extra_capabilities() {
    return array();
}

/* Gradebook API */

/**
 * Is a given scale used by the instance of dmelearn?
 *
 * This function returns if a scale is being used by one dmelearn
 * if it has support for grading and scales.
 *
 * @param int $dmelearnid ID of an instance of this module.
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by the given dmelearn instance.
 */
function dmelearn_scale_used($dmelearnid, $scaleid) {
    global $DB;

    return $scaleid && $DB->record_exists('dmelearn', array('id' => $dmelearnid, 'grade' => -$scaleid));
}

/**
 * Checks if scale is being used by any instance of dmelearn.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by any dmelearn instance.
 */
function dmelearn_scale_used_anywhere($scaleid) {
    global $DB;

    return $scaleid && $DB->record_exists('dmelearn', array('grade' => -$scaleid));
}

/**
 * Creates or updates grade item for the given dmelearn instance.
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $dmelearn Instance object with extra cmidnumber and modname property.
 * @param bool $reset Reset grades in the gradebook.
 * @return void.
 */
function dmelearn_grade_item_update($dmelearn, $reset=false) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    $item = array();
    $item['itemname'] = clean_param($dmelearn->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;

    if (isset($dmelearn->cmidnumber)) {
        $item['idnumber'] = $dmelearn->cmidnumber;
    }

    if ($dmelearn->grade > 0) {
        $item['gradetype']  = GRADE_TYPE_VALUE;
        $item['grademax']   = $dmelearn->grade;
        $item['grademin']   = 0;
        $item['multfactor'] = 1.0;
    } else if ($dmelearn->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = -$dmelearn->grade;
    } else {
        $item['gradetype']  = GRADE_TYPE_NONE;
        $item['multfactor'] = 1.0;
    }
    if ($reset) {
        $item['reset'] = true;
    }

    grade_update('mod/dmelearn', $dmelearn->course, 'mod', 'dmelearn', $dmelearn->id, 0, null, $item);
}

/**
 * Delete grade item for given dmelearn instance.
 *
 * @param stdClass $dmelearn Instance object.
 * @return int grade_item
 */
function dmelearn_grade_item_delete($dmelearn) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    return grade_update('mod/dmelearn', $dmelearn->course, 'mod', 'dmelearn',
        $dmelearn->id, 0, null, array('deleted' => 1));
}

/**
 * Update dmelearn grades in the gradebook.
 *
 * Needed by {@link grade_update_mod_grades()}.
 *
 * @param stdClass $dmelearn Instance object with extra cmidnumber and modname property.
 * @param int $userid Update grade of specific user only, 0 means all participants.
 * @param bool $nullifnone null if grade does not exist
 */
function dmelearn_update_grades($dmelearn = null, $userid = 0, $nullifnone = false) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/gradelib.php');

    // Populate array of grade objects indexed by userid.
    if ($dmelearn != null) {
        if ($grades = dmelearn_get_user_grades($dmelearn, $userid)) {
            dmelearn_grade_item_update($dmelearn, $grades);
        } else if ($userid && $nullifnone) {
            $grade = new stdClass();
            $grade->userid   = $userid;
            $grade->rawgrade = null;
            dmelearn_grade_item_update($dmelearn, $grade);
        } else {
            dmelearn_grade_item_update($dmelearn);
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
            foreach ($recordset as $dminstance) {
                if ($dminstance->grade != false) {
                    dmelearn_update_grades($dminstance);
                } else {
                    dmelearn_grade_item_update($dminstance);
                }
            }
        }
    }
}

/* File API */

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function dmelearn_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for dmelearn file areas
 *
 * @package mod_dmelearn
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function dmelearn_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the dmelearn file areas
 *
 * @package mod_dmelearn
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the dmelearn's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function dmelearn_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

/* Navigation API */

// Removed unneeded functions.

/* Other Functions */

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
 * Returns the users with data in one dmelearn
 * (users with records in dmelearn_entries, students and teachers)
 *
 * @param $elearnid
 * @return mixed
 */
function dmelearn_get_participants($elearnid) {

    global $DB;

    // Get students.
    $students = $DB->get_records_sql(
        "SELECT DISTINCT u.id
        FROM {user} u,
        {dmelearn_entries} dme
        WHERE dme.dmelearn = ?
        AND u.id = dme.userid",
        array($elearnid)
    );

    // Get teachers.
    $teachers = $DB->get_records_sql(
        "SELECT DISTINCT u.id
        FROM {user} u,
        {dmelearn_entries} dme
        WHERE dme.dmelearn = ?
        AND u.id = dme.teacher",
        array($elearnid)
    );

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
    return array('reset_dmelearn' => 1);
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

        $status[] = array(
            'component' => get_string('modulenameplural', 'dmelearn'),
            'item' => get_string('removeentries', 'dmelearn'),
            'error' => false
        );
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

    if (!$dmelearns = get_all_instances_in_courses('dmelearn', $courses)) {
        return array();
    }

    $strelmo = get_string('modulename', 'dmelearn');
    $timenow = time();

    foreach ($dmelearns as $dmelearn) {
        $courses[$dmelearn->course]->format = $DB->get_field('course', 'format', array('id' => $dmelearn->course));
        if ($courses[$dmelearn->course]->format == 'weeks' && $dmelearn->days) {
            $coursestartdate = $courses[$dmelearn->course]->startdate;
            $dmelearn->timestart = $coursestartdate + (($dmelearn->section - 1) * 608400);

            if (!empty($dmelearn->days)) {
                $dmelearn->timefinish = $dmelearn->timestart + (3600 * 24 * $dmelearn->days);
            } else {
                $dmelearn->timefinish = 9999999999;
            }
            $dmelearnopen = ($dmelearn->timestart < $timenow && $timenow < $dmelearn->timefinish);
        } else {
            $dmelearnopen = true;
        }

        if ($dmelearnopen) {
            $str = '<div class="elmo overview"><div class="name">' .
                $strelmo . ': <a ' . ($dmelearn->visible ? '' : ' class="dimmed"') .
                ' href="' . $CFG->wwwroot . '/mod/dmelearn/view.php?id=' . $dmelearn->coursemodule . '">' .
                $dmelearn->name . '</a></div></div>';

            if (empty($htmlarray[$dmelearn->course]['dmelearn'])) {
                $htmlarray[$dmelearn->course]['dmelearn'] = $str;
            } else {
                $htmlarray[$dmelearn->course]['dmelearn'] .= $str;
            }
        }
    }
}

/**
 * Get Grade Information from the dmelearn_entries table.
 *
 * @param stdClass $dmelearn Instance object with extra cmidnumber and modname property.
 * @param int $userid optional User ID
 * @return array|bool grades or false
 */
function dmelearn_get_user_grades($dmelearn, $userid = 0) {
    global $DB;

    if (!$dmelearn) {
        return false;
    }
    // Dmelearn instance supplied.
    if ($userid) {
        // User ID supplied.
        $params = array($dmelearn->id, $userid);
        $sql = "SELECT userid, modified AS datesubmitted, grade AS rawgrade
            FROM {dmelearn_entries}
            WHERE dmelearn = ?
            AND userid = ?";
        $grades = $DB->get_records_sql($sql, $params);
    } else {
        // User ID Not supplied.
        $params = array($dmelearn->id);
        $sql = "SELECT userid, modified AS datesubmitted, grade AS rawgrade
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

/* Other SQL Functions */

/**
 * Get Dmelearn data
 *
 * @param $dmelearn
 * @param $currentgroup
 * @return array|null
 */
function dmelearn_get_users_done($dmelearn, $currentgroup) {
    global $DB;

    // Group users.
    if ($currentgroup != 0) {
        $params = array($currentgroup, $dmelearn->id);
        $sql = "SELECT u.*
                FROM {dmelearn_entries} dme
                JOIN {user} u
                ON dme.userid = u.id
                JOIN {groups_members} gm
                ON gm.userid = u.id
                AND gm.groupid = ?
                WHERE dme.dmelearn = ?
                ORDER BY dme.modified DESC";
        $dmelearns = $DB->get_records_sql($sql, $params);

    } else {
        $params = array($dmelearn->id);
        $sql = "SELECT u.*
            FROM {dmelearn_entries} dme
            JOIN {user} u
            ON dme.userid = u.id
            WHERE dme.dmelearn = ?
            ORDER BY dme.modified DESC";
        $dmelearns = $DB->get_records_sql($sql, $params);
    }

    $coursemodule = dmelearn_get_coursemodule($dmelearn->id);
    if (!$dmelearns || !$coursemodule) {
        return null;
    }

    // Remove unenrolled participants.
    foreach ($dmelearns as $key => $user) {
        $context = context_module::instance($coursemodule->id);

        $canadd = has_capability('mod/dmelearn:addentries', $context, $user);
        $entriesmanager = has_capability('mod/dmelearn:manageentries', $context, $user);

        if (!$entriesmanager && !$canadd) {
            unset($dmelearns[$key]);
        }
    }

    return $dmelearns;
}

/**
 * Counts all the dmelearn entries (optionally in a given group).
 *
 * @param $dmelearn
 * @param int $groupid
 * @return int
 */
function dmelearn_count_entries($dmelearn, $groupid = 0) {

    global $DB;

    $coursemodule = dmelearn_get_coursemodule($dmelearn->id);
    $context = context_module::instance($coursemodule->id);

    if ($groupid) { // How many in a particular group?

        $params = array($dmelearn->id, $groupid);
        $sql = "SELECT DISTINCT u.id
                FROM {dmelearn_entries} dme
                JOIN {groups_members} g
                ON g.userid = dme.userid
                JOIN {user} u
                ON u.id = g.userid
                WHERE dme.dmelearn = ?
                AND g.groupid = ?";
        $dmelearns = $DB->get_records_sql($sql, $params);

    } else {
        // Count all the entries from the whole course.
        $params = array($dmelearn->id);
        $sql = "SELECT DISTINCT u.id
                FROM {dmelearn_entries} dme
                JOIN {user} u
                ON u.id = dme.userid
                WHERE dme.dmelearn = ?";
        $dmelearns = $DB->get_records_sql($sql, $params);
    }

    if (!$dmelearns) {
        return 0;
    }

    // Remove un-enrolled participants.
    foreach ($dmelearns as $key => $user) {
        $canadd = has_capability('mod/dmelearn:addentries', $context, $user);
        $entriesmanager = has_capability('mod/dmelearn:manageentries', $context, $user);

        if (!$entriesmanager && !$canadd) {
            unset($dmelearns[$key]);
        }
    }

    return count($dmelearns);
}

/**
 * @param $log
 * @return mixed
 */
function dmelearn_log_info($log) {
    global $DB;

    $sql = "SELECT dm.*, u.firstname, u.lastname
            FROM {dmelearn} dm
            JOIN {dmelearn_entries} dme
            ON dme.dmelearn = dm.id
            JOIN {user} u
            ON u.id = dme.userid
            WHERE dme.id = ?";
    return $DB->get_record_sql($sql, array($log->info));
}

/**
 * Returns the dmelearn instance course_module id
 *
 * @param integer $elearnid instance id
 * @return object
 */
function dmelearn_get_coursemodule($elearnid) {
    global $DB;
    return $DB->get_record_sql(
        "SELECT cm.id
        FROM {course_modules} cm
        JOIN {modules} m
        ON m.id = cm.module
        WHERE cm.instance = ?
        AND m.name = 'dmelearn'",
        array($elearnid)
    );
}
