<?php //Course Footer V02 ?>
<div class="footer">
    <div class="container">
        <p class="version-stamp">
            <span class="footer-text">
                <picture class="dm-logo">
                    <source type="image/svg+xml" srcset="<?= $constants['base_img'] ?>dmlogo-footer.svg" width="160" height="41">
                    <img src="<?= $constants['base_img'] ?>dmlogo-footer.png"
                         alt="Digital Media"
                         width="160"
                         height="41"
                         data-no-retina>
                </picture>
            </span>
            <span class="sep">|</span>
            <span class="footer-text">Brought to you by <?= $constants['brand_name'] ?></span>
            <span class="sep">|</span>
            <span class="footer-text">Copyright &copy; <?= date('Y'); ?></span>
            <span class="sep">|</span>
            <span class="footer-text">
                <picture class='dm-logo'>
                    <source type="image/svg+xml" srcset="<?= $constants['base_img'] ?>gov-logo.svg"
                            width="230" height="50" data-no-retina>
                    <img src="<?= $constants['base_url'] ?>'assets/images/gov-logo.png"
                         alt="SA Health Government of South Australia"
                         width="230"
                         height="50"
                         data-no-retina>
                </picture>
            </span>
        </p>
    </div>
</div>

<?php $this->insert('layouts/02/footer_scripts'); ?>
