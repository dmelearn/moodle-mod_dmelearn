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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * @package       mod_dmelearn
 * @author        Kien Vu, CJ Faulkner
 * @copyright     2015 BrightCookie (http://www.brightcookie.com.au), Digital Media e-Learning
 * @since         1.0.0
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'DM e-Learning';
$string['modulenameplural'] = 'DM e-Learning modules';
$string['modulename_help'] = 'This Digital Media e-Learning activity module enables e-Learning courses from the Digital Media e-Learning site to be loaded into Moodle.
<br><br> To load a course from this site the activity module will need to be connected through the sites API using a key provided by it\'s administrators';
$string['dmelearnsetting'] = 'Digital Media e-Learning settings';
$string['dmelearnname'] = 'DM e-Learning Moodle Activity Name';
$string['dmelearnname_help'] = 'Please enter a name for the Digital Media e-Learning course, this will be the title for the Moodle Activity.';
$string['elmo'] = 'ELMO';
$string['dmelearn'] = 'DM e-Learning';
$string['pluginadministration'] = 'DM e-Learning administration';
$string['pluginname'] = 'Digital Media e-Learning';
$string['dmelearncoursepath'] = 'Digital Media e-Learning Course';
$string['dmelearncoursepath_help'] = 'Please select a course from the drop down menu.<br><br>
<em>This list will display all courses that this Moodle Plugin has access to. If the course you require is not listed, please contact Digital Media.</em>';
$string['dmelearnsetting_desc'] = 'Please fill in the following form with credentials provided to you by the administrators of your Digital Media e-Learning provider (ELMO API) that you wish to connect with.';
$string['dmelearnsetting_URL'] = 'ELMO API URL';
$string['dmelearnsetting_URL_help'] = 'ELMO Endpoint URL with ending "/"  eg. http://example.com.au/';
$string['dmelearnsetting_public_key'] = 'ELMO public key';
$string['dmelearnsetting_public_key_help'] = 'The public key that has been provided to you';
$string['dmelearnsetting_secret_key'] = 'ELMO secret key';
$string['dmelearnsetting_secret_key_help'] = 'The secret key that has been provided to you';
$string['dmelearnsetting_appname'] = 'ELMO application name';
$string['dmelearnsetting_appname_help'] = 'The name of the application that has been specifically created for this Moodle';
$string['accessdenied'] = 'Access denied';
$string['alwaysopen'] = 'Always open';
$string['blankentry'] = 'Blank entry';
$string['daysavailable'] = 'Days available';
$string['editingended'] = 'Editing period has ended';
$string['editingends'] = 'Editing period ends';
$string['entries'] = 'Entries';
$string['feedbackupdated'] = 'Feedback updated for {$a} entries';
$string['dmelearn:addentries'] = 'Add Digital Media e-Learning entries';
$string['dmelearn:addinstance'] = 'Add Digital Media e-Learning instances';
$string['dmelearn:manageentries'] = 'Manage Digital Media e-Learning entries';
$string['dmelearnmail'] = '{$a->teacher} has posted some feedback on your
Digital Media e-Learning entry for \'{$a->ELMO}\'

You can see it appended to your Digital Media e-Learning entry:

    {$a->url}';
$string['dmelearnmailhtml'] = '{$a->teacher} has posted some feedback on your
Digital Media e-Learning entry for \'<i>{$a->ELMO}</i>\'<br /><br />
You can see it appended to your <a href="{$a->url}">Digital Media e­Learning entry</a>.';
$string['dmelearnquestion'] = 'Digital Media e-Learning question';
$string['mailsubject'] = 'Digital Media e-Learning feedback';
$string['newdmelearnentries'] = 'New Digital Media e-Learning entries';
$string['noentriesmanagers'] = 'There are no teachers';
$string['noentry'] = 'No entry';
$string['noratinggiven'] = 'No rating given';
$string['notopenuntil'] = 'This Digital Media e-Learning won\'t be open until';
$string['notstarted'] = 'You have not started this Digital Media e-Learning yet';
$string['overallrating'] = 'Overall rating';
$string['rate'] = 'Rate';
$string['removeentries'] = 'Remove all entries';
$string['saveallfeedback'] = 'Save all my feedback';
$string['showrecentactivity'] = 'Show recent activity';
$string['showoverview'] = 'Show Digital Media e-Learning overview on my moodle';
$string['startoredit'] = 'Start or edit my Digital Media e-Learning entry';
$string['viewallentries'] = 'View {$a} Digital Media e-Learning entries';
$string['viewentries'] = 'View entries';
// For mod_form.php.
$string['mfnocourses'] = '<style>.mf-info{color:#e32f0b;font-weight:bold;}</style><div><span class="mf-info">Information:</span> The DM e-Learning server did not return any available courses. Please contact Digital Media for help.</div><br>';
$string['mfinstructions'] = '<style>.mf-instruct{color:#2ca02c;font-weight:bold;}</style><div><span class="mf-instruct">Instructions:</span> The form will let you add a DM e-Learning course into an activity within your Moodle Course.
<ol><li>Select a DM e-Learning Course from the dropdown below.</li><li>OPTIONAL: Enter the number of months that you will allow access to a course after it has been 100% completed on DM e-Learning. If expired, users will need to reset the course and Moodle Grade before gaining access. (DEFAULT: 0 = No course expiry limits).</li>
<li>Provide a name for the Moodle Activity.</li><li>Save your selection.</li></ol></div>';
$string['mfselectcourse'] = 'Please select a course ...';
$string['mfnocourse'] = 'You must choose from one of the courses in the drop down.';
$string['mftfwrong'] = 'You must enter either 0 or a number of months that a completed course will be accessible before needing to be reset.';
$string['dmelearntimeframemonths'] = 'Force Reset After X Months Ago <br>(0 = No course expiry limits)';
$string['dmelearntimeframemonths_help'] = 'This options exists to prevent users that have completed a DM e-Learning course more than a certain amount of months ago (possibly via another website or Moodle) from syncing their completion percentage with this activities Moodle Grade.
<br><br>If a users completion date is greater than the amount of months set here, they will be required to reset their course and complete the course assessments from the beginning.<br><br>If you do not need this feature set this field to 0.';
$string['dmelearnpreventearlierthanyear'] = 'Force Reset if course was previously completed before this year!';
$string['dmelearnpreventearlierthanyear_help'] = 'e.g If you select 2016, any course completed in 2015 or earlier will not be synced to this activity. The user will be required to do a reset before starting the course within this activity.<br>Note: If set "Force Reset After X Months Ago" is ignored.';
