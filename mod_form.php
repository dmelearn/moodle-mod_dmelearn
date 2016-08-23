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
 * @author        Kien Vu, AJ Dunn, CJ Faulkner
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.1
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Class mod_dmelearn_mod_form
 */
class mod_dmelearn_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    function definition() {
        global $CFG;
        global $USER;

        include_once("content/elmo_lib.php");

        // Get array of available Courses.
        $elmocourses = get_key_courses();
        $elmocoursearr = array();

        $mform = $this->_form;

        if ($elmocourses->result) {
            // Default drop down message.
            $elmocoursearr['0'] = get_string('mfselectcourse', 'dmelearn');
            // List all the courses available.
            foreach ($elmocourses->result as $elmocourse) {
                $elmocoursearr[$elmocourse["path"]] = $elmocourse["course_short_name"];
            }
        } else {
            // No Courses available.
            $mform->addElement('html', get_string('mfnocourses', 'dmelearn'));
        }

        // Help text for new users.
        $mform->addElement('html', get_string('mfinstructions', 'dmelearn'));

        // Adding the rest of newmodule settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
        $mform->addElement('header', 'dmelearnsetting', get_string('dmelearnsetting', 'dmelearn'));

        $select = $mform->addElement('select', 'coursepath', get_string('dmelearncoursepath', 'dmelearn'), $elmocoursearr);

        // Add field for limiting already completed courses by completion date.
        $mform->addElement('text', 'timeframemonths', get_string('dmelearntimeframemonths', 'dmelearn'));
        $mform->setDefault('timeframemonths', '0');
        $mform->addHelpButton('timeframemonths', 'dmelearntimeframemonths', 'dmelearn');

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('dmelearnname', 'dmelearn'), array('size' => '64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'dmelearnname', 'dmelearn');

        // Adding the standard "intro" and "introformat" fields.
        $this->add_intro_editor();

        $mform->addRule('coursepath', null, 'required', null, 'client');

        $mform->addHelpButton('coursepath', 'dmelearncoursepath', 'dmelearn');

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }

    /**
     * Some basic validation.
     *
     * @param $data
     * @param $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        // Check that the default 'select a course' is not selected.
        if (($data['coursepath'] == '0')) {
            $errors['coursepath'] = get_string('mfnocourse', 'dmelearn');
        }
        // Check that limited months selected is number or null.
        if ((!filter_var($data['timeframemonths'], FILTER_VALIDATE_INT) && $data['timeframemonths'] != '0') ||
            ($data['timeframemonths'] < 0 || $data['timeframemonths'] > 480)) {
            $errors['timeframemonths'] = get_string('mftfwrong', 'dmelearn');
        }
        return $errors;
    }
}
