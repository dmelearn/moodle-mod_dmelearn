<?php //Version 4 Shell ?>
<?php $this->insert('partials/prev_next'); //Add JS to go to prev/next page ?>

<div class="content-container">
    <div class="row course_content">
        <div class="course_body" id="maincontent">
            <?php
            // Navigation Hamburger
            if (isset($navigation)) {
                echo $navigation;
            } ?>
            <?php // Page content ?>
            <?php if (isset($page_data['data']['assessment_summary'])) : ?>
                <?php $this->insert('layouts/4/assessment_summary'); ?>
            <?php else if (isset($page_data['content'])) : ?>
                <?= $page_data['content'] ?>
            <?php endif; ?>
        </div>
    </div>
</div>
