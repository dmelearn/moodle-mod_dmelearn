<?php //Header V02 ?>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="<?= $constants['base_url']?>" crossorigin>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Digital Media produced eLearning course">
<meta name="author" content="WCHN CET Digital Media">
<meta name="format-detection" content="telephone=no">

<?php //CSS Google Fonts ?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">

<?php
//Course CSS
foreach ($course_data['course_css'] as $css) {
    echo '<link rel="stylesheet" type="text/css" href="' . $css . '">';
}
?>

<?php //jQuery ?>
<script src="<?= $constants['base_libs'] ?>jquery/2/jquery.min.js"></script>

<?php $this->insert('layouts/02/header_scripts'); ?>

<?php //CSS Font Awesome 4.3.0 ?>
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
<?php //DM Icons ?>
<link rel="stylesheet" href="dmicons/css/dmicons.min.css">
<script src="js/dmelearn.min.js"></script>

<?php //Fix Firefox selections ?>
<style>.assess-quest select {color: black;}</style>

<?php if (isset($course_data['course_favicon'])) : ?>
<link rel="shortcut icon" href="<?= $course_data['course_favicon'] ?>">
<?php endif; ?>

