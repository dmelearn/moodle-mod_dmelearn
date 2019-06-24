<?php
// JS Not Minified
// Setup Variables
$coursepath = $page_data['data']['course_data']['course_directory'] ?? '';
?>
<script>
  (function(window, document, undefined) {
    var r = window.routes; //Reference routes
    r.course = {};
    r.base = '<?= $constants['base_url'] ?>';
    r.course.path = r.base + '<?= $page_data['data']['course_data']['courses_dir'] ?>' + '<?= $coursepath ?>/';
    r.course.img = r.base + 'assets/img/';
    r.course.resources = r.course.path + 'resources/';
    r.course.images = r.course.resources + 'images/';
  })(window);

  $(function() {
    //Fix for IE8 and IE9 to allow form interactive ajax
    $.support.cors = true;
    //Path to redirect ajax requests to
    var path = 'elmo_ajax_ws.php?request=';
    $.ajaxSetup({
      global: true,
      beforeSend: function(jqXHR, settings) {
        //Current url.
        var before_url = settings.url;

        //Check if we are doing a form_interactive
        if (before_url.indexOf("/form_interactive/loadData/") > -1) {
          //For load data form interactive
          var matches = before_url.split("form_interactive/loadData/");
          var interactive = matches[1].split("?")[0];

          settings.url = path + "form_interactive_load/" + interactive + '/' + '<?= $coursepath ?>';
        }
        else if (before_url.indexOf("/form_interactive/checkData/") > -1) {
          //For setting data form interactive
          var matches = before_url.split("form_interactive/checkData/");
          var interactive = matches[1].split("?")[0];

          settings.url = path + "form_interactive_set/" + interactive + '/' + '<?= $coursepath ?>';
        }
        else if(before_url.indexOf("ajax/assessment/validateQuestion/") > -1){

          var matches = before_url.split("ajax/assessment/validateQuestion/");
          var questionID = matches[1];

          settings.url = path + 'validateQuestion/' + questionID;
        }
        else {

          if(settings.url === 'ajax/assessment/getQuestions'){
            settings.url = path + 'getQuestions';
          }


          //For Validate Question etc.
          //Split the string at /client_api because we need the string that follows this text
          //var matches = before_url.split("/client_api");
          ////If there is only one string left over then that "/client_api" string didn't exist
          //if ($(matches).length === 1) {
          //  matches = null;
          //}
          ////If there was a result.
          //if (matches !== null) {
          //  //Remap
          //  settings.url = path + matches[1];
          //} else if (before_url.indexOf("<?//=$constants['elmo_env']?>//") === 0) {
          //  //BC script. Remap
          //  settings.url = path + before_url;
          //}
        }
      }
    });

    //Init plugins
    if (typeof $.fn.elmo_multipleChoice !== 'undefined') {
      $('.question').elmo_multipleChoice({
        imagePath: window.routes.img,
        courseImages: window.routes.course.images
      });
    }

    //Finds the reset assessments button
    var reset_button = $('.reset_button');
    var pos = this;
    var reset_course = function() {
      reset_button.on('click', function() {
        reset_button.unbind('click');
        //Ajax Request send the course_path and user_id back to ELMO to reset it in the database
        var request = $.ajax({
          type: 'POST',
          url: 'elmo_ajax_ws_reset.php',
          data: {
            course_path: "<?=$coursepath?>",
            user_id: "<?=$page_data['data']['cert_data']['user_id']?>"
          },
          success: function(data, status) {
            //If data returned is true refresh the current page
            if (data == 1) {
              reset_button.parents('.modal').modal('hide');
              window.location.href = '<?= $content_url ?>';
              reset_course();
            }
            else {
              reset_button.parents('.modal').modal('hide');
              alert('Sorry something went wrong. This course assessment could not be reset.');
              reset_course();
            }
          }
        });
      });
    };

    reset_course();
  });
</script>