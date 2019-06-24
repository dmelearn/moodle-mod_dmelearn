<div class="prev-next clearfix">
    <?php if ($previous_url) : ?>
        <a class="col-md-6 col-sm-6 col-xs-6 prev-next__btn prev-next__btn--prev"
           href="<?= $lms_content_url ?>&module=<?= $previous_url['module'] ?>&page=<?= $previous_url['page'] ?>">
            <div class="prev-next__btn__inner">
                <i class="icon dm-arrow-back"></i><span>PREV</span><span class="hoverText">Previous Page</span>
            </div>
        </a>
    <?php endif; ?>

    <?php if ($next_url) : ?>
        <a class="col-md-6 col-sm-6 col-xs-6 prev-next__btn prev-next__btn--next pull-right"
           href="<?= $lms_content_url ?>&module=<?= $next_url['module'] ?>&page=<?= $next_url['page'] ?>">
            <div class="prev-next__btn__inner">
                <span>NEXT</span><i class="icon dm-arrow-forward"></i><span class="hoverText">Next Page</span>
            </div>
        </a>
    <?php endif; ?>
</div>