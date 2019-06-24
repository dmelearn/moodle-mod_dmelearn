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
                                 aria-valuemax="100"
                                 style="width: <?php echo $page_data['data']['assessment_summary']['progress']['percentage']; ?>%;">
                                <?php echo $page_data['data']['assessment_summary']['progress']['percentage']; ?>%
                            </div>
                        </div>
                        <div class="assessment-summary__progress__text">
                            <h2><?php echo $page_data['data']['assessment_summary']['progress']['percentage']; ?>% of
                                questions complete</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($page_data['data']['assessment_summary']['progress']['percentage'] < 100) : ?>
        <div class="assessment-summary__container">
            <div class="assessment-summary__container__inner">
                <div class="assessment-summary__content">
                    <div class="assessment-summary__progress container">
                        <div class="col-md-8 col-md-offset-2">
                            <?php foreach ($page_data['data']['assessment_summary']['block_data'] as $block) : ?>
                                <div class="assessment-summary__block">
                                    <?php if ($block['complete']) : ?>
                                        <div class="assessment-summary__block__tick">
                                            <img
                                                src="<?= $constants['base_url'] ?>courses/<?= $page_data['data']['cert_data']['course_path'] ?>/resources/images/big_tick_default.png">
                                        </div>
                                    <?php else : ?>
                                        <div class="assessment-summary__block__tick">
                                            <img
                                                src="<?= $constants['base_url'] ?>courses/<?= $page_data['data']['cert_data']['course_path'] ?>/resources/images/big_cross_default.png">
                                        </div>
                                    <?php endif; ?>
                                    <div class="assessment-summary__block__title">
                                        <?php echo $block['title']; ?>
                                    </div>

                                    <div class="assessment-summary__block__content">
                                        <?php foreach ($block['questions'] as $question) : ?>
                                            <div class="assessment-summary__questions">
                                                <div class="assessment-summary__questions__title">
                                                    <?php echo $question['name']; ?>
                                                </div>

                                                <div class="assessment-summary__questions__box clearfix">
                                                    <?php if ($question['completed']) : ?>
                                                        <div class="assessment-summary__question__tick pull-right">
                                                            <i class="icon dm-check"></i>
                                                        </div>
                                                    <?php else : ?>
                                                        <?php $fix_backslashed_urls = str_replace("\\","/", $question['loc']); ?>
                                                        <?php $cmp = explode("/", $fix_backslashed_urls); ?>
                                                        <div class="assessment-summary__question__cross pull-right">
                                                            <i class="icon dm-cross"></i>
                                                            <a href="?id=<?= $mod_id ?>&course=<?= $cmp[0] ?>&module=<?= $cmp[1] ?>&page=<?= $cmp[2] ?>#<?= $question['short_name'] ?>">
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
                    <a href="<?= $constants['base_url'] ?>api/cert/download/<?= $page_data['data']['cert_data']['course_path'] ?>/<?= $page_data['data']['cert_data']['user_id'] ?>/<?= $page_data['data']['cert_data']['assessment_id'] ?>">
                        <div class="assessment-summary__download__text col-md-8 col-md-offset-2">
                            <h1>DOWNLOAD YOUR<br>CERTIFICATE HERE</h1>
                            <i class="icon dm-ribbon" style="font-size: 50px"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
