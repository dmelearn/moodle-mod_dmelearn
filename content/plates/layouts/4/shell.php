<?php //Course shell version 4 ?>
<div class="course_head">
    <div class="course_head_inner container">
        <h1><?=$course_data['name']?></h1>
    </div>
</div>
<div class="container content-container">
    <div class="row course_content">
        <?php //If the user has completed the course the reset and certificate buttons will appear ?>
        <?php if ($page_data['data']['cert_data']['has_certificate']): ?>
            <div class="alert alert-info clearfix row-fliud reset_and_cert_buttons">
                <p class="pull-left"><strong>Well Done!</strong>  You have completed the assessment for this course.</p>
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
        <?php endif;?>



        <? //page content ?>
        <div id="maincontent" class="col-md-12 course_body">
            <?php if (isset($page_data['data']['assessment_summary'])): ?>
                <?php $this->insert('partials/assessment_summary'); ?>
            <?php elseif (isset($page_data['content'])): ?>
                <?=$page_data['content']?>
            <?php endif; ?>
        </div>
        <?php //Next and back buttons at bottom of screen ?>
        <div class="col-md-12">
            <div class="col-md-3 bottom-pn">
                <?php $this->insert('partials/prev_next'); ?>
            </div>
        </div>
    </div>
</div>