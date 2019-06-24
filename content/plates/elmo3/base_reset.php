<!DOCTYPE html>
<html class="mdl-dmelearn" lang="en">
<head>
    <title><?= isset($page_title) ? $page_title : 'Page Title Missing' ?></title>
    <?php $this->insert('layouts/' . (isset($template_version) ? $template_version : '02' ) . '/header'); ?>
</head>
<body>
<a id="skip" href="#maincontent">Skip to content</a>

<?php
// Top Menu for getting back to Moodle
$this->insert('partials/lms_menu');
?>

<?php
// Main Content
?>
<div class="container text-center">
    <hr>
    <h1>Course Content Not Accessible</h1>
    <p>You must reset your course assessment to access course again.</p>
    <hr>
</div>

<?php
// Footer
$this->insert('layouts/' . (isset($template_version) ? $template_version : '02' ) . '/footer');
?>

</body>
</html>
