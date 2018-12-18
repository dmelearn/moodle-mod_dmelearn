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
 * Basic Set of Constants
 *
 * @package    mod_dmelearn
 * @author     Chris Barton, AJ Dunn
 * @copyright  2015 Chris Barton, Digital Media e-learning
 * @version    1.1.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

/**
 * BASE URL to the ELMO Application
 */
define('ELMO_WEB_BASE_URL', $ELMO_ENV . '');
/**
 * Location of BASE ASSETS
 */
define('ELMO_WEB_BASE_ASSETS', $ELMO_ENV . 'assets/');
/**
 * Location of BASE CSS
 */
define('ELMO_WEB_BASE_CSS', $ELMO_ENV . 'css/');
/**
 * Location of BASE JAVASCRIPT
 */
define('ELMO_WEB_BASE_JAVASCRIPT', $ELMO_ENV . 'js/');
/**
 * Location of BASE IMAGES
 */
define('ELMO_WEB_BASE_IMAGES', $ELMO_ENV . 'images/');
/**
 * Location of BASE COURSES folder
 */
define('ELMO_WEB_BASE_COURSES', $ELMO_ENV . 'courses/');
/**
 * BASE API URI
 */
define('API_URL', $ELMO_ENV . 'api/');
/**
 * URI COURSE(S)
 */
define('API_COURSES', 'courses/');
/**
 * URI MODULE(S)
 */
define('API_MODULES', 'modules/');
/**
 * URI PAGE(S)
 */
define('API_PAGES', 'pages/');
/**
 * Validate Question
 */
define('API_VALIDATE', 'validate_question/');
/**
 * Key courses
 */
define('API_KEY_COURSES', 'keys/');
/**
 * Reset
 */
define('API_RESET', 'reset/');
/**
 * Loading FORM INTERACTIVE ACTIVITY STORAGE
 */
define('API_LOAD_ACTIVITY_STORAGE', 'api_form_interactive_load/');
/**
 * Saving to FORM INTERACTIVE ACTIVITY STORAGE
 */
define('API_SET_ACTIVITY_STORAGE', 'api_form_interactive_set/');

/**
 * Array of values to pass to the plates template.
 */
$frontEndConstants = array(
    'elmo_env' => $ELMO_ENV,
    'base_url' => ELMO_WEB_BASE_URL,
    'base_assets' => ELMO_WEB_BASE_ASSETS,
    'base_css' => ELMO_WEB_BASE_CSS,
    'base_js' => ELMO_WEB_BASE_JAVASCRIPT,
    'base_img' => ELMO_WEB_BASE_IMAGES,
    'base_courses' => ELMO_WEB_BASE_COURSES
);
