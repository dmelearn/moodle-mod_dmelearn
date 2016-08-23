<div class="assessment-summary">
    <div class="assessment-summary__head course-head">
        <h1>Assessment Summary</h1>
    </div>
    <div class="assessment-summary__container assessment-summary__container--top">
        <div class="assessment-summary__container__inner">
            <div class="assessment-summary__content">
                <div class="assessment-summary__heading">
                    <h1>ASSESSMENT SUMMARY</h1>
                </div>
                <div class="assessment-summary__progress container">
                    <div class="row">
                        <div class="progress assessment-summary__progress__bar">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                 aria-valuemax="100" style="width: <?php echo $progress['percentage']; ?>%;">
                                <?php echo $progress['percentage']; ?>%
                            </div>
                        </div>
                        <div class="assessment-summary__progress__text">
                            <h2><?php echo $progress['percentage']; ?>% of questions complete</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($page_data['data']['assessment_summary']['progress']['percentage'] < 100): ?>
        <div class="assessment-summary__container">
            <div class="assessment-summary__container__inner">
                <div class="assessment-summary__content">
                    <div class="assessment-summary__progress container">
                        <div class="col-md-8 col-md-offset-2">
                            <?php foreach ($page_data['data']['assessment_summary']['block_data'] as $block): ?>
                                <div class="assessment-summary__block">
                                    <?php if ($block['complete']): ?>

                                        <div class="assessment-summary__block__tick">
                                            <?php if (file_exists("courses/{$path}/resources/images/big_tick_default.png")): ?>
                                                <img
                                                    src="<?php echo base_url(); ?>courses/<?php echo $path; ?>/resources/images/big_tick_default.png"/>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="assessment-summary__block__tick">
                                            <?php if (file_exists("courses/{$path}/resources/images/big_cross_default.png")): ?>
                                                <img
                                                    src="<?php echo base_url(); ?>courses/<?php echo $path; ?>/resources/images/big_cross_default.png"/>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="assessment-summary__block__title">
                                        <?php echo $block['title']; ?>
                                    </div>

                                    <div class="assessment-summary__block__content">
                                        <?php foreach ($block['questions'] as $question): ?>
                                            <div class="assessment-summary__questions">
                                                <div class="assessment-summary__questions__title">
                                                    <?php echo $question['name']; ?>
                                                </div>

                                                <div class="assessment-summary__questions__box clearfix">
                                                    <?php if ($question['completed']): ?>
                                                        <div class="assessment-summary__question__tick pull-right">
                                                            <?php if (file_exists("courses/{$path}/resources/images/tick_default.png")): ?>
                                                                <img
                                                                    src="<?php echo base_url(); ?>courses/<?php echo $path; ?>/resources/images/tick_default.png"/>
                                                            <?php else: ?>
                                                                <img
                                                                    src="<?php echo base_url(); ?>images/course_default/assessment-sum-tick.png"/>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="assessment-summary__question__cross pull-right">
                                                            <?php if (file_exists("courses/{$path}/resources/images/cross_default.png")): ?>
                                                                <img
                                                                    src="<?php echo base_url(); ?>courses/<?php echo $path; ?>/resources/images/cross_default.png"/>
                                                            <?php else: ?>
                                                                <img
                                                                    src="<?php echo base_url(); ?>images/course_default/assessment-sum-tick.png"/>
                                                            <?php endif; ?>

                                                            <a href="<?php echo base_url("/course/run") . '/' . $question['loc'] . '#' . $question['short_name']; ?>">
                                                                Go back
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
    <?php
    if ($page_data['data']['cert_data']['has_certificate']) : ?>
        <div class="assessment-summary__container assessment-summary__download">
            <div class="assessment-summary__download__content">
                <div class="row">
                    <div class="assessment-summary__download__text col-md-7 col-md-offset-2">
                        <h1>DOWNLOAD YOUR <br>CERTIFICATE HERE</h1>
                    </div>
                    <div class="assessment-summary__download__btn">
                        <a href="<?= $constants['base_url'] ?>api/cert/download/<?= $page_data['data']['cert_data']['course_path'] ?>/<?= $page_data['data']['cert_data']['user_id'] ?>/<?= $page_data['data']['cert_data']['assessment_id'] ?>"><i
                                class="icon dm-ribbon"></i></a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>