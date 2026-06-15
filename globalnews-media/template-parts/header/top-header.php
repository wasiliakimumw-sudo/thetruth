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
            <div class="top-social-icons">
                <a href="<?php echo esc_url(get_theme_mod('globalnews_social_facebook', '#')); ?>" class="social-top-link facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="<?php echo esc_url(get_theme_mod('globalnews_social_twitter', '#')); ?>" class="social-top-link twitter" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="<?php echo esc_url(get_theme_mod('globalnews_social_instagram', '#')); ?>" class="social-top-link instagram" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069z"/></svg>
                </a>
                <a href="<?php echo esc_url(get_theme_mod('globalnews_social_youtube', '#')); ?>" class="social-top-link youtube" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.017 3.017 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z"/></svg>
                </a>
                <a href="<?php echo esc_url(get_theme_mod('globalnews_social_linkedin', '#')); ?>" class="social-top-link linkedin" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
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
