<?php //Course shell version 1 ?>
<div class="course_head">
    <div class="course_head_inner container">
        <h1><?=$course_datap['name']?></h1>
    </div>
</div>
<div class="container content-container">
    <div class="row course_content">
        <div class="alert alert-info clearfix row-fliud reset_and_cert_buttons">
            <p class="pull-left">The assessment for this course has expired because you previously completed it more than <?=$timeframemonths?> month
                <?php if (timeframemonths > 1):?>s<?php endif; ?> ago. Press the 'Reset Course' button to restart your assessment.</p>
            <a class="btn pull-right btn-success" href="<?=$constants['base_url']?>api/cert/download/<?=$page_data['data']['cert_data']['course_path']?>/<?=$page_data['data']['cert_data']['user_id']?>/<?=$page_data['data']['cert_data']['assessment_id']?>">Download Certificate (PDF)</a>
            <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target=".reset_modal">Reset Course</button>
        </div>
        <?php //Modal ?>
        <div class="modal fade reset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h3 id="myModalLabel">Are you sure you want to reset this course?</h3>
                    </div>
                    <div class="modal-body">
                        <p>All of your assessments for this course will be reset so you can re-complete it again from the beginning.</p>
                        <p><strong>You will not be able access previous certificates for this course once it has been reset.</strong></p>
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