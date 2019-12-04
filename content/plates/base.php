<!DOCTYPE html>
<?php $this->insert('partials/html'); ?>
<head>
<?php
// Find the course version.
$version = isset($course_data['configuration']['course_version']) ? $course_data['configuration']['course_version'] : null;
// If course version has not been set it should be a version 1.
if ($version === null) {
    $version = 1;
}
// Header.
$this->insert('layouts/' . $version . '/header');
?>
<title><?php if (isset($course_data['name'])) { echo $course_data['name'];} ?>: Digital Media eLearning Course</title>
</head>
<body id="course-controller" class="course" style="padding-top:0px; background-color: #f9f9f9;">
<?php
// Top Menu for getting back to Moodle.
$this->insert('partials/lmsmenu');
// Main Content.
$this->insert('layouts/' . $version . '/shell');
// Footer.
$this->insert('layouts/' . $version . '/footer');
?>
</body>
</html>
