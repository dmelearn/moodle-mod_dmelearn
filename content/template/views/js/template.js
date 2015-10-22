// retina.js
//Modernizr.load({
//    test: Modernizr.localstorage,
//    yep: '{{ constants.base_js }}retina.min.js'
//});
//Rewire and Remap
(function(window, document, undefined) {
    var r = window.routes; // Reference routes.
    r.base = '{{ constants.base_url }}';
    r.course.path = r.base + 'courses/{{ coursepath }}/';
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
            } else if (before_url.indexOf("{{ constants.elmo_env }}") === 0){ // Brightcookie script.
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
    var reset_button = $('.reset_button');
    var pos = this;
    var reset_course = function() {
        reset_button.on('click', function() {
            reset_button.unbind('click');
            // AJAX REQUEST -send the course_path and user_id back to ELMO to reset it in the database.
            var request = $.ajax({
                type: 'POST',
                url: 'elmo_ajax_ws_reset.php',
                data: {course_path: "{{ coursepath }}", user_id: "{{ page_data.data.cert_data.user_id }}"},
                success: function (data, status) {
                    // If data returned is true refresh the current page.
                    if (data == 1) {
                        reset_button.parents('.modal').modal('hide');
                        window.location.href = '{{ content_url }}';
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