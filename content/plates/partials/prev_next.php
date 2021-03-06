<?php
//Plates Template
$version = isset($course_data['configuration']['course_version']) ? $course_data['configuration']['course_version'] : null;
// If course version has not been set it should be a version 1
if ($version === null) {
    $version = 1;
}
?>
<?php if ($version === null || $version === 1) : ?>
<div class = "prev_next row-fluid">
    <?php if ($previous_url) : ?>
    <a class="button-flat-primary prev_button span6"
       href="<?=$lmscontenturl?>&module=<?=$previous_url['module']?>&page=<?=$previous_url['page']?>">
        <i class="icon icon-angle-left"></i>PREV<span class="drop_small">IOUS</span>
    </a>
    <?php endif; ?>
    <?php if ($next_url) : ?>
    <a class="button-flat-primary next_button span6"
       href="<?=$lmscontenturl?>&module=<?=$next_url['module']?>&page=<?=$next_url['page']?>">NEXT
        <i class="icon icon-angle-right"></i>
    </a>
    <?php endif; ?>
</div>
<?php elseif ($version != 4) : ?>
<div class = "prev_next row-fluid">
    <?php if ($previous_url) : ?>
    <a class="button-flat-primary prev_button col-md-6"
       href="<?=$lmscontenturl?>&module=<?=$previous_url['module']?>&page=<?=$previous_url['page']?>">
        <i class="fa fa-angle-left"></i>PREV<span class="drop_small">IOUS</span>
    </a>
    <?php endif; ?>
    <?php if ($next_url) : ?>
    <a class="button-flat-primary next_button col-md-6"
       href="<?=$lmscontenturl?>&module=<?=$next_url['module']?>&page=<?=$next_url['page']?>">NEXT
        <i class="fa fa-angle-right"></i>
    </a>
    <?php endif; ?>
</div>
<?php endif ?>

<?php
// JS for linking to pages in course version 4
if ($version == 4) : ?>
<script>
    function goToNextPage()
    {
        <?php if ($next_url) : ?>
        window.location = '<?=$lmscontenturl?>&module=<?=$next_url['module']?>&page=<?=$next_url['page']?>';
        <?php endif; ?>
    }
    function goToPrevPage()
    {
        <?php if ($previous_url) : ?>
        window.location = '<?=$lmscontenturl?>&module=<?=$previous_url['module']?>&page=<?=$previous_url['page']?>';
        <?php endif; ?>
    }
</script>
<?php endif; ?>
