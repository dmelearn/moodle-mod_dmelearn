<section class="assessment-summary">
    <div class="section-content">
        <div class="assessment-summary__container assessment-summary__container--top">
            <div class="assessment-summary__container__inner">
                <div class="assessment-summary__content">
                    <div class="assessment-summary__heading">
                        <h3>Assessment Summary</h3>
                    </div>
                    <div class="assessment-summary__progress">
                        <div class="row-fluid">
                            <div class="assessment-summary__progress__text">
                                <h4>
                                    <?php echo $page_data['data']['assessment_summary']['progress']['completed_questions']; ?>
                                    out
                                    of <?php echo $page_data['data']['assessment_summary']['progress']['total_questions']; ?>
                                    questions complete
                                    (<?php echo $page_data['data']['assessment_summary']['progress']['percentage']; ?>
                                    %)</h4>
                            </div>
                            <div class="progress assessment-summary__progress__bar">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                     aria-valuemax="100"
                                     style="width: <?php echo $page_data['data']['assessment_summary']['progress']['percentage']; ?>%;">
                                </div>
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
                        <div class="assessment-summary__progress">
                            <div class="">
                                <?php foreach ($page_data['data']['assessment_summary']['block_data'] as $block): ?>
                                    <div class="assessment-summary__block">
                                        <?php if ($block['complete']): ?>
                                            <div class="assessment-summary__block__title clearfix">
                                                <div class="pull-left"><?php echo $block['title']; ?></div>
                                                <div class="pull-right"><i class="icon dm-check"></i> Complete
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="assessment-summary__block__title clearfix">
                                                <div class="pull-left"><?php echo $block['title']; ?></div>
                                                <div class="pull-right"><?php echo $block['number_completed'] ?>
                                                    of <?php echo $block['mark'] ?> questions completed
                                                </div>
                                            </div>
                                            <div class="assessment-summary__block__content">
                                                <?php if ($block['randomised'] == true): ?>
                                                    <?php if (isset($block['loc']) && !empty($block['loc'])): ?>
                                                        <div class="assessment-summary__questions">
                                                            <div class="assessment-summary__questions__title">
                                                                <?php echo $block['mark']; ?> randomly selected
                                                                questions.
                                                            </div>
                                                            <div class="assessment-summary__questions__box clearfix">
                                                                <div
                                                                    class="assessment-summary__questions__cross pull-right">
                                                                    <?php $cmp = explode("/", $block['loc']) ?>
                                                                    <a href="?id=<?=$mod_id?>&course=<?=$cmp[0]?>&module=<?=$cmp[1]?>&page=<?=$cmp[2]?>">Go
                                                                        to page <i
                                                                            class="icon dm-arrow-forward"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <?php foreach ($block['questions'] as $question): ?>
                                                        <div class="assessment-summary__questions">
                                                            <div class="assessment-summary__questions__title">
                                                                <?php echo $question['name']; ?>
                                                            </div>

                                                            <div class="assessment-summary__questions__box clearfix">
                                                                <?php if ($question['completed']): ?>
                                                                    <div
                                                                        class="assessment-summary__questions__tick pull-right">
                                                                        <p><i class="icon dm-check"></i> Correct</p>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <?php $cmp = explode("/", $question['loc']); ?>
                                                                    <div class="assessment-summary__questions__cross pull-right">
                                                                        <a href="?id=<?= $mod_id ?>&course=<?= $cmp[0] ?>&module=<?= $cmp[1] ?>&page=<?= $cmp[2]?>#<?= $question['short_name'] ?>">
                                                                            Go to question <i
                                                                                class="icon dm-arrow-forward"></i>
                                                                        </a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
        <?php
        if (isset($certificate) && $certificate): ?>
            <div class="assessment-summary__container assessment-summary__download">
                <div class="assessment-summary__download__content">
                    <div class="row-fluid">
                        <div class="assessment-summary__certificate text-center">
                            <a href="<?php echo base_url() . "certificate/get/{$path}" ?>"><i
                                    class="icon dm-ribbon"></i></a>
                        </div>
                        <div class="assessment-summary__download__text text-center">
                            <a class="btn dm-btn" href="<?php echo base_url() . "certificate/get/{$path}" ?>"
                               target="_blank">
                                <div>Download your certificate</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <?php if (isset($certificate) && $course_evaluation): ?>
            <div class="assessment-summary__evaluation text-center">
                <?php echo($course_evaluation); ?>
            </div>
        <?php endif; ?>

    </div>
</section>