<?php //Shell V02 ?>

<a href="#maincontent" id="skip">Skip to content</a>

<div class="course-head">
    <div class="container">
        <div class="course-head__inner container">
            <h2><?= $course_data['name'] ?></h2>
        </div>
    </div>
</div>

<div class="course__content clearfix">
    <?php //Page Navigation ?>
    <?php
    if ($html['navigation']) {
        $this->insert('layouts/02/content_navigation');
    }
    ?>

    <?php //Page content ?>
    <div class="course__content__wrapper clearfix">

        <?php $this->insert('layouts/02/content_notifications'); ?>

        <div id="maincontent" class="course-body clearfix">

            <?php if (isset($page_data['data']['assessment_summary'])) : ?>
                <?php $this->insert('layouts/02/content_assessment_summary'); ?>
            <?php elseif (isset($page_data['content'])) : ?>
                <?php $this->insert('layouts/02/content_course'); ?>
            <?php endif; ?>

            <?php $this->insert('layouts/02/prev_next_bottom'); ?>

        </div>
    </div>
</div>
