<?php // Course shell version 2.1. ?>
<a href="#maincontent" id="skip" class="text-primary">Skip to content</a>

<div class="course-head">
    <div class="container">
        <div class="course-head__inner container">
            <h2><?= $course_data['name'] ?></h2>
        </div>
    </div>
</div>

<div class="course__content clearfix">
    <?php if ($navigation) : ?>
        <script>
          hideMenu();
          // Do side menu check.
          $(function() {
            checkMenuIcon();
          });

          // Check again when the window is resized.
          $(window).resize(function() {
            hideMenu();
          });

          // Resize the course content so the footer is below the sidebar always.
          function contentMinHeight() {
            var sidebar = $('.course__sidebar');
            var courseBody = $('.course__content__wrapper');

            if (sidebar.innerHeight() >= courseBody.innerHeight()) {
              courseBody.css({minHeight: sidebar.innerHeight()});
            }
          }

          // Checks if the side menu needs to be open or closed.
          function hideMenu() {
            var viewportWidth = $(window).width();
            var openWindow = localStorage.getItem('dmSidebarOpen');
            // If the local storage is set to close then keep it closed.
            if (openWindow == 'false') {
              $(".course__content").addClass("closed-menu");
              $('#nav-box ul').removeClass('open');
            } else {
              if (viewportWidth < 990) {
                $(".course__content").addClass("closed-menu");
                $('#nav-box ul').removeClass('open');
                localStorage.setItem('dmSidebarOpen', false);
              } else {
                $(".course__content").removeClass("closed-menu");
                $('#nav-box ul').addClass('open');
                localStorage.setItem('dmSidebarOpen', true);
              }
            }
          }

          // Check which icon should be shown on the menu.
          function checkMenuIcon() {
            if (localStorage.getItem('dmSidebarOpen') == 'false' || $(window).width() < 990) {
              $('#nav-box ul').removeClass('open');
            } else {
              $('#nav-box ul').addClass('open');
            }
          }

          // This function is called when the close menu button is clicked.
          function smallScreen() {
            $('.course__content').toggleClass('closed-menu')
            $('#nav-box ul').toggleClass('open');

            if ($('.course__content').hasClass('closed-menu')) {
              localStorage.setItem('dmSidebarOpen', false);
            } else {
              localStorage.setItem('dmSidebarOpen', true);
            }
          }

        </script>
        <div class="course__sidebar__bg"></div>
        <aside class="course__sidebar">
            <div class="course__sidebar__menu" onclick="smallScreen()">
                <div class="nav-box">
                    <div id="nav-box">
                        <ul>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
                <div class="hoverText hoverTextOpen">show menu</div>
                <div class="hoverText hoverTextClose">hide menu</div>
            </div>
            <?php $this->insert('layouts/2.1/prev_next'); ?>
            <nav class="course_nav">
                <?php echo $navigation; ?>
            </nav>
        </aside>
    <?php endif; ?>

    <?php // Page content. ?>
    <div class="course__content__wrapper clearfix">
        <?php // If the user has completed the course the reset and certificate buttons will appear. ?>
        <?php if ($page_data['data']['cert_data']['has_certificate']) : ?>
            <div class="alert alert-info clearfix row-fluid reset_and_cert_buttons">
                <p class="pull-left"><strong>Well Done!</strong> You have completed the assessment for this course.</p>
                <a class="btn pull-right btn-success"
                   href="<?= $constants['base_url'] ?>api/cert/download/<?= $page_data['data']['cert_data']['course_path'] ?>/<?= $page_data['data']['cert_data']['user_id'] ?>/<?= $page_data['data']['cert_data']['assessment_id'] ?>">Download
                    Certificate (PDF)</a>
                <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target=".reset_modal">Reset Course</button>
            </div>
            <?php // Modal. ?>
            <div class="modal fade reset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h3 id="myModalLabel">Are you sure you want to reset this course?</h3>
                        </div>
                        <div class="modal-body">
                            <p>All of your assessments for this course will be reset so you can re-complete it again from the beginning.</p>
                            <p><strong>You will not be able access previous certificates for this course once it has been reset.</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                            <button class="btn btn-danger reset_button" href="reset_course.php">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div id="maincontent" class="course-body clearfix">
            <?php if (isset($page_data['data']['assessment_summary'])) : ?>
                <?php $this->insert('layouts/2.1/assessment_summary'); ?>
            <?php elseif (isset($page_data['content'])) : ?>
                <?= $page_data['content'] ?>
            <?php endif; ?>
            <?php //Next and back buttons at bottom of screen ?>
            <section class="clearfix">
                <div class="bottom-pn-box">
                    <div class="col-md-4 col-sm-8 col-xs-12 bottom-pn pull-right">
                        <?php $this->insert('layouts/2.1/prev_next'); ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
      // Adjust the height of the content before the content is loaded
      contentMinHeight();
      // Adjust
      $(document).ready(function() {
        contentMinHeight();
      });
    </script>

</div>

<?php if (isset($course_data['glossary']['glossary_items']) && $course_data['glossary']) : ?>
    <?php $this->insert('layouts/2.1/glossary'); ?>
<?php endif; ?>
