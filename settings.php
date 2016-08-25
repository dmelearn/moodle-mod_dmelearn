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
 * This file is used to add the DMELEARN settings into Moodle.
 *
 * @package       mod_dmelearn
 * @author        Kien Vu, AJ Dunn
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

global $CFG;

if ($ADMIN->fulltree) {
    // Add Settings
    $settings->add(new admin_setting_heading(
        'mod_dmelearn/dmelearnsettinginfo',
        '',
        get_string('dmelearnsetting_desc', 'dmelearn')
    ));

    $settings->add(new admin_setting_configtext(
        'mod_dmelearn/elmourl',
        get_string('dmelearnsetting_URL', 'dmelearn'),
        get_string('dmelearnsetting_URL_help', 'dmelearn'),
        null,
        PARAM_TEXT,
        68
    ));

    $settings->add(new admin_setting_configtext(
        'mod_dmelearn/elmoappname',
        get_string('dmelearnsetting_appname', 'dmelearn'),
        get_string('dmelearnsetting_appname_help', 'dmelearn'),
        null,
        PARAM_TEXT,
        68
    ));

    $settings->add(new admin_setting_configtext(
        'mod_dmelearn/elmopublickey',
        get_string('dmelearnsetting_public_key', 'dmelearn'),
        get_string('dmelearnsetting_public_key_help', 'dmelearn'),
        null,
        PARAM_TEXT,
        68
    ));

    $settings->add(new admin_setting_configtext(
        'mod_dmelearn/elmosecretkey',
        get_string('dmelearnsetting_secret_key', 'dmelearn'),
        get_string('dmelearnsetting_secret_key_help', 'dmelearn'),
        null,
        PARAM_TEXT,
        137
    ));
}
