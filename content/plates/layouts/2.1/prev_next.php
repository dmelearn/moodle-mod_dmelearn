<div class="prev-next row">
    <?php if ($previous_url): ?>
    <a class="col-md-6 col-sm-6 prev-next__btn prev-next__btn--prev"
       href="<?=$lmscontenturl?>&module=<?=$previous_url['module']?>&page=<?=$previous_url['page']?>">
        <div class="prev-next__btn__inner">
            <i class="icon dm-arrow-back"></i>PREV
        </div>
    </a>
    <?php endif; ?>

    <?php if ($next_url): ?>
    <a class="col-md-6 col-sm-6 prev-next__btn prev-next__btn--next pull-right"
       href="<?=$lmscontenturl?>&module=<?=$next_url['module']?>&page=<?=$next_url['page']?>">
        <div class="prev-next__btn__inner">
            NEXT<i class="icon dm-arrow-forward"></i>
        </div>
    </a>
    <?php endif; ?>
</div>