<?php //Course Utils ?>
<script>
  var course_util = {};
  course_util.assessmentID <?= (isset($course_data['user']['assessment_id']) && $course_data['user']['assessment_id'] ? '=' . $course_data['user']['assessment_id'] : '') ?>;
  course_util.courseID = <?= isset($course_id) ? $course_id : null ?>;
  course_util.productID = <?= isset($course_id) ? $course_id : null ?>;

  // @todo: update this to be course directory
  course_util.coursePath = '<?= isset($course_data['directory']) ? $course_data['directory'] : null ?>';
  course_util.path = '<?= isset($course_data['course_path']) ? $course_data['course_path'] : null ?>';

  course_util.coursePage = '<?= $course_data['user']['last_visited']['page'] ?? null ?>';
  course_util.courseModule = '<?= $course_data['user']['last_visited']['module'] ?? null ?>';

  function getE3SubDir() {
    return '<?= isset($course_data['sub_directory']) ? $course_data['sub_directory'] : '/' ?>';
  }

  function getE3InProd() {
    return 'true';
  }

  function getE3CourseDir() {
    return '<?= isset($coursesDir) ? $coursesDir : 'courses/'?>';
  }
</script>

<?php //Routes ?>
<script src="<?= $constants['base_js'] ?>util/routes.min.js"></script>
<script src="<?= $constants['base_js'] ?>util/util.min.js"></script>

<?php
//Rewire, remap, retina.js, reset btn
$this->insert('partials/template_min_js');
?>

<?php
//Dependency Scripts
if (isset($course_data['configuration']['dependency_scripts'])) {
    foreach ($course_data['configuration']['dependency_scripts'] as $depScript) {
        echo '<script src="' . $constants['base_js'] . $depScript['path'] . '/' . $depScript['script'] . '.min.js"></script>';
    }
}
//Base Scripts
if (isset($course_data['configuration']['base_scripts'])) {
    foreach ($course_data['configuration']['base_scripts'] as $baseScript) {
        echo '<script src="' . $constants['base_js'] . $baseScript['path'] . '/' . $baseScript['script'] . '.min.js"></script>';
    }
}
//Page Scripts
if (isset($course_data['configuration']['page_scripts'])) {
    foreach ($course_data['configuration']['page_scripts'] as $pageScript) {
        if ($module === $pageScript['module'] && $page === $pageScript['page']) {
            echo '<script src="' . $course_constants['course_js'] . $pageScript['script'] . '.min.js"></script>';
        }
    }
}
?>
