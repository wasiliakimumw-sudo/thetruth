<?php
/**
 * Scripts and Styles Enqueue
 */

function globalnews_enqueue_scripts() {
    $version = GLOBALNEWS_VERSION;

    wp_enqueue_style('globalnews-fonts', globalnews_fonts_url(), array(), null);
    wp_enqueue_style('globalnews-style', GLOBALNEWS_URI . '/assets/css/main.css', array(), $version);
    wp_enqueue_style('globalnews-dark-mode', GLOBALNEWS_URI . '/assets/css/dark-mode.css', array(), $version);
    wp_enqueue_style('globalnews-responsive', GLOBALNEWS_URI . '/assets/css/responsive.css', array('globalnews-style'), $version);

    wp_enqueue_script('globalnews-main', GLOBALNEWS_URI . '/assets/js/main.js', array(), $version, true);
    wp_enqueue_script('globalnews-dark-mode', GLOBALNEWS_URI . '/assets/js/dark-mode.js', array(), $version, true);
    wp_enqueue_script('globalnews-breaking', GLOBALNEWS_URI . '/assets/js/breaking-news.js', array(), $version, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_localize_script('globalnews-main', 'globalnewsData', array(
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('globalnews_nonce'),
        'themeUri'    => GLOBALNEWS_URI,
        'isMobile'    => wp_is_mobile(),
        'homeUrl'     => home_url(),
    ));
}
add_action('wp_enqueue_scripts', 'globalnews_enqueue_scripts');

function globalnews_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    $fonts[] = 'Inter:wght@300;400;500;600;700;800;900';
    $fonts[] = 'Poppins:wght@300;400;500;600;700;800';

    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family'  => rawurlencode(implode('&family=', $fonts)),
            'display' => 'swap',
        ), 'https://fonts.googleapis.com/css2');
    }

    return $fonts_url;
}

function globalnews_admin_scripts($hook) {
    wp_enqueue_style('globalnews-admin', GLOBALNEWS_URI . '/assets/css/admin.css', array(), GLOBALNEWS_VERSION);
    wp_enqueue_script('globalnews-admin-menu', GLOBALNEWS_URI . '/assets/js/admin-menu.js', array(), GLOBALNEWS_VERSION, true);

    if ('settings_page_globalnews-site-settings' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('globalnews-admin-settings', GLOBALNEWS_URI . '/assets/js/admin-settings.js', array('jquery'), GLOBALNEWS_VERSION, true);
        wp_localize_script('globalnews-admin-settings', 'globalnewsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('globalnews_admin_nonce'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'globalnews_admin_scripts');
