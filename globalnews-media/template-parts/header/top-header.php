<div class="top-header-bar">
    <div class="top-header-inner">
        <div class="top-header-left">
            <div class="top-site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                <div class="flag-strip"></div>
            </div>
            <nav class="top-primary-nav" aria-label="<?php esc_attr_e('Primary Menu', 'globalnews-media'); ?>">
                <?php
                if (has_nav_menu('primary')) :
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'top-primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ));
                else :
                    globalnews_primary_menu_fallback();
                endif;
                ?>
            </nav>
        </div>
        <div class="top-header-right">
            <span class="current-date" id="currentDate"><?php echo esc_html(date_i18n(get_option('date_format'))); ?></span>
            <span class="top-header-separator">|</span>
            <span class="current-time" id="currentTime"></span>
        </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateClock() {
        var now = new Date();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        var seconds = String(now.getSeconds()).padStart(2, '0');
        var el = document.getElementById('currentTime');
        if (el) el.textContent = hours + ':' + minutes + ':' + seconds;
    }
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
