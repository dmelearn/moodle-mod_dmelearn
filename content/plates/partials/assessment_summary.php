<?php
//Assessment Summary
$version = isset($course_data['configuration']['course_version']) ? $course_data['configuration']['course_version'] : null;
// If course version has not been set it should be a version 1
if ($version === null) {
    $version = 1;
}
?>
<section>
    <table class="table quiz_summary">
        <?php foreach ($page_data['data']['assessment_summary']['block_data'] as $block) : ?>

        <tr>
            <?php //Block name ?>
            <td>
                <?php if (isset($block['questions'])) : ?>
                    <?php if ($version === null || $version === 1) : ?>
                    <a class="dropdown_summary" href="#"><i class="icon-chevron-down" style="font-size:22px;"></i><?=$block['title']?></a>
                    <?php else : ?>
                    <a class="dropdown_summary" href="#"><i class="fa fa-chevron-down" style=font-size:22px;"></i><?=$block['title']?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <?php //Number correct ?>
            <td><?=$block['number_completed']?> out of <?=$block['mark']?> questions completed</td>
            <?php //If Complete Show a tick ?>
            <td>
                <?php if ($block['complete']) : ?>
                <img src="<?=$course_constants['course_img']?>tick_default.png">
                <?php else : ?>
                <img src="<?=$constants['base_img']?>course_default/assessment-sum-roundel.png">
                <?php endif; ?>
            </td>
            <?php //Loc Link if Available ?>
            <td class="link">

                <?php if (isset($block['loc']) && !empty($block['loc'])) : ?>
                    <?php $cmp = explode("/", $block['loc']) ?>
                    <a class="btn" href="?id=<?=$mod_id?>&course=<?=$cmp[0]?>&module=<?=$cmp[1]?>&page=<?=$cmp[2]?>">go to assessment</a>
                <?php endif; ?>
            </td>

            <tr class="second-tier" style="display:none;">
                <td colspan="4" class="second-td">
                    <table class="table">
                        <tbody>
                        <?php if ($block['randomised'] == true) : ?>
                            <tr class="question-level">
                                <td colspan="4">The <?=$block['mark']?> questions are randomised from a bank</td>
                            </tr>
                        <?php else : ?>

                            <?php foreach ($block['questions'] as $question_data) : ?>
                                <tr class="question-level">
                                    <td><?=$question_data['name']?></td>
                                    <td class="middle"><?=$question_data['type']?></td>
                                    <?php if (empty($question_data['loc'])) : ?>
                                        <td colspan='2'>
                                    <?php else : ?>
                                        <td>
                                    <?php endif; ?>

                                    <?php if ($question_data['completed']) : ?>
                                        <img src="<?=$course_constants['course_img']?>tick_default.png">
                                    <?php else : ?>
                                        <img src="<?=$constants['base_img']?>course_default/assessment-sum-roundel.png">
                                    <?php endif; ?>
                                    </td>

                                    <?php if (isset($question_data['loc']) && !empty($question_data['loc'])) : ?>
                                    <td class="middle">
                                        <?php $cmp = explode("/", $question_data['loc']) ?>
                                        <a class="btn" href="?id=<?=$mod_id?>&course=<?=$cmp[0]?>&module=<?=$cmp[1]?>&page=<?=$cmp[2]?>">Go to Question</a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;?>

                        <?php endif; ?>
                        </tbody>
                    </table>
                </td>
            </tr>

        <?php endforeach;?>

    </table>
    <?php //Overall progress ?>
    <div class="summary_progess_bar">
        <h2 class="assessment-sum-head"> You are <?=$page_data['data']['assessment_summary']['progress']['percentage']?>% complete (<?=$page_data['data']['assessment_summary']['progress']['completed_questions']?> of <?=$page_data['data']['assessment_summary']['progress']['total_questions']?> questions correct) </h2>
        <div class="progress assessment-sum-bar">
            <div class="bar bar-success" style="width:<?=$page_data['data']['assessment_summary']['progress']['percentage']?>%"></div>
        </div>
    </div>
</section>
