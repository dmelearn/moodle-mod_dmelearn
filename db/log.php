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
 * Log definitions
 * @package       mod_dmelearn
 * @author        Kien Vu, AJ Dunn
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();

$logs = array(
    array('module'=>'dmelearn', 'action'=>'view', 'mtable'=>'dmelearn', 'field'=>'name'),
    array('module'=>'dmelearn', 'action'=>'view all', 'mtable'=>'dmelearn', 'field'=>'name'),
    array('module'=>'dmelearn', 'action'=>'view responses', 'mtable'=>'dmelearn', 'field'=>'name'),
    array('module'=>'dmelearn', 'action'=>'add entry', 'mtable'=>'dmelearn', 'field'=>'name'),
    array('module'=>'dmelearn', 'action'=>'update entry', 'mtable'=>'dmelearn', 'field'=>'name'),
    array('module'=>'dmelearn', 'action'=>'update feedback', 'mtable'=>'dmelearn', 'field'=>'name')
);