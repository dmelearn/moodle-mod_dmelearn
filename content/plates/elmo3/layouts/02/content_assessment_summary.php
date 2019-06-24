<?php

//$mydata = $this->data();
//unset($mydata['course_data']['navigation']);
//unset($mydata['html']);

//dump($mydata);

//exit();

extract($page_data['data']['assessment_summary']);
?>

<section>
    <div class="section-content">



        <div class="assessment-summary__container assessment-summary__container--top">
            <div class="assessment-summary__container__inner">
                <div class="assessment-summary__content">
                    <div class="assessment-summary__heading">
                        <?php if ($allQuestionsAttempted) : ?>
                            <h3>Assessment results</h3>
                        <?php else : ?>
                            <h3>Assessment summary</h3>
                        <?php endif; ?>
                    </div>
                    <div class="assessment-summary__progress">
                        <div class="clearfix">
                            <?php if (!$allQuestionsAttempted) : ?>
                                <div class="assessment-summary__overview text-center">
                                    <div class="assessment-summary__overview__top">
                                        Your current grade
                                    </div>
                                    <h2 class="assessment-summary__overview__grade">
                                        <?= $assessmentProgress ? $assessmentProgress : 0 ?>%
                                    </h2>
                                    <div class="text-center">
                                        <?php if (isset($passGrade) && $passGrade > 0) : ?>
                                            <small>(pass grade = <?= $passGrade ?>%)</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row-fluid">
                            <?php if (!$allQuestionsAttempted) : ?>
                                <div class="assessment-summary__progress__text">
                                    <h4>
                                        <?= $questionAttemptCount ?> out of <?= $questionCount ?>
                                        questions attempted
                                    </h4>
                                </div>
                                <div class="progress assessment-summary__progress__bar">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $passGrade ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100"
                                         style="width: <?= (int)($questionAttemptCount / $questionCount * 100) ?>%;">
                                    </div>
                                </div>
                                <br>
                            <?php endif; ?>

                            <?php if ($allQuestionsAttempted) : ?>
                                <div class="assessment-summary__overview text-center">
                                    <div class="text-center">
                                        <div class="assessment-summary__overview__top">
                                            Your final grade
                                        </div>
                                        <h2 class="assessment-summary__overview__grade">
                                            <?= $assessmentProgress ?>%
                                        </h2>
                                        <?php if ($passGrade && $passGrade > 0) : ?>
                                            <div class="text-center">
                                                <small>(pass grade = <?= $passGrade ?>%)</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($assessmentPassed) : ?>
                                        <?php if ($passGrade && $passGrade > 0) : ?>
                                            <h3>Congratulations!</h3>
                                            <p>You have successfully completed all requirements for this course.</p>

                                        <?php endif; ?>
                                    <?php elseif ($passGrade > 0 && $passGrade < 100) : ?>
                                        <p>You have not successfully completed the requirements for the course. </p>
                                        <?php if (!$prerequisiteFallBackRequired) : ?>
                                            <p>You can review your questions and reset the course and assessments
                                                below.</p>
                                        <?php else : ?>
                                            <p>You did not pass the course within the
                                                required number of <strong>attempt(s)</strong>. <br>
                                                Return to the course overview page for more information.</p>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div class="assessment-summary__container">
            <div class="assessment-summary__container__inner">
                <div class="assessment-summary__content">
                    <div class="assessment-summary__progress">
                        <div class="">
                            <?php foreach ($userQuestions as $module) : ?>
                                <div class="assessment-summary__block">
                                    <div class="assessment-summary__block__content">
                                        <div class="assessment-summary__block__title clearfix">
                                            <?= $module['moduleName'] ?>
                                            <div class="pull-right assessment-summary__block__title__count"><?= $module['correctCount'] ?>
                                                out of <?= $module['totalCount'] ?> questions correct
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (isset($module['questionData'])) : ?>
                                        <?php foreach ($module['questionData'] as $question) : ?>

                                            <div class="assessment-summary__questions">
                                                <div class="assessment-summary__questions__title">
                                                    <a href="<?= $this->buildURI($mod_id, '', '', $question['short_name'], $question['path']) ?>">
                                                        <?= ($question['name']) ?>
                                                    </a>
                                                </div>

                                                <div class="assessment-summary__questions__box clearfix">
                                                    <?php if ($question['complete'] && $question['correct']) : ?>
                                                        <div class="assessment-summary__questions__tick pull-right">
                                                            <p><i class="icon dm-check"></i></p>
                                                        </div>
                                                    <?php elseif ($question['complete'] && !$question['correct']) : ?>
                                                        <a class="btn dm-btn dm-btn--small dm-btn--inverse"
                                                           href="">
                                                            Review question
                                                        </a>

                                                        <div class="assessment-summary__questions__lock pull-right">
                                                            <p><i class="icon dm-cross"></i></p>
                                                        </div>
                                                    <?php else : ?>
                                                        <a class="btn dm-btn dm-btn--small dm-btn--inverse"
                                                           href="<?= $this->buildURI($mod_id, '', '', $question['short_name'], $question['path']) ?>">
                                                            Go to question
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if (isset($module['groupData'])) : ?>
                                        <?php foreach ($module['groupData'] as $questionGroup) : ?>
                                            <?php if (isset($questionGroup['questions'])) : ?>
                                                <?php foreach ($questionGroup['questions'] as $question) : ?>
                                                    <div class="assessment-summary__questions">
                                                        <div class="assessment-summary__questions__title">

                                                            <a href="<?= $this->buildURI($mod_id, '', '', $question['short_name'], $question['path']) ?>">
                                                                <?= $question['name'] ?>
                                                            </a>
                                                        </div>
                                                        <div class="assessment-summary__questions__box clearfix">
                                                            <?php if ($question['complete'] && $question['correct']) : ?>
                                                                <div class="assessment-summary__questions__tick pull-right">
                                                                    <p><i class="icon dm-check"></i></p>
                                                                </div>
                                                            <?php elseif ($question['complete'] && !$question['correct']) : ?>
                                                                <a class="btn dm-btn dm-btn--small dm-btn--inverse"
                                                                   href="<?= ("course/run/$path/" . $question['path'] . '#' . $question['short_name']) ?>">
                                                                    Review question
                                                                </a>

                                                                <div class="assessment-summary__questions__lock pull-right">
                                                                    <p><i class="icon dm-cross"></i></p>
                                                                </div>
                                                            <?php else : ?>
                                                                <a class="btn dm-btn dm-btn--small dm-btn--inverse"
                                                                   href="<?= ("course/run/$path/" . $question['path'] . '#' . $question['short_name']) ?>">
                                                                    Go to question
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <div class="assessment-summary__questions">
                                                    <div class="assessment-summary__questions__title"><?= $questionGroup['groupName'] ?>
                                                        (<?= $questionGroup['questionAmount'] ?> randomly selected
                                                        <?= (int)$questionGroup['questionAmount'] > 1 ? 'questions' : 'question' ?>
                                                        )
                                                    </div>
                                                    <div class="assessment-summary__questions__cross">
                                                        <a class="btn dm-btn dm-btn--small dm-btn--inverse"
                                                           href="<?= $this->buildURI($mod_id, '', '', $question['short_name'], $question['path']) ?>">
                                                            Go to
                                                            <?= (int)$questionGroup['questionAmount'] > 1 ? 'questions' : 'question' ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <?php if ($allQuestionsAttempted) : ?>

            <div class="assessment-summary__container text-center">

                <?php if (isset($courseEvaluation) && $courseEvaluation && $assessmentPassed) : ?>
                    <?= ($courseEvaluation); ?>
                    <br>
                    <br>
                <?php endif; ?>

                <?php if ($assessmentPassed) : ?>
                    <p>You can download a certificate of completion from the Course Overview page.</p>
                    <a class="btn dm-btn dm-btn--large dm-btn--marg" href="">
                        Return to Course Overview
                    </a>
                    <br>
                    <a class="btn dm-btn dm-btn--large dm-btn--inverse" href="<?= ("course/library") ?>">
                        Go to All Courses</a>
                <?php else : ?>
                    <?php if (!$prerequisiteFallBackRequired) : ?>
                        <a class="btn dm-btn dm-btn--large" href="">
                            Reset course and assessments </i>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>