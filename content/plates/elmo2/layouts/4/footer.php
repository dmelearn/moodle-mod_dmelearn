<?php //Version 4 Footer ?>
<div class="footer">
    <div class="container">
        <hr class="clear">
        <p class="version-stamp">Brought to you by <?= $constants['brand_name'] ?> - &copy; <?=date("Y")?></p>

        <?php
        //If the user has completed the course the reset and certificate buttons will appear in the footer
        if ($page_data['data']['cert_data']['has_certificate']): ?>
            <div class="alert alert-info clearfix row-fliud reset_and_cert_buttons">
                <p class="pull-left"><strong>Well Done!</strong>  You have completed the assessment for this course.</p>
                <a class="btn pull-right btn-success" href="<?=$constants['base_url']?>api/cert/download/<?=$page_data['data']['cert_data']['course_path']?>/<?=$page_data['data']['cert_data']['user_id']?>/<?=$page_data['data']['cert_data']['assessment_id']?>">Download Certificate (PDF)</a>
                <button type="button" class="btn btn-danger pull-right" data-toggle="modal" data-target=".reset_modal">Reset Course</button>
            </div>
            <?php //Modal ?>
            <div class="modal fade reset_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 3000;">
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

    </div>
</div>
<?php //Jquery-UI ?>
<script src="<?=$constants['base_js']?>jquery-ui.min.js"></script>
<script src="<?=$constants['base_js']?>jquery.ui.touch-punch.min.js"></script>
<?php //Bootstrap 3 JS  ?>
<script src="js/bootstrap.min.js"></script>
