<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7 iam-ie6" lang="en"> <![endif]-->
<!--[if IE 7]>   <html class="no-js lt-ie10 lt-ie9 lt-ie8 iam-ie7" lang="en"> <![endif]-->
<!--[if IE 8]>   <html class="no-js lt-ie10 lt-ie9 iam-ie8" lang="en"> <![endif]-->
<!--[if IE 9]>   <html class="no-js lt-ie10 iam-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="utf-8">
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Bitter|Roboto+Slab' rel='stylesheet' type='text/css'>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo ELMO_WEB_BASE_CSS . "main.css" ?>" />
    <!-- jQuery UI Bootstrap Theme -->
    <link rel="stylesheet" href="<?php echo ELMO_WEB_BASE_CSS . "jquery-ui-for-bootstrap.css" ?>" />
    <!-- Font Awesome 3.2.1  -->
    <link rel="stylesheet" type="text/css" href="css/font-awesome-3.min.css" />
    <!-- Font Awesome 4.2.0  NOT USED YET -->
    <!-- <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" /> -->
    <!-- Course CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo ELMO_WEB_BASE_CSS . "course_boilerplate.css" ?>" />
    <?php foreach ($course_request['course_css'] as $course_css): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $course_css; ?>">
    <?php endforeach; ?>
    <!-- JQUERY -->
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "jquery.min.js"; ?>"></script>
    <!-- Utility Scripts -->
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "modernizr.custom.min.js"; ?>"></script>
    <!-- ROUTES -->
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "util/routes.min.js"; ?>"></script>
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "util/util.min.js"; ?>"></script>
    <!-- COURSE SCRIPT -->
    <script src="<?php echo ELMO_WEB_COURSE_JAVASCRIPT . "script.min.js"; ?>"></script>
         <!-- Retina.js -->
    <script>
    Modernizr.load({
        test: Modernizr.localstorage,
        yep: '<?php echo ELMO_WEB_BASE_JAVASCRIPT . 'retina.min.js' ?>'
    });
    </script>
   <!-- ROUTE REMAP -->
   <script>
        // Rewire and remap.
        (function(window, document, undefined) {
            var r = window.routes; // Reference routes.
            r.base = '<?php echo ELMO_WEB_BASE_URL; ?>';
            r.course.path = r.base + 'courses/<?php echo $course; ?>/';
            r.course.img = r.base + 'images/';
            r.course.resources = r.course.path + 'resources/';
            r.course.images = r.course.resources + 'images/';
        })(window);
        $(function() {
            var path = 'elmo_ajax_ws.php?request=';
            $.ajaxSetup({
                global: true,
                beforeSend: function(jqXHR, settings) {
                    // Current url.
                    var before_url = settings.url;
                    // Split the string at /client_api because we need the string that follows this text.
                    var matches = before_url.split("/client_api");
                    // If there is only one string left over then that "/client_api" string didn't exist.
                    if ($(matches).length === 1) {
                        matches = null;
                    }
                    // If there was a result.
                    if (matches !== null) {
                        // remap
                        settings.url = path + matches[1];
                    } else if (before_url.indexOf("<?php echo $ELMO_ENV; ?>") == 0){ // Brightcookie script.
                        // remap
                        settings.url = path + before_url;
                    }
                }
            });
            // Init plugins.
            if (typeof $.fn.elmo_multipleChoice !== null || 'undefined') {
                $('.question').elmo_multipleChoice({
                    imagePath: window.routes.img,
                    courseImages: window.routes.course.images
                });
            }
            // Finds the reset assessments button.
                var reset_button = $('#reset_modal').find('.reset_button');
                var pos = this;
                var reset_course = function() {

                    reset_button.on('click', function() {
                        reset_button.unbind('click');
                        // AJAX REQUEST -send the course_path and user_id back to ELMO to reset it in the database.
                        var request = $.ajax({
                            type: 'POST',
                            url: 'elmo_ajax_ws_reset.php',
                            data: {course_path: "<?php echo($page_request['data']['cert_data']['course_path']); ?>", user_id: "<?php echo($page_request['data']['cert_data']['user_id']); ?>"},
                            success: function (data, status) {
                                if (data == 1) {
                                    reset_button.parents('.modal').modal('hide');
                                    window.location.href = '<?php echo $lmscontenturl; ?>';
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
                }
                reset_course();
        });
    </script>
        <!-- DEPENDANCY SCRIPTS -->
        <?php if (isset($course_request['configuration']['dependancy_scripts'])): ?>
            <?php foreach ($course_request['configuration']['dependancy_scripts'] as $dependant_scripts) : ?>
                <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "{$dependant_scripts['path']}/{$dependant_scripts['script']}.js"; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- BASE SCRIPTS -->
        <?php foreach ($course_request['configuration']['base_scripts'] as $base_scripts): ?>
            <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "{$base_scripts['path']}/{$base_scripts['script']}.js"; ?>"></script>
        <?php endforeach; ?>
        <!-- PAGE SCRIPTS -->
        <?php if (isset($course_request['configuration']['page_scripts'])): ?>
            <?php foreach ($course_request['configuration']['page_scripts'] as $page_scripts): ?>
                <?php elmo_parse_config_page_scripts($module, $page, $page_scripts); ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <!-- COURSE JAVASCRIPT -->
        <script>
            // DOM ready
            $(function() {
                // Select Box Fix For IE.
                if (!Modernizr.borderradius) { // Using borderradius as a test.
                    var el;
                    $("select")
                            .each(function() {
                                el = $(this);
                                el.data("origWidth", el.outerWidth()) // IE 8 has padding.
                            })
                            .mouseenter(function() {
                                $(this).css("width", "auto");
                            })
                            .bind("blur change", function() {
                                el = $(this);
                                el.css("width", el.data("origWidth"));
                            });
                }
                // Bootstrap tooltips.
                $('a').tooltip(); // DEPRECIATED - use rel="tooltip" to call your tooltips instead.
                $('a[rel=tooltip]').tooltip();
                $('.popover-owner').popover(); // DEPRECIATED - use rel="popover" on the popover button/link to call your popup.
                $("a[rel=popover]")
                        .popover()
                        .click(function(e) {
                            e.preventDefault();
                        });
                // Remove popover by clicking anywhere.
                $('body').on('click', function(e) {
                    $("a[rel=popover]").each(function() {
                        // The 'is' for buttons that trigger popups.
                        // The 'has' for icons within a button that triggers a popup.
                        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                            $(this).popover('hide');
                        }
                    });
                });
                // accordion collapse bootstrap.
                $('#notification-popover').popover({
                    'placement': 'bottom',
                    'trigger': 'hover',
                    'html': true
                });
                $('.notification-assessmentcomplete').popover({
                    'placement': 'bottom',
                    'trigger': 'click',
                    'html': true,
                    'title': 'Well done!',
                    'content': 'You\'ve completed the assessment for this course.'
                });
                // Force external links to open in a new window.
                $('a').each(function() {
                    var a = new RegExp('/' + window.location.host + '/');
                    if (!a.test(this.href)) {
                        $(this).click(function(event) {
                            event.preventDefault();
                            event.stopPropagation();
                            window.open(this.href, '_blank');
                        });
                    }
                });
                // Assessment summary drop-down.
                var assessment_sum_parent = $('table.quiz_summary');
                var sum_a_tag_ = assessment_sum_parent.find('a.dropdown_summary');
                sum_a_tag_.on('click', function(event) {
                    event.preventDefault();
                    // Get closest
                    var i_tag = $(this).find('i');
                    var tr = $(this).parents('tr');
                    var second_tier = tr.next('tr.second-tier');

                    if (second_tier.hasClass('open'))
                    {
                        // close
                        second_tier.hide(400);
                        second_tier.removeClass('open');
                        i_tag.attr('class', 'icon-chevron-down');
                    }
                    else
                    {
                        // open
                        second_tier.show(400);
                        second_tier.addClass('open');
                        i_tag.attr('class', 'icon-chevron-up');
                    }
                });
                // The following code came from course.main.js.
                var nav = $('.course_nav'); // Navigation - click on module with multiple pages and it opens up.
                var nav_group = nav.find('.accordion-group');
                nav_group.each(function() {
                    if ($(this).find('ul').length > 0) {
                        var anch = $(this).children('ul').find('a');
                        var link = anch[0].href;
                    }
                    $(this).click(function() {
                        window.location.href = link;
                    });
                });
                // Collapsable elements.
                // Swap arrow sprite on hide/show.
                $('.accordion').on('show hide', function(n) {
                    $(n.target).siblings('.accordion-heading').find('.accordion-toggle i').toggleClass('icon-chevron-up icon-chevron-down');
                });
                //Changes the colours of other accordion items when one is clicked (but not closed).
                var active_colour = null;
                var select_accordion = $('.course_body').find('.accordion-group');
                select_accordion.on('click', function(event) {
                    var collapsed_colour = $('.icon-chevron-down').parents('.accordion-toggle').css("background-color");
                    if (active_colour == null) {
                        active_colour = $(this).css('background-color');
                    }
                    $(this).siblings('.accordion-group').find('.accordion-toggle').addClass('collapsed');
                    $(this).css({'background-color': active_colour});
                    $(this).siblings('.accordion-group').css({'background-color': collapsed_colour});
                });
                // Popover.
                $(document).click(function() {
                    // Removes popovers when clicking anywhere on the page.
                    // Close a popover on click of popover close button as well.
                    $('.popover-owner').popover('destroy');
                });
            });
        </script>
</head>
<body style="padding-top:0px; background-color: #f9f9f9; font-family:'Arial'" id="course-controller">
    <?php echo $lmsmenu; // brightcookie - LMS menu ?>
    <div class="course_head">
        <div class="course_head_inner">
            <h1><?php echo $course_request['name']; ?></h1>
        </div>
    </div>
    <div class="container content-container">
        <div class="row course_content">
            <?php if ($page_request['data']['cert_data']['has_certificate']): ?>
                    <div class="alert alert-info clearfix row-fliud reset_and_cert_buttons">
                        <p class='pull-left'><strong>Well Done! </strong> This course has been 100% completed.</p>
                        <a class='btn pull-right btn-success' href="<?php echo ELMO_WEB_BASE_URL . 'api/cert/download/' . $page_request['data']['cert_data']['course_path'] . '/' . $page_request['data']['cert_data']['user_id'] . '/' . $page_request['data']['cert_data']['assessment_id'] ?>">Download Certificate (PDF)</a>
                        <a class="btn pull-right btn-danger" href ='#reset_modal' data-toggle="modal">Reset Course</a>
                    </div>
                    <!-- Modal -->
                    <div id="reset_modal" class="modal hide fade" tabindex="-1" role"dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <?php endif; ?>
            <?php if (isset($navigation)): ?>
                <nav id="accordion_menu" class="course_nav accordion span3">
                    <!-- Next and Back Buttons inside navigation -->
                    <div class="nextback">
                        <?php if (isset($previous_url) && $previous_url): ?>
                            <!-- BrightCookie changed-->
                           <a href="<?php echo $lmscontenturl; ?>&module=<?php echo $previous_url['module']; ?>&page=<?php echo $previous_url['page']; ?>">
                                <img alt="back" src="<?php echo ELMO_WEB_BASE_IMAGES; ?>/btn-blk-left.png"/>
                            </a>
                        <?php endif; ?>

                        <?php if (isset($next_url) && $next_url): ?>
                        <!-- BrightCookie changed-->
                            <a href="<?php echo $lmscontenturl; ?>&module=<?php echo $next_url['module']; ?>&page=<?php echo $next_url['page']; ?> ">
                                <img alt="next" src="<?php echo ELMO_WEB_BASE_IMAGES; ?>/btn-blk-right.png"/>
                            </a>
                        <?php endif; ?>
                    </div>
                    <!-- NAVIGATION -->
                    <?php echo $navigation->make(); ?>
                </nav>
            <?php endif; ?>
            <div id="maincontent" class="span9 course_body">
                <?php if (isset($page_request['data']['assessment_summary'])) {
                include 'template/views/assessment_summary.php';
                }
                else if (isset($page_request['content'])) {
                    echo $page_request['content'];
                } ?>
            </div>
            <!-- NEXT AND BACK BUTTONS -->
            <div class="span9 offset3">
                <div class="nextback right">
                    <?php if ($previous_url): ?>
                        <!-- BrightCookie changed-->
                        <a class="prev" href="<?php echo $lmscontenturl; ?>&module=<?php echo $previous_url['module']; ?>&page=<?php echo $previous_url['page']; ?>">
                            <img alt="back" src="<?php echo ELMO_WEB_BASE_IMAGES; ?>/btn-blk-left.png"/>
                        </a>
                    <?php endif; ?>
                    <?php if ($next_url): ?>
                        <!-- BrightCookie changed-->
                        <a class="next" href="<?php echo $lmscontenturl; ?>&module=<?php echo $next_url['module']; ?>&page=<?php echo $next_url['page']; ?> ">
                            <img alt="next" src="<?php echo ELMO_WEB_BASE_IMAGES; ?>/btn-blk-right.png"/>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- END NEXT AND BACK BUTTONS -->
        </div>
    </div>
    <!-- JQUERY-UI -->
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "jquery-ui.min.js" ?>"></script>
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "jquery.ui.touch-punch.min.js" ?>"></script>
    <!-- BOOTSTRAP 2.3 JAVASCRIPT -->
    <script src="<?php echo ELMO_WEB_BASE_JAVASCRIPT . "bootstrap/bootstrap-all.min.js"; ?>"></script>
    <!-- Audio JS -->
    <script src="<?php echo ELMO_WEB_BASE_URL . "plugins/audiojs/audiojs/audio.min.js"; ?>"></script>
</body>
</html>