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

namespace mod_dmelearn\cache;

defined('MOODLE_INTERNAL') || die();

/**
 * Class Cache
 * @package       mod_dmelearn\cache
 * @author        Chris Barton, AJ Dunn
 * @copyright     Digital Media e-learning
 * @version       1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class Cache
{
    protected static $cache_dir = "cache/";

    /**
     * Cache IS NOT used in this version (1.0.0) due to unresolved issues with caching
     * dynamic content. This could still be used to cache navigation etc.
     *
     * @param $filename
     * @param $string
     * @todo re-implement limited caching for static content.
     */
    public static function caching($filename, $string) {
        //$f = fopen(self::$cache_dir.$filename.'.txt', 'w+');
        //fwrite($f, $string);
        //fclose($f);
    }

    /**
     * Retrieve a cached file.
     *
     * @param $filename
     * @return string|false
     */
    public static function retrieve($filename) {
        $filename = $filename . '.txt';
        $path = self::$cache_dir . $filename;
        if (file_exists($path)) {
            // Read the file into a string.
            $handle = fopen($path, "r+");
            $contents = fread($handle, filesize($path));
            fclose($handle);
            return $contents;
        } else {
            // File not found.
            return false;
        }
    }
}
