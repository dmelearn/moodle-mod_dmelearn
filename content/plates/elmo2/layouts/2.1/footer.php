<?php //Course Footer ?>
<div class="footer">
    <div class="container">
        <p class="version-stamp">
            <span class="footer-text">
            <picture class='dm-logo'>
                <source type="image/svg+xml" srcset="<?= $constants['base_url'] ?>assets/images/dmlogo-footer.svg"
                        width="160" height="41" data-no-retina>
                <img src="<?= $constants['base_url'] ?>assets/images/dmlogo-footer.png"
                     alt="Digital Media"
                     width="160"
                     height="41"
                     data-no-retina>
            </picture>
                </span>
            <span class="sep">|</span>
            <span class="footer-text">Brought to you by <?= $constants['brand_name'] ?></span>
            <span class="sep">|</span>
            <span class="footer-text">Copyright &copy; <?= date('Y'); ?>
            </span>
            <span class="sep">|</span>
            <span class="footer-text">
            <picture class='dm-logo'>
                <source type="image/svg+xml" srcset="<?= $constants['base_url'] ?>assets/images/gov-logo.svg"
                        width="230" height="50" data-no-retina>
                <img src="<?= $constants['base_url'] ?>'assets/images/gov-logo.png"
                     alt="SA Health Government of South Australia"
                     width="230"
                     height="50"
                     data-no-retina>
            </picture>
            </span>
        </p>
    </div>
</div>

<?php //IE 8 Support ?>
<!--[if lt IE 9]>
<script src="js/html5shiv/html5shiv.min.js"></script>
<script src="js/respond/respond.min.js"></script>
<![endif]-->
<?php //Jquery-UI ?>
<script src="<?= $constants['base_js'] ?>jquery-ui.min.js"></script>
<script src="<?= $constants['base_js'] ?>jquery.ui.touch-punch.min.js"></script>
<?php //Bootstrap 3 JS ?>
<script src="js/bootstrap.min.js"></script>
<?php //Audio JS ?>
<script src="<?= $constants['base_url'] ?>plugins/audiojs/audiojs/audio.min.js"></script>

<script>
  var course_util = {};
  course_util.assessmentID <?= (isset($course_data['user']['assessment_id']) && $course_data['user']['assessment_id'] ? '=' . $course_data['user']['assessment_id'] : '') ?>;
  course_util.courseID = <?= $course_id ?? null ?>;
  course_util.productID = <?= $course_id ?? null ?>;

  // @todo: update this to be course directory
  course_util.coursePath = '<?= $course_data['directory'] ?? null ?>';
  course_util.path = '<?= $course_data['course_path'] ?? null ?>';

  course_util.coursePage = '<?= $course_data['user']['last_visited']['page'] ?? null ?>';
  course_util.courseModule = '<?= $course_data['user']['last_visited']['module'] ?? null ?>';

  function getE3SubDir() {
    return '<?= $course_data['sub_directory'] ?? '/' ?>';
  }

  function getE3InProd() {
    return <?= $inProduction ?? 'true' ?>;
  }

  function getE3CourseDir() {
    return '<?= isset($coursesDir) ? $coursesDir : 'courses/'?>';
  }
</script>
