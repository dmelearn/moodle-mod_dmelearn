<?php
//LMS Menu
$version = $course_data['configuration']['course_version'];
?>
<div class="lmsmenu">
    <?php // Use Font Awesome 3 on version 1 courses and FA4 on all others ?>
    <div class="lmsmenuleft">
        <?php if ($version == null || $version == 1) : ?>
        <i class="icon-home"></i> <a href="<?=$wwwroot?>"> Home</a> <i class="icon-double-angle-right"></i> <i class="icon-tasks"></i><a href="<?=$wwwroot?>/course/view.php?id=<?=$course_id?>"> <?=$fullname?></a>
        <?php else : ?>
        <i class="fa fa-home"></i> <a href="<?=$wwwroot?>"> Home</a> <i class="fa fa-angle-double-right"></i> <i class="fa fa-tasks"></i><a href="<?=$wwwroot?>/course/view.php?id=<?=$course_id?>"> <?=$fullname?></a>
        <?php endif; ?>
    </div>
    <div class="lmsmenuright">
        You are logged in as <?=$first_name?> <?=$last_name?> (<a href="<?=$wwwroot?>/login/logout.php">Log out</a>)
    </div>
</div>
<?php //Style for LMS Menu ?>
<style>
.lmsmenu{background:#F5F5F5;padding:8px 0; box-shadow:0 3px 5px #8A8A8A; float:left; width:100%}
.lmsmenuleft{float:left;padding-left:20px}.lmsmenuright{float:right;padding-right:20px}.lmsmenu img{vertical-align:inherit}
</style>
