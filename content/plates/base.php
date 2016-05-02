<!DOCTYPE html>
<?php $this->insert('partials/html'); ?>
<head>
<?php
// Find the course version
$version = $course_data['configuration']['course_version'];
// If course version has not been set it should be a version 1
if (!isset($version) || $version === null) {
    $version = 1;
}
// Header
$this->insert('layouts/' . (int)$version . '/header');
?>
<title><?php if (isset($course_data['name'])) { echo $course_data['name'];} ?>: Digital Media eLearning Course</title>
</head>
<body style="padding-top:0px; background-color: #f9f9f9;" id="course-controller">
<?php
// Top Menu for getting back to Moodle
$this->insert('partials/lmsmenu');
// Main Content
$this->insert('layouts/' . (int)$version . '/shell');
// Footer
$this->insert('layouts/' . (int)$version . '/footer');
?>
</body>
</html>