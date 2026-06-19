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
    </div>
</div>
