<!DOCTYPE html>
<?php $this->insert('partials/html'); ?>
<head>
<?php
// Find the course version
$version = $course_data['configuration']['course_version'];

if (!isset($version) || $version === null) {
    $version = 1;
}

$this->insert('layouts/' . (int)$version . '/header');
?>
<title>Digital Media eLearning Course</title>
</head>
<body style="padding-top:0px; background-color: #f9f9f9;" id="course-controller">
<?php
$this->insert('partials/lmsmenu');

$this->insert('layouts/' . (int)$version . '/shell');
$this->insert('layouts/' . (int)$version . '/footer');
?>
</body>
</html>