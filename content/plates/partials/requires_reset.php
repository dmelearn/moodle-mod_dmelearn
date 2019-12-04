<?php // Course shell version 1. ?>
<style>
.course_head_expired {
    padding: 30px;
}
.content-container_expired {
    padding-bottom: 40px;
}
.button-click {
    margin-top: 5px;
}
</style>
<div class="course_head course_head_expired">
    <div class="course_head_inner container">
    <?php if(isset($course_data['name'])): ?>
        <h1><?= $course_data['name'] ?></h1>
    <?php endif; ?>
    </div>
</div>
<div class="container content-container content-container_expired">
    <div class="row course_content">
        <div class="alert alert-info clearfix row-fliud reset_and_cert_buttons">

            <?php if (isset($timeframemonths) && ($timeframemonths > 1)) : ?>
            <p class="pull-left">The assessment for this course has expired because you previously completed it more than <?=$timeframemonths?> month
                <?php if ($timeframemonths > 1):?>s<?php endif; ?> ago. Press the 'Reset Course' button to restart your assessment.</p>
            <?php endif; ?>


            <p class="pull-left">The assessment for this course has expired because you previously completed it in a previous year.
            Press the 'Reset Course' button to restart your assessment.</p>

            <a class="btn pull-right btn-success button-click" href="<?=$constants['base_url']?>api/cert/download/<?=$page_data['data']['cert_data']['course_path']?>/<?=$page_data['data']['cert_data']['user_id']?>/<?=$page_data['data']['cert_data']['assessment_id']?>">Download Certificate (PDF)</a>
            <button type="button" class="btn btn-danger pull-right button-click" data-toggle="modal" data-target=".reset_modal">Reset Course</button>
            
        </div>
        <?php // Modal. ?>
        <div class="modal fade reset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h3 id="myModalLabel">Are you sure you want to reset this course?</h3>
                    </div>
                    <div class="modal-body">
                        <p>All of your assessments for this course will be reset so you can re-complete it again from the beginning.</p>
                        <p><strong>You will not be able download previous DM Certificates for this course once it has been reset.</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        <button class="btn btn-danger reset_button" href="reset_course.php">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>