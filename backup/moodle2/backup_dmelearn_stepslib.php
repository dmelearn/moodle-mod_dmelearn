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
 * Define all the backup steps that will be used by the backup_assign_activity_task
 *
 * @package   mod_dmelearn
 * @copyright 2015 NetSpot, Digital Media e-learning
 * @version   1.0.0
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class backup_dmelearn_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        $dmelearn = new backup_nested_element('dmelearn', array('id'), array(
            'name', 'intro', 'introformat', 'coursepath', 'maxattempt', 'grade', 'timemodified'));

        $entries = new backup_nested_element('entries');

        $entry = new backup_nested_element('entry', array('id'), array(
            'userid', 'grade','trackdata', 'modified'));

        // elmo -> entries -> entry
        $dmelearn->add_child($entries);
        $entries->add_child($entry);

        // Sources.
        $dmelearn->set_source_table('dmelearn', array('id' => backup::VAR_ACTIVITYID));

        if ($this->get_setting_value('userinfo')) {
            $entry->set_source_table('dmelearn_entries', array('dmelearn' => backup::VAR_PARENTID));
        }

        // Define id annotations.
        $entry->annotate_ids('user', 'userid');

        // Define file annotations.
        $dmelearn->annotate_files('mod_dmelearn', 'intro', null); // This file areas haven't itemid
        $entry->annotate_files('mod_dmelearn_entries', 'grade', null); // This file areas haven't itemid
        $entry->annotate_files('mod_dmelearn_entries', 'trackdata', null); // This file areas haven't itemid

        return $this->prepare_activity_structure($dmelearn);
    }
}