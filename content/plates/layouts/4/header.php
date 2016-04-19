<?php //Version 4 Header ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="author" content="CET Digital Media">
<meta name="description" content="DM eLearning Course">
<?php //Google Fonts and Material Icons ?>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700|Material+Icons' rel='stylesheet' type='text/css'>
<?php //CSS ?>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"><?php //Local hosted copy of bootstrap 3 ?>
<link rel="stylesheet" type="text/css" href="<?=$constants['base_css']?>main_v2.css"><?php //Main styling from dm server ?>
<link rel="stylesheet" href="<?=$constants['base_css']?>jquery-ui-for-bootstrap.css"><?php //Jquery UI theme from dm server ?>
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css"><?php //Font Awesome 4.2.0 ?>
<?php
//Load Course CSS
foreach ($course_data['course_css'] as $css) {
    echo '<link rel="stylesheet" type="text/css" href="' . $css . '">';
}
?>
<?php //jQuery ?>
<script src="<?=$constants['base_js']?>jquery.min.js"></script><?php //Jquery from dm server ?>
<script src="<?=$constants['base_js']?>modernizr.custom.min.js"></script><?php //Modernizr from dm server ?>
<?php //Routes ?>
<script src="<?=$constants['base_js']?>util/routes.min.js"></script>
<script src="<?=$constants['base_js']?>util/util.min.js"></script>
<?php //Course scripts ?>
<script src="<?=$course_constants['course_js']?>script.min.js"></script>
<?php
//Rewire, remap, retina.js, reset btn
$this->insert('partials/template_min_js');

//Dependency Scripts
if (isset($course_data['configuration']['dependancy_scripts'])) {
    foreach ($course_data['configuration']['dependancy_scripts'] as $dScript) {
        echo '<script src="' . $constants['base_js'] . $dScript['path'] . '/' . $dScript['script'] . '.min.js"></script>';
    }
}

//Base Scripts
if (isset($course_data['configuration']['base_scripts'])) {
    foreach ($course_data['configuration']['base_scripts'] as $bScript) {
        echo '<script src="' . $constants['base_js'] . $bScript['path'] . '/' . $bScript['script'] . '.min.js"></script>';
    }
}

//Page Scripts
if (isset($course_data['configuration']['page_scripts'])) {
    foreach ($course_data['configuration']['page_scripts'] as $pScript) {
        if ($module === $pScript['module'] && $page === $pScript['page']) {
            echo '<script src="' . $course_constants['course_js'] . $pScript['script'] . '.min.js"></script>';
        }
    }
}
?>
<script src="js/dmelearn.min.js"></script><?php //Plugin specific Course JS ?>