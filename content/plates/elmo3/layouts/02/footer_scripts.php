<?php //Course Footer Scripts V02 ?>
<script>
  // Adjust the height of the content before the content is loaded
  contentMinHeight();
  // Adjust
  $(document).ready(function() {
    contentMinHeight();
  });
</script>

<?php //Jquery-UI ?>
<script src="<?= $constants['base_libs'] ?>/jquery-ui/js/jquery-ui.min.js">"></script>
<script src="<?= $constants['base_libs'] ?>/jquery/1/jquery.ui.touch-punch.min.js"></script>


<?php //Bootstrap 3 JS  //TODO: UPDATE THIS!! ?>
<script src="js/bootstrap.min.js"></script>

<?php //Audio JS ?>
<script src="<?= $constants['base_libs'] ?>audiojs/audiojs/audio.min.js"></script>

<?php //Course script ?>
<script src="<?= $course_constants['course_js'] ?>script.min.js"></script>
<?php //Other Course scripts ?>
<?php foreach ((array)$course_data['course_js'] as $js) : ?>
<script src="<?= $js ?>"></script>
<?php endforeach; ?>
