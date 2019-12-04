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

/**
 * Display an error on page if course is not available
 *
 * @copyright   2015 Digital Media e-learning
 * @since       1.0.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

echo $lmsmenu; // Show the LMS Top Menu Navigation. ?>
<div style="font-size: 20px;margin:25px;font-family: Arial, Helvetica, sans-serif;">

<br><p><strong>Error:
<?php if (isset($unauthorised)) : ?>
This Digital Media e-Learning course is not currently available. Please ensure your email address in Moodle is valid and current. Please contact your Moodle Administrator.
<?php else : ?>
This Digital Media e-Learning course is not currently available. Please contact your Moodle Administrator.
<?php endif; ?>
</strong></p><br>

<?php if (isset($course_version)) : ?>
<p>Course type <?php echo $course_version; ?> is unsupported in this version of the Digital Media e-Learning Plugin.</p>
<?php endif; ?>
</div>