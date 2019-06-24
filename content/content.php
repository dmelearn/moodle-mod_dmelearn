<?php

namespace mod_dmelearn\content;

use mod_dmelearn\cache\Cache;

/**
 * Class Content
 *
 * Generates the navigation template for ELMO
 *
 * @package    mod_dmelearn\content
 * @author     AJ Dunn, Digital Media e-learning
 * @copyright  2019 Digital Media e-learning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class Content
{
    protected $module;
    protected $page;
    protected $directory;
    protected $plates;
    protected $client;

    /**
     * Constructor to set config variables.
     *
     * @param $module
     * @param $page
     */
    public function __construct($module, $page)
    {
        global $USER;

        $this->module = $module;
        $this->page = $page;
        $this->setPlatesDir();
        $this->setupPlates();

        // Setup Guzzle to the web services end point.
        $this->client = new \GuzzleHttp\Client();
    }

    private function setPlatesDir()
    {
        // Create new Plates Instance Loaded From Composer
        $classicSite = false;

        if ($classicSite) {
            $this->directory = __DIR__ . '/plates/elmo2';
        } else {
            $this->directory = __DIR__ . '/plates/elmo3';
        }
    }

    private function setupPlates()
    {
        $this->plates = new \League\Plates\Engine($this->directory);

        $this->plates->registerFunction('buildURI', static function ($mod, $module, $page, $id = '', $path = null) {
            if ($path !== null) {
                $paths = explode('/', $path);
                list($module, $page) = $paths;
            }
            $id = empty($id) ? '' : "#{$id}";
            return "?id={$mod}&module={$module}&page={$page}{$id}";
        });
    }

    public function loadContentPage($pageData, $tf_has_expired = false, $timeframemonths = false)
    {
        if ($tf_has_expired) {
            // Course that requires reset before it can be viewed.
            // Output Template
            $this->plates->addData($pageData);
            echo $this->plates->render('base_reset');
        } else {
            // Course that can be viewed.
            $this->plates->addData($pageData);
            echo $this->plates->render('base');
        }
    }

    public function loadAboutPage($pageData)
    {
        $this->plates->addData($pageData);
        echo $this->plates->render('about');
    }

    public function getCourseData($course)
    {
        global $CFG, $USER;

        $public_key = get_config('mod_dmelearn', 'elmopublickey');
        $secret_key = get_config('mod_dmelearn', 'elmosecretkey');
        $app_name = get_config('mod_dmelearn', 'elmoappname');

        $firstname = $USER->firstname;
        $lastname = $USER->lastname;
        $email = $USER->email;
        $payroll = $USER->idnumber;

        $preventearlierthanyear = 0; //TODO: HARD CODED
        $timeframemonths = 0; //TODO: HARD CODED

        // Make Requests to course first to get all user information/scripts/etc.
        // We have course = courseName, make a request for information on the course.
        try {
            // Check if this Moodle Activity's DM course has to be reset after a certain amount of months.
            $limitbymonths = '/';

            if ($preventearlierthanyear > 2000) {
                // A value of 0 or null means this is not used
                // Include the minimum year completion date in the API URL.
                $limitbymonths .= '0/' . $preventearlierthanyear;
            } else if ($timeframemonths >= 1) {
                // Include the amount of months in the API URL.
                $limitbymonths .= $timeframemonths;
            }

            $request = course_request(
                $this->client,
                API_URL . API_COURSES . $course . $limitbymonths,
                make_header($public_key, $app_name, $firstname, $lastname, $email, $payroll, $secret_key)
            );
            $course_request = $request->json();

            return $course_request;

        } catch (GuzzleHttp\Exception\ClientException $e) {
            // Check if we are unauthorised 'HTTP Error 401'.
            if ($e->getResponse()->getStatusCode() == '401') {
                // This Moodle is not authorised to access this course.
                $unauthorised = true;
                include_once 'include/noAccess.php';
                exit();
            } else {
                // We are authorised but another issue has occurred.
                // Dump Guzzle exception message if debug is enabled in Moodle.
                if (isset($CFG->debug) && $CFG->debug !== DEBUG_NONE) {
                    echo $e->getMessage();
                }
                // Throw Moodle Exception.
                throw new moodle_exception('course_client_exception', 'dmelearn');
            }
        } catch (GuzzleHttp\Exception\RequestException $e) {
            if (isset($CFG->debug) && $CFG->debug !== DEBUG_NONE) {
                echo $e->getMessage();
            }
            // Throw Moodle Exception.
            throw new moodle_exception('course_request_exception', 'dmelearn');
        }
    }

    public function getPageData($course, $module, $page, $foundFirstPage)
    {
        global $CFG, $USER;

        $public_key = get_config('mod_dmelearn', 'elmopublickey');
        $secret_key = get_config('mod_dmelearn', 'elmosecretkey');
        $app_name = get_config('mod_dmelearn', 'elmoappname');

        $first_name = $USER->firstname;
        $last_name = $USER->lastname;
        $email = $USER->email;
        $payroll = $USER->idnumber;

        try {
            // If the page is already cached.
            $cached = Cache::retrieve("{$course}_{$module}_{$page}");

            if (!$cached) {
                // Make a new request.
                $request = course_request(
                    $this->client,
                    API_URL . API_COURSES . $course . '/' . API_MODULES . $module . '/' . API_PAGES . $page,
                    make_header($public_key, $app_name, $first_name, $last_name, $email, $payroll, $secret_key)
                );
                $page_request = $request->json();
                // Attempt to cache the page.
                if (isset($page_request['content'])) {
                    Cache::caching("{$course}_{$module}_{$page}", $page_request['content']);
                }
            } else {
                $page_request['content'] = $cached;
            }

            return $page_request;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() == '404') {
                // 404 - Page not found on the API providers site.
                // We need to get the FIRST module and page.
                $module = $foundFirstPage['module'];
                $page = $foundFirstPage['page'];

                $elearnid = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);
                $lms_content_url = "{$CFG->wwwroot}/mod/dmelearn/content/?id={$elearnid}";

                if (isset($page) && $page != "" && isset($module) && $module != "") {
                    // Take user to a working page.
                    echo '<script>window.location.href="' . $lms_content_url . '&module=' . $module . '&page=' . $page . '";</script>';
                } else {
                    throw new moodle_exception('pagenotfound', 'dmelearn');
                }
                exit();
            } elseif ($e->getResponse()->getStatusCode() == '400') {
                // This will happen when no page is specified in the request URL.
                // lms_settings.php will redirect the users to the last saved page.
                if (isset($CFG->debug) && $CFG->debug !== DEBUG_NONE) {
                    echo 'Note: Moodle debugging is enabled.<br>';
                }
                echo 'Redirecting ...';
            } else {
                // Dump Guzzle exception message if debug is enabled in moodle.
                if (isset($CFG->debug) && $CFG->debug !== DEBUG_NONE) {
                    echo $e->getMessage();
                }
                throw new moodle_exception('page_client_exception', 'dmelearn');
            }
        }
    }
}
