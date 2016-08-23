# Changelog

## v1.6.0 (2016082300)
- Add DM-icons webfont v3.0.3 to all course templates.
- Add course template type/version 2.1
- Update Bootstrap to v3.3.7
- Update Guzzle to v5.3.1

## v1.5.0 (2016050200)
- Switch from Twig templating to Plates templating to allow for new courses. (No longer requires write permissions)
- Activities that require loading and saving to Interactive Activity Storage storage now work via API.
- Add support for older courses and new courses of type/version 4.
- Add NPM package.json file content folder.
- Add extra .htaccess files for Apache servers.
- Updated composer packages.
- General bug fixes.

## v1.4.0beta (2015102700)
- Add feature to force reset if course previously completed a certain amount of months ago.

## v1.3.1 (2015102600)
- Update font style/weights for template 2 courses.

## v1.3.0 (2015102200)
- Plugin now requires PHP 5.4.0 or above.
- Upgrade Guzzle to 5.3 and Twig to v1.22.3.
- Update course templates v1 and v2 to support newer DM e-Learning courses.
- On upgrade the plugin now removes old cache files in Twig Cache directory.
- Add Moodle PHPUnit tests for this plugin.

## v1.2.5 (2015080501)
- Fix issue with null if none in dmelearn_update_grades() function being set as true by default.
- $PAGE->activityrecord skipped in view.php as we don't have a record.

## v1.2.1 (2015072300)
- Fix issue with upgrading when Moodle is using a database prefix.

## v1.2.0 (2015072200)
- Twig Caching disabled if template_cache directory is not writable.
- Fix issue with missing dmelearn grades in gradebook.
- Add repair for missing grades to the upgrade function for affected versions.

## v1.1.1 (2015063000)
- Exit after '401' exception is caught.
- Remove unneeded example text.
- Move dmelearningsettings to top of mod_form.
- Fix Moodle 2.7 not showing 1st course page with module and page in URL.
- Update the grading so that it sets the correct grade on first load.

## v1.1.0 (2015062900)
- Add twig template engine and setup to allow different course versions to use different template styling.
- Remove depricated calls and console.logs.
- Gradebook update for the grading to include % complete.

## v1.0.4 (2015052800)
- Updated the navigation side menu and previous next buttons.

## v1.0.3 (2015052500)
- Fixed a potential bug where the reset_button could possibly be found elsewhere throughout courses.
- Fixed assessment summary pages that include questions that are randomised from a (block) bank.
- Moved some javascript from base to dmlearn.js and setup compression with gulp.
- Improved javascript to make dropdowns nicer in IE8.
- Added footer into base.
- Replaced small prev/next buttons to our modern style with text.

## v1.0.2 (2015051200)
- Fixed issue with modules not working if they are integers.
- Fixed grades not updating Course Totals in User Reports.

## v1.0.1 (2015050500)
- Added fixes for users with Internet Explorer 8.

## v.1.0.0 (2015042900)
- Initial version.
