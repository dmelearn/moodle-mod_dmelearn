# moodle-mod_dmelearn
- Plugin to access/use Digital Media e-Learning course content as a Moodle Activity rather than users accessing the "DM e-learning" website directly.
- This plugin is only useful for Moodle Managers that have been granted API access to "Digital Media e-Learning" courses with valid application settings.
- Usage of this plugin creates an account for each user on DM e-learning to track course completion and provide certificates, completion of each course is also recorded within Moodle.

## Setup
### Add required third party components (Developers)
- Requires [Composer](https://getcomposer.org/), [Bower](http://bower.io/), [Gulp](http://gulpjs.com/), [gulp-rename](https://www.npmjs.com/package/gulp-rename), [del](https://www.npmjs.com/package/del) and [gulp-uglify](https://www.npmjs.com/package/gulp-uglify) to be setup and installed.
- Open the `content` folder inside this plugin from the terminal/command line.
- Run `composer install`.
- Run `bower install`.
- Run `gulp`.

### Remove unused third party components files before deployment (Developers)
- Open the `content` folder inside this plugin from the terminal/command line.
- Run `gulp clean:vendor` and `gulp del:bower`.

### Installation / Usage
* Either install the plugin as a .zip file via Moodle Plugin installer page or ...
* Create a `dmelearn` folder inside `mod/` in the root directory of you Moodle install.
* Copy all plugin files and folders into `dmelearn`.
* *Please make sure that the folder `mod/dmelearn/content/template_cache/` is writable by Moodle.*
* After the plugin has been installed by a Moodle Adminstrator ...
* Go to Site Administration -> Plugins -> Activity modules -> Digital Media e-Learning.
* Enter all required configuration details for the mod_dmelearn plugin (Provided to DM partners only).
* `DM e-Learning` Activities will now be available to add to Moodle Courses.

## Requirements
- PHP 5.3.3 or above with the [cURL extension](http://php.net/manual/en/book.curl.php).
- Moodle 2.5, 2.6, 2.7, 2.8+
- https://docs.moodle.org/25/en/Installing_Moodle#Requirements

## Authors
- Digital Media e-Learning team.

## Acknowledgements
- This project contains code contributed by Chris Barton and BrightCookie.
