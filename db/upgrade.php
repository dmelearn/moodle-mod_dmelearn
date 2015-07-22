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
 * @see uninstall_plugin()
 *
 * @package       mod_dmelearn
 * @author        Kien Vu
 * @copyright     2015 Brightcookie.com {@link http://www.brightcookie.com.au}
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/mod/dmelearn/lib.php');

/**
 * Execute mod_dmelearn upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_dmelearn_upgrade($oldversion = 0) {
    global $DB;

    // Check if version between 1.0.2 and 1.1.1.
    if ($oldversion >= 2015051200 && $oldversion < 2015072200) {
        // Fix missing dmelearn grades in gradebook by forcing full update of grades for
        // courses containing dmelearn activities.
        global $CFG;
        require_once($CFG->libdir.'/gradelib.php');
        // Get all course ids that contain a dmelearn activity.
        $sql = "SELECT DISTINCT course.id
              FROM course
              JOIN course_modules ON course.id = course_modules.course
              JOIN modules ON course_modules.module = modules.id
              WHERE modules.name = 'dmelearn'";
        if ($courseids = $DB->get_records_sql($sql)) {
            // Force update of all dmelearn activity grades.
            foreach ($courseids as $courseid)
            {
                grade_grab_course_grades($courseid->id, 'dmelearn');
            }
        }
    }

    return true;
}