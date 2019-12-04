<?php //Page to show if course must be reset. ?>
<!DOCTYPE html>
<?php
// Find the course version.
$version = $course_data['configuration']['course_version'];

if (!isset($version) || $version === null) {
    $version = 1;
}

$this->insert('partials/html');
?>
<head>
    <?php // set vars.
    $coursepath = $page_data['data']['cert_data']['course_path'];
    ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="utf-8">
    <?php // CSS. ?>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $constants['base_css']; ?>main_v2.css">
    <?php // jQuery UI Bootstrap Theme. ?>
    <link rel="stylesheet" href="<?=$constants['base_css']?>jquery-ui-for-bootstrap.css">
    <?php // Font Awesome 3.2.1. ?>
    <link rel="stylesheet" type="text/css" href="css/font-awesome-3.min.css">
    <?php // Font Awesome 4.2.0. ?>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <?php
    // Course CSS.
    foreach ($course_data['course_css'] as $css) {
        echo '<link rel="stylesheet" type="text/css" href="' . $css . '">';
    }
    ?>
    <?php // jQuery. ?>
    <script src="<?=$constants['base_js']?>jquery.min.js"></script>
    <?php // Utility Scripts. ?>
    <script src="<?=$constants['base_js']?>modernizr.custom.min.js"></script>
    <?php // Routes. ?>
    <script src="<?=$constants['base_js']?>util/routes.min.js"></script>
    <script src="<?=$constants['base_js']?>util/util.min.js"></script>
    <?php //Course scripts. ?>
    <script src="<?=$course_constants['course_js']?>script.min.js"></script>
    <?php
    // Rewire, remap, retina.js, reset btn.
    $this->insert('partials/template_min_js');

    // Dependency Scripts.
    if (isset($course_data['configuration']['dependancy_scripts'])) {
        foreach ($course_data['configuration']['dependancy_scripts'] as $dScript) {
            echo '<script src="' . $constants['base_js'] . $dScript['path'] . '/' . $dScript['script'] . '.min.js"></script>';
        }
    }

    // Base Scripts.
    if (isset($course_data['configuration']['base_scripts'])) {
        foreach ($course_data['configuration']['base_scripts'] as $bScript) {
            echo '<script src="' . $constants['base_js'] . $bScript['path'] . '/' . $bScript['script'] . '.min.js"></script>';

        }
    }

    // Page Scripts.
    if (isset($course_data['configuration']['page_scripts'])) {
        foreach ($course_data['configuration']['page_scripts'] as $pScript) {
            if ($module === $pScript['module'] && $page === $pScript['page']) {
                echo '<script src="' . $course_constants['course_js'] . $pScript['script'] . '.min.js"></script>';
            }
        }
    }
    // Course JS.
    ?>
    <script src="js/dmelearn.min.js"></script>
</head>
<body style="padding-top:0px; background-color: #f9f9f9;" id="course-controller" class="mdl-dmelearn-forcereset">
<?php
// LMS menu.
$this->insert('partials/lmsmenu');
// Shell.
$this->insert('partials/requires_reset');
// Footer V2.
$this->insert('layouts/2/footer');
?>
</body>
</html>