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
?>
<!DOCTYPE html>
<?php
/**
 * Display an error on page if course is not available
 *
 * @copyright   2015 - 2022 WCHN Digital Learning & Design
 * @since       1.0.1
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

echo $lmsmenu; // Show the LMS Top Menu Navigation. ?>

<style>.lmsmenu {box-shadow: none;border-bottom: 1px solid #B9B9B9;}</style>

<div style="font-size: 20px;margin:25px;font-family: Arial, Helvetica, sans-serif; text-align: center;">

<br><br><br><p><strong>Notice</strong><br><br>
<?php if (isset($unauthorised) && $unauthorised) : ?>

This legacy Digital Media e-Learning course is not currently available.<br>
<em>Your past results are still on record</em><br>
A newer improved version of this course may exist on this eLearning site in another place<br>
Please check with your eLearning Support Team for assistance.

<?php elseif ((isset($apiMissing) && $apiMissing) || (isset($urlMissing) && $urlMissing)) : ?>

This legacy Digital Media e-Learning course is no longer available. (API URL is inactive)<br>
<em>Your past results are still on record</em><br>
A newer improved version of this course may exist on this eLearning site in another place<br>
Please check with your eLearning Support Team for assistance.

<?php else : ?>

This Digital Media e-Learning course is not currently available. Please contact your Moodle Administrator.

<?php endif; ?>

</p><br>

<?php if (isset($course_version)) : ?>
<p>Course type <?php echo $course_version; ?> is unsupported in this version of the Digital Media e-Learning Plugin.</p>
<?php endif; ?>
</div>
