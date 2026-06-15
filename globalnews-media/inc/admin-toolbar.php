<?php

function globalnews_customize_admin_bar() {
    global $wp_admin_bar;

    $remove_nodes = array(
        'wp-logo',
        'site-name',
        'comments',
        'new-content',
        'my-account',
        'updates',
        'search',
        'archive',
        'view-site',
        'edit',
        'appearance',
    );

    foreach ($remove_nodes as $node_id) {
        $wp_admin_bar->remove_node($node_id);
    }
}
add_action('wp_before_admin_bar_render', 'globalnews_customize_admin_bar', 999);

function globalnews_admin_footer_bar() {
    if (!is_admin() || !is_user_logged_in()) {
        return;
    }

    $current_user = wp_get_current_user();
    $comments = wp_count_comments();
    $comments_total = $comments->total_comments;
    $comments_moderated = $comments->moderated;
    ?>
    <div id="globalnews-admin-footer">
        <div class="gn-footer-section gn-footer-left">
            <span class="gn-footer-site-name"><?php echo esc_html(get_bloginfo('name')); ?></span>
            <a href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Dashboard', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('plugins.php')); ?>"><?php esc_html_e('Plugins', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('themes.php')); ?>"><?php esc_html_e('Themes', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('widgets.php')); ?>"><?php esc_html_e('Widgets', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Menus', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php esc_html_e('Customize', 'globalnews-media'); ?></a>
        </div>

        <div class="gn-footer-section gn-footer-center">
            <span class="gn-footer-label"><?php esc_html_e('New:', 'globalnews-media'); ?></span>
            <a href="<?php echo esc_url(admin_url('post-new.php')); ?>"><?php esc_html_e('Post', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('media-new.php')); ?>"><?php esc_html_e('Media', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=page')); ?>"><?php esc_html_e('Page', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=video')); ?>"><?php esc_html_e('Video', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=audio')); ?>"><?php esc_html_e('Audio', 'globalnews-media'); ?></a>
            <?php if (current_user_can('create_users')): ?>
                <a href="<?php echo esc_url(admin_url('user-new.php')); ?>"><?php esc_html_e('User', 'globalnews-media'); ?></a>
            <?php endif; ?>
        </div>

        <div class="gn-footer-section gn-footer-right">
            <a href="<?php echo esc_url(admin_url('edit-comments.php')); ?>" class="gn-footer-comments">
                <?php echo esc_html($comments_total); ?>
                <?php esc_html_e('Comments', 'globalnews-media'); ?>
                <?php if ($comments_moderated > 0): ?>
                    <span class="gn-footer-mod-count"><?php echo esc_html($comments_moderated); ?> <?php esc_html_e('in moderation', 'globalnews-media'); ?></span>
                <?php endif; ?>
            </a>
            <span class="gn-footer-user">
                <?php echo esc_html(
                    sprintf(
                        __('Howdy, %s', 'globalnews-media'),
                        $current_user->display_name
                    )
                ); ?>
            </span>
            <a href="<?php echo esc_url(get_edit_user_link()); ?>"><?php esc_html_e('Edit Profile', 'globalnews-media'); ?></a>
            <a href="<?php echo esc_url(wp_logout_url()); ?>"><?php esc_html_e('Log Out', 'globalnews-media'); ?></a>
        </div>

        <div class="gn-footer-bottom">
            <a href="https://wordpress.org/about/" target="_blank" rel="noopener"><?php esc_html_e('About WordPress', 'globalnews-media'); ?></a>
            <a href="https://make.wordpress.org/" target="_blank" rel="noopener"><?php esc_html_e('Get Involved', 'globalnews-media'); ?></a>
            <a href="https://wordpress.org/" target="_blank" rel="noopener"><?php esc_html_e('WordPress.org', 'globalnews-media'); ?></a>
            <a href="https://wordpress.org/documentation/" target="_blank" rel="noopener"><?php esc_html_e('Documentation', 'globalnews-media'); ?></a>
            <a href="https://learn.wordpress.org/" target="_blank" rel="noopener"><?php esc_html_e('Learn WordPress', 'globalnews-media'); ?></a>
            <a href="https://wordpress.org/support/" target="_blank" rel="noopener"><?php esc_html_e('Support', 'globalnews-media'); ?></a>
            <a href="https://wordpress.org/support/forum/requests-and-feedback/" target="_blank" rel="noopener"><?php esc_html_e('Feedback', 'globalnews-media'); ?></a>
        </div>
    </div>
    <?php
}
add_action('admin_footer', 'globalnews_admin_footer_bar', 1);

function globalnews_admin_footer_styles() {
    if (!is_admin() || !is_user_logged_in()) {
        return;
    }
    ?>
    <style>
        #wpadminbar { display: none !important; }
        #wpcontent, #wpfooter { margin-top: 0; }
        html.wp-toolbar { padding-top: 0 !important; }
        #globalnews-admin-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 99999;
            background: #1d2327;
            color: #f0f0f1;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            border-top: 1px solid #2c3338;
            box-shadow: 0 -2px 10px rgba(0,0,0,.3);
        }
        #globalnews-admin-footer a {
            color: #9ec2e6;
            text-decoration: none;
            padding: 0 6px;
            white-space: nowrap;
        }
        #globalnews-admin-footer a:hover { color: #72aee6; text-decoration: underline; }
        .gn-footer-section {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 2px;
            padding: 4px 12px;
            border-bottom: 1px solid #2c3338;
        }
        .gn-footer-section:last-of-type { border-bottom: none; }
        .gn-footer-left { justify-content: flex-start; }
        .gn-footer-center { justify-content: center; }
        .gn-footer-right { justify-content: flex-end; }
        .gn-footer-bottom {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 2px;
            padding: 4px 12px;
            background: #15171a;
            font-size: 12px;
        }
        .gn-footer-site-name {
            font-weight: 600;
            color: #fff;
            padding-right: 10px;
            margin-right: 6px;
            border-right: 1px solid #2c3338;
        }
        .gn-footer-label {
            color: #a0a5aa;
            margin-right: 4px;
            font-weight: 500;
        }
        .gn-footer-comments { position: relative; }
        .gn-footer-mod-count {
            display: inline-block;
            background: #d63638;
            color: #fff;
            font-size: 10px;
            padding: 1px 5px;
            border-radius: 8px;
            margin-left: 2px;
            font-weight: 600;
        }
        .gn-footer-user {
            color: #f0f0f1;
            padding: 0 6px;
            font-weight: 500;
        }
        #wpfooter { padding-bottom: 80px; }
    </style>
    <?php
}
add_action('admin_print_styles', 'globalnews_admin_footer_styles');
