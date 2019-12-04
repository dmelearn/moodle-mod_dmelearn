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

defined('MOODLE_INTERNAL') || die();

/**
 * Class Elmo_web_service_hash
 *
 * @package    mod_dmelearn
 * @author     Chris Barton, AJ Dunn
 * @copyright  2015 Chris Barton, Digital Media e-learning
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
abstract class Elmo_web_service_hash
{
    /**
     *
     * Generates a hash token for use in the web service
     * Send token with each request
     *
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param $secret_key
     * @return string The generated hash token
     */
    public static function generate($first_name, $last_name, $email, $secret_key) {
        return hash_hmac('sha256', $first_name . $last_name . $email, $secret_key);
    }
}
