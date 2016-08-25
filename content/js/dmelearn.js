// DOM ready
$(function() {
  // Select Box Fix For IE.
  if (typeof Modernizr == 'object') {
    if (!Modernizr.borderradius) { // using borderradius as a test
      var el;
      $('select')
        .each(function() {
          el = $(this);
          el.data('origWidth', el.outerWidth()); // IE 8 can haz padding
        })
        .mousedown(function() {
          $(this).css('width', 'auto');
        })
        .focus(function() {
          $(this).css('width', 'auto');
        })
        .bind('blur', function() {
          el = $(this);
          el.css('width', el.data('origWidth'));
        });
    }
  }
  // Bootstrap tooltips.
  $('a').tooltip(); // DEPRECIATED - use rel="tooltip" to call your tooltips instead.
  $('a[rel=tooltip]').tooltip();
  $('.popover-owner').popover(); // DEPRECIATED - use rel="popover" on the popover button/link to call your popup.
  $('a[rel=popover]')
    .popover()
    .click(function(e) {
      e.preventDefault();
    });
  // Remove popover by clicking anywhere.
  $('body').on('click', function(e) {
    $('a[rel=popover]').each(function() {
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

    if (second_tier.hasClass('open')) {
      // close
      second_tier.hide(400);
      second_tier.removeClass('open');
      i_tag.attr('class', 'icon-chevron-down');
    }
    else {
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
  // Collapsible elements.
  // Swap arrow sprite on hide/show.
  $('.accordion').on('show hide', function(n) {
    $(n.target).siblings('.accordion-heading').find('.accordion-toggle i').toggleClass('icon-chevron-up icon-chevron-down');
  });
  $('.panel-group').on('show.bs.collapse hide.bs.collapse', function(n) {
    $(n.target).siblings('.panel-heading').find('i').toggleClass('fa-angle-up fa-angle-down');
    $(n.target).siblings('.panel-heading').find('i').toggleClass('dm-arrow-down dm-arrow-up');
  });
  //Changes the colours of other accordion items when one is clicked (but not closed).
  var active_colour = null;
  var select_accordion = $('.course_body').find('.accordion-group');
  select_accordion.on('click', function(event) {
    var collapsed_colour = $('.icon-chevron-down').parents('.accordion-toggle').css('background-color');
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

  // Handle carousel count (out of).
  var carousel = $('.carousel');
  carousel.each(function() {
    var currentCarousel = $(this);
    var countBox = currentCarousel.find('.carousel-count');
    if (countBox.length > 0) {
      var totalDiv = countBox.find('.carousel-total');
      var totalCount = currentCarousel.find('.item').length;
      totalDiv.html(totalCount);
      currentCarousel.on('slide.bs.carousel', function(e) {
        var carouselData = $(this).data('bs.carousel');
        var currentCount = carouselData.getItemIndex($(e.relatedTarget)) + 1;
        var currentDiv = countBox.find('.carousel-current');
        currentDiv.html(currentCount);
      });
    }
  });
});