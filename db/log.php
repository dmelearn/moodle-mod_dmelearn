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
 * Log definitions
 * @package     mod_dmelearn
 * @author      Kien Vu, AJ Dunn
 * @copyright   2015 - 2022 WCHN Digital Learning & Design, 2015 BrightCookie (http://www.brightcookie.com.au)
 * @since       1.0.0
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module' => 'dmelearn', 'action' => 'view', 'mtable' => 'dmelearn', 'field' => 'name'),
    array('module' => 'dmelearn', 'action' => 'view all', 'mtable' => 'dmelearn', 'field' => 'name'),
    array('module' => 'dmelearn', 'action' => 'view responses', 'mtable' => 'dmelearn', 'field' => 'name'),
    array('module' => 'dmelearn', 'action' => 'add entry', 'mtable' => 'dmelearn', 'field' => 'name'),
    array('module' => 'dmelearn', 'action' => 'update entry', 'mtable' => 'dmelearn', 'field' => 'name'),
    array('module' => 'dmelearn', 'action' => 'update feedback', 'mtable' => 'dmelearn', 'field' => 'name')
);
