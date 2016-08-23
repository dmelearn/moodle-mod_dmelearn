<?php
// Set this initial item id
$itemID = 1;
// Get the array of glossary items
$glossary = $course_data['glossary']['glossary_items'];

/**
 * Sort the json object into alphabetical order by title
 *
 * @param array $first first item to sort
 * @param array $second second item to sort
 * @return int sorting order
 */
function sortObjectsByTitle($first, $second)
{
    if ($first['title'] == $second['title']) {
        return 0;
    }
    return ($first['title'] < $second['title']) ? -1 : 1;
}

// Start the sorting of the objects
usort($glossary, 'sortObjectsByTitle');
?>
<div class="dm-gloss">
    <div class="dm-gloss__inner row">
        <div class="dm-gloss__heading"><h3>Glossary</h3><i class="icon dm-close pull-right"></i></div>
        <div class="dm-gloss__item-list col-md-4 col-sm-5 col-xs-6">
            <div class="dm-gloss__item-list__inner">
                <?php foreach ($glossary as $item) : ?>
                    <?php $itemID++; ?>
                    <div class="dm-gloss__item" id="gloss<?php echo $itemID; ?>">
                        <p class="dm-gloss__title"><?php echo($item['title']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="dm-gloss__body col-md-8 col-sm-7 col-xs-6">
            <?php $itemID = 1; ?>
            <div class="dm-gloss__body-item glossInstruc">
                <h4><i class="icon dm-arrow-round-back"></i> Select from this list to find out the definition.</h4>
            </div>
            <?php foreach ($glossary as $item) : ?>
                <?php $itemID++; ?>
                <div class="dm-gloss__body-item gloss<?php echo $itemID; ?>">
                    <h3 class="dm-gloss__body__title"><?php echo($item['title']); ?></h3>
                    <?php echo($item['html']); ?>
                    <?php if (isset($item['img']) && $item['img'] != "" && count($item['img']) > 0) : ?>
                        <?php foreach ($item['img'] as $img) : ?>
                            <?php if (isset($img['filename']) && $img['filename'] != "") : ?>
                                <div class="dm-gloss__img-box">
                                    <img class="dm-gloss__img"
                                         src="<?php echo ELMO_WEB_COURSE_IMAGES . '/glossary/' . $img['filename']; ?>"
                                         alt="<?php echo $img['alt_text']; ?>"
                                         title="<?php echo $img['alt_text']; ?>">
                                    <div class="dm-gloss__caption"> <?php echo $img['caption']; ?> </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="dm-gloss-tab">
    <i class="icon dm-glossary"></i>
    <p>Glossary</p>
</div>
<script>
    (function($, window, document, undefined) {
        var interactive = {
            init: function() {
                this.clickItem();
                this.openGlossary();
                this.closeGlossary();
            },
            clickItem: function() {
                $('.dm-gloss__item').on('click', function() {
                    $('.current').removeClass('current');
                    $(this).addClass('current');
                    var glossID = this.id;
                    $('.dm-gloss__body-item').hide();
                    $("." + glossID).show('fade');
                });
            },
            openGlossary: function() {
                $('.dm-gloss-tab').on('click', function() {
                    $('.dm-gloss').show('fade');
                });
            },
            closeGlossary: function() {
                $('.dm-gloss__heading .dm-close').on('click', function() {
                    $('.dm-gloss').hide('fade');
                });
            }
        };

        $(function() {
            interactive.init();
        });

    }(jQuery, window, document));
</script>