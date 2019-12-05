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
 * @package       mod_dmelearn
 * @author        Kien Vu
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/dmelearn/backup/moodle2/restore_dmelearn_stepslib.php');
/**
 * elmo restore task that provides all the settings and steps to perform one complete restore of the activity
 */

class restore_dmelearn_activity_task extends restore_activity_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        $this->add_step(new restore_dmelearn_activity_structure_step('dmelearn_structure', 'dmelearn.xml'));
    }

    static public function define_decode_contents() {
        $contents = array();
        $contents[] = new restore_decode_content('dmelearn', array('intro'), 'dmelearn');
        $contents[] = new restore_decode_content('dmelearn_entries', array('grade', 'trackdata'), 'dmelearn_entry');
        return $contents;
    }

    static public function define_decode_rules() {
        $rules = array();
        $rules[] = new restore_decode_rule('ELMOINDEX', '/mod/dmelearn/index.php?id=$1', 'course');
        $rules[] = new restore_decode_rule('ELMOVIEWBYID', '/mod/dmelearn/view.php?id=$1', 'course_module');
        // This part for future report.
        $rules[] = new restore_decode_rule('ELMOREPORT', '/mod/dmelearn/report.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('ELMOEDIT', '/mod/dmelearn/edit.php?id=$1', 'course_module');
        return $rules;
    }

    public static function define_restore_log_rules() {
        $rules = array();
        $rules[] = new restore_log_rule('dmelearn', 'view', 'view.php?id={course_module}', '{dmelearn}');
        return $rules;
    }

    public static function define_restore_log_rules_for_course() {
        $rules = array();
        $rules[] = new restore_log_rule('dmelearn', 'view all', 'index.php?id={course}', null);
        return $rules;
    }
}
