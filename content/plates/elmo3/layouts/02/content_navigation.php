<script>
  hideMenu();
  // Do side menu check
  $(function() {
    checkMenuIcon();
  });

  // Check again when the window is resized
  $(window).resize(function() {
    hideMenu();
  });

  // Resize the course content so the footer is below the sidebar always
  function contentMinHeight() {
    var sidebar = $('.course__sidebar');
    var courseBody = $('.course__content__wrapper');

    if (sidebar.innerHeight() >= courseBody.innerHeight()) {
      courseBody.css({minHeight: sidebar.innerHeight()});
    }
  }

  // Checks if the side menu needs to be open or closed
  function hideMenu() {
    var viewportWidth = $(window).width();
    var openWindow = localStorage.getItem('dmSidebarOpen');
    // If the local storage is set to close then keep it closed
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

  // Check which icon should be shown on the menu
  function checkMenuIcon() {
    if (localStorage.getItem('dmSidebarOpen') == 'false' || $(window).width() < 990) {
      $('#nav-box ul').removeClass('open');
    } else {
      $('#nav-box ul').addClass('open');
    }
  }

  // This function is called when the close menu button is clicked
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
    <?php $this->insert('layouts/02/prev_next'); ?>
    <nav class="course_nav">
        <?= $html['navigation'] ?>
    </nav>
</aside>
