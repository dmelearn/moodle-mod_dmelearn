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
 * @author        Kien Vu
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

 /**
  * Define all the restore steps that will be used by the restore_dmelearn_activity_task
  */
class restore_dmelearn_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        $paths[] = new restore_path_element('dmelearn', '/activity/dmelearn');

        if ($this->get_setting_value('userinfo')) {
            $paths[] = new restore_path_element('dmelearn_entry', '/activity/dmelearn/entries/entry');
        }

        return $this->prepare_activity_structure($paths);
    }

    protected function process_dmelearn($data) {

        global $DB;

        $data = (Object)$data;

        $oldid = $data->id;
        unset($data->id);

        $data->course = $this->get_courseid();
        $data->timemodified = $this->apply_date_offset($data->timemodified);

        $newid = $DB->insert_record('dmelearn', $data);
        $this->apply_activity_instance($newid);
    }

    protected function process_dmelearn_entry($data) {

        global $DB;

        $data = (Object)$data;

        $oldid = $data->id;
        unset($data->id);

        $data->dmelearn = $this->get_new_parentid('dmelearn');
        $data->modified = $this->apply_date_offset($data->modified);
        $data->userid = $this->get_mappingid('user', $data->userid);
        $data->grade = $this->get_mappingid('user', $data->grade);
        $data->trackdata = $this->get_mappingid('user', $data->trackdata);
        $newid = $DB->insert_record('dmelearn_entries', $data);
        $this->set_mapping('dmelearn_entry', $oldid, $newid);
    }

    protected function after_execute() {
        $this->add_related_files('mod_dmelearn', 'intro', null);
        $this->add_related_files('mod_dmelearn_entries', 'trackdata', null);
        $this->add_related_files('mod_dmelearn_entries', 'grade', null);
    }
}
