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
 * Plugin administration pages are defined here.
 * This file is used to add the DMELEARN settings into Moodle.
 *
 * @package     mod_dmelearn
 * @category    admin
 * @author      Kien Vu, AJ Dunn
 * @copyright   2015 - 2022 WCHN Digital Learning & Design, 2015 BrightCookie (http://www.brightcookie.com.au)
 * @since       1.0.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

if ($hassiteconfig) {
    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
    if ($ADMIN->fulltree) {
        // Add Settings.
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
}