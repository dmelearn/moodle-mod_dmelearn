<section>
    <table class="table quiz_summary">
        <?php foreach ($page_request['data']['assessment_summary']['block_data'] as $block): ?>
        <tr>
            <!-- Block Name -->
                <td><a class="dropdown_summary" href="#"><?php if (isset($block['questions'])) {
            echo '<i class="icon-chevron-down" style:"font-size:22px;"></i>';
        } ?><?php echo $block['title']; ?></a></td>
                <!-- Number Correct -->
                <td><?php echo $block['number_completed']; ?> out of <?php echo $block['mark']; ?> questions completed</td>
                <!-- If Complete Show a tick -->
                <td>
                    <?php if ($block['complete']): ?>
            <?php if (elmo_url_exists(ELMO_WEB_BASE_URL . "courses/{$course}/resources/images/tick_default.png")): ?>
                <img
                    src=<?php echo ELMO_WEB_BASE_URL; ?>"courses/<?php echo $course; ?>/resources/images/tick_default.png"/>
            <?php else: ?>
                <img src="<?php echo ELMO_WEB_BASE_IMAGES; ?>course_default/assessment-sum-tick.png"/>
            <?php endif; ?>
        <?php else: ?>
            <?php if (elmo_url_exists(ELMO_WEB_BASE_URL . "courses/{$course}/resources/images/roundel.png")): ?>
                <img src="<?php echo ELMO_WEB_BASE_URL; ?>courses/<?php echo $course; ?>/resources/images/roundel.png"/>
            <?php else: ?>
                <img src="<?php echo ELMO_WEB_BASE_IMAGES; ?>course_default/assessment-sum-roundel.png"/>
            <?php endif; ?>
        <?php endif; ?>
                </td>
                <!-- Loc Link if Available -->
                <td class="link">
                    <?php if (isset($block['loc']) && !empty($block['loc'])): ?>
            <a class="btn" href='<?php echo site_url("/course/run/") . "/" . $block['loc']; ?>'>go to assessment</a>
        <?php endif; ?>
                </td>
                <tr class="second-tier" style="display:none;">
                    <td colspan="4" class="second-td">
                        <table class="table">
                            <tbody>
                            <?php if ($block['randomised'] === true): ?>
            <tr class="question-level">
                <td colspan="4">The <?php echo $block['mark']; ?> questions are randomised from a bank</td>
            </tr>
        <?php else: ?>
            <?php // foreach question loop ?>
            <?php foreach ($block['questions'] as $question_data): ?>
                <tr class="question-level">
                    <td><?php echo $question_data['name']; ?></td>
                    <td class="middle"><?php echo $question_data['type']; ?></td>
                    <td <?php if (empty($question_data['loc'])) {
                        echo "colspan='2'";
                    } ?>>
                        <?php if ($question_data['completed']): ?>
                            <?php if (elmo_url_exists(ELMO_WEB_BASE_URL . "courses/{$course}/resources/images/tick_default.png")): ?>
                                <img
                                    src="<?php echo ELMO_WEB_BASE_URL; ?>courses/<?php echo $course; ?>/resources/images/tick_default.png"
                                    ;/>
                            <?php else: ?>
                                <img src='<?php echo ELMO_WEB_BASE_URL; ?>images/course_default/assessment-sum-tick.png'
                                     ;/>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (elmo_url_exists(ELMO_WEB_BASE_URL . "courses/{$course}/resources/images/roundel.png")): ?>
                                <img
                                    src="<?php echo ELMO_WEB_BASE_URL; ?>courses/<?php echo $course; ?>/resources/images/roundel.png"/>
                            <?php else: ?>
                                <img
                                    src="<?php echo ELMO_WEB_BASE_URL; ?>images/course_default/assessment-sum-roundel.png"/>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($question_data['loc']) && !empty($question_data['loc'])): ?>

                        <td class="middle"><a
                                href="<?php call_user_func_array('make_api_url', (list($course, $module, $page) = explode('/', $question_data['loc']))); ?>">Go
                                to Question</a></td>
                    <?php else: ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
        </tr>
    <?php endforeach; ?>
    </table>
    <!-- Over all progress -->
    <div class="summary_progess_bar">
        <?php if (isset($certificate) && $certificate):  // This has been replaced this with something else as certificates are not possible externally ?>
            <img src="<?php echo base_url(); ?>images/award-gold_medium_rotated.png"/>
            <p class="top"><b><i>Well done!</i></b> You have completed the assessment for this course</p>
            <p>(<?php echo $page_request['data']['assessment_summary']['progress']['completed_questions']; ?>
                of <?php echo $page_request['data']['assessment_summary']['progress']['total_questions']; ?> questions
                correct)</p>
            <a class="btn pull-right" href="<?php echo base_url() . "certificate/get/{$path}" ?>" target="_blank">Download
                certificate (PDF)</a>
            <div class="clear"></div>
        <?php else: ?>
            <h2 class="assessment-sum-head">You
                are <?php echo $page_request['data']['assessment_summary']['progress']['percentage']; ?>% complete
                (<?php echo $page_request['data']['assessment_summary']['progress']['completed_questions']; ?>
                of <?php echo $page_request['data']['assessment_summary']['progress']['total_questions']; ?> questions
                correct)</h2>
            <div class="progress assessment-sum-bar">
                <div class="bar bar-success"
                     style="width: <?php echo $page_request['data']['assessment_summary']['progress']['percentage'] . '%'; ?>"></div>
            </div>
        <?php endif; ?>
    </div>
</section>