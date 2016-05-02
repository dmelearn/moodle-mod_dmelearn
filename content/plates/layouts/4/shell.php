<?php //Version 4 Shell ?>
<?php $this->insert('partials/prev_next'); //Add JS to go to prev/next page ?>

<div class="content-container">
    <div class="row course_content">
        <div class="course_body" id="maincontent">
            <?php if (isset($navigation)) { echo $navigation; } //Navigation Hamburger ?>

            <?php //TODO: Course Reset ?>

            <?php //Page content
            if (isset($page_data['data']['assessment_summary'])): ?>
                <?php $this->insert('partials/assessment_summary'); ?>
            <?php elseif (isset($page_data['content'])): ?>
                <?=$page_data['content']?>
            <?php endif; ?>
        </div>
    </div>
</div>
