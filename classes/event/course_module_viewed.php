<?php

namespace mod_dmelearn\event;

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

defined('MOODLE_INTERNAL') || die();

/**
 *  The mod_dmelearn module viewed event.
 *
 * @package     mod_dmelearn\event
 * @author      Kien Vu
 * @copyright   2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-learning
 * @version     1.0.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_viewed extends \core\event\course_module_viewed {
    protected function init() {
        $this->data['objecttable'] = 'DMELEARN';
        parent::init();
    }
    // You might need to override get_url() and get_legacy_log_data() if view mode needs to be stored as well.
}