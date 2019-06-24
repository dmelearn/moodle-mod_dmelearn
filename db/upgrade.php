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
 * @see uninstall_plugin()
 *
 * @package       mod_dmelearn
 * @author        AJ Dunn, Kien Vu
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once $CFG->dirroot . '/mod/dmelearn/lib.php';

/**
 * Execute mod_dmelearn upgrade from the given old version.
 *
 * @param int $oldversion the version being upgraded from
 * @return bool was upgrade successful
 */
function xmldb_dmelearn_upgrade($oldversion = 0) {
    global $DB, $CFG;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    // Check if old version was between v1.0.2 and v1.2.0.
    if ($oldversion >= 2015051200 && $oldversion < 2015072300) {
        // Fix missing dmelearn grades in gradebook by forcing full update of grades for
        // courses containing dmelearn activities.
        global $CFG;
        require_once $CFG->libdir.'/gradelib.php';
        // Get all course ids that contain a dmelearn activity.
        $sql = "SELECT DISTINCT course.id
              FROM {course} course
              JOIN {course_modules} course_modules ON course.id = course_modules.course
              JOIN {modules} modules ON course_modules.module = modules.id
              WHERE modules.name = 'dmelearn'";
        if ($courseids = $DB->get_records_sql($sql)) {
            // Force update of all dmelearn activity grades.
            foreach ($courseids as $courseid) {
                grade_grab_course_grades($courseid->id, 'dmelearn');
            }
        }
    }

    // Add timeframemonths field to database to store how long ago a previously completed report
    // will be accepted as a result with an activity.
    if ($oldversion < 2015102700) {
        // Define field timeframemonths to be added to dmelearn.
        $table = new xmldb_table('dmelearn');
        // xmldb_field requires a name, type, precision, unsigned, notnull, sequence, default, previous
        $field = new xmldb_field('timeframemonths', XMLDB_TYPE_INTEGER, '10', true, null, null, '0', 'timemodified');

        // Add field course.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Upgraded to the version 2015102700 so the next time this block is skipped.
        upgrade_mod_savepoint(true, 2015102700, 'dmelearn');
    }

    // Add preventearlierthanyear field to database to store the earliest year that a course completion will be accepted.
    if ($oldversion < 2016121200) {
        // Define field preventearlierthanyear to be added to dmelearn.
        $table = new xmldb_table('dmelearn');
        // xmldb_field requires a name, type, precision, unsigned, notnull, sequence, default, previous
        $field = new xmldb_field('preventearlierthanyear', XMLDB_TYPE_INTEGER, '10', true, null, null, '0', 'timeframemonths');

        // Add field course.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Upgraded to the version 2016121200 so the next time this block is skipped.
        upgrade_mod_savepoint(true, 2016121200, 'dmelearn');
    }

    return true;
}
