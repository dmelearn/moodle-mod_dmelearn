# moodle-mod_dmelearn
[![Code Climate](https://codeclimate.com/github/dmelearn/moodle-mod_dmelearn/badges/gpa.svg)](https://codeclimate.com/github/dmelearn/moodle-mod_dmelearn)
- Plugin to access/use Digital Media e-Learning course content as a Moodle Activity rather than users accessing the "DM e-learning" website directly.
- This plugin is only useful for Moodle Managers that have been granted API access to "Digital Media e-Learning" courses with valid application settings.
- Usage of this plugin creates an account for each user on DM e-learning to track course completion and provide certificates, completion of each course is also recorded within Moodle.

## Setup
### Add required third party components (Developers)
- Requires [Composer](https://getcomposer.org/), [Bower](http://bower.io/), [Node.js](https://nodejs.org/).
- Node packages can be installed using [NPM](https://www.npmjs.com/) or [Yarn](https://yarnpkg.com/).
This includes [Gulp](http://gulpjs.com/), [gulp-rename](https://www.npmjs.com/package/gulp-rename), [del](https://www.npmjs.com/package/del) and [gulp-uglify](https://www.npmjs.com/package/gulp-uglify).
- Open the `content` folder inside this plugin from the terminal/command line.
- Run `yarn install` to install node packages listed within `package.json`.
- Run `composer install` to install composer packages listed within `composer.json`.
- If preparing for deployment run `composer install --no-dev` instead.
- Run `bower install` to install bower packages listed within `bower.json`.
- Run `gulp` to run the default task within `gulpfile.js` this places the front end assets into the expected locations.

### Remove unused third party components files before deployment (Developers)
- Open the `content` folder inside this plugin from the terminal/command line.
- Run `gulp clean:vendor`, `gulp clean:git`, `gulp del:bower` and `gulp del:nm`.

### Installation / Usage
* Either install the plugin as a .zip file via Moodle Plugin installer page or ...
* Create a `dmelearn` folder inside `mod/` in the root directory of you Moodle install.
* Copy all plugin files and folders into `dmelearn`.
* After the plugin has been installed by a Moodle Administrator ...
* Go to Site Administration -> Plugins -> Activity modules -> Digital Media e-Learning.
* Enter all required configuration details for the mod_dmelearn plugin (Provided to DM partners only).
* `DM e-Learning` Activities will now be available to add to Moodle Courses.
* The plugin will use proxy setting from Site Administration -> Server -> HTTP -> Web proxy.
If you do not wish to use these proxy settings for the plugin please enter the API URL int the ``Proxy bypass hosts`` field on this page.

## Requirements
- PHP 5.4.0 or above with short echo tag support and the [cURL extension](http://php.net/manual/en/book.curl.php).
- Moodle 2.7, 2.8, 2.9, 3.x+
- https://docs.moodle.org/25/en/Installing_Moodle#Requirements

## Authors
- Digital Media e-Learning team.

## Acknowledgements
- This project contains code contributed by Chris Barton and BrightCookie.
