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
 * Plugin version and other meta-data are defined here.
 *
 * @package     mod_dmelearn
 * @author      CJ Faulkner, AJ Dunn
 * @copyright   2015 - 2022 WCHN Digital Learning & Design
 * @since       1.0.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'mod_dmelearn';
$plugin->release = 'v1.11.0';
$plugin->version = 2022030400;
$plugin->requires = 2014051200; // Requires Moodle 2.7 or newer.
$plugin->maturity = MATURITY_STABLE;
$plugin->cron = 0;
$plugin->dependencies = array();
