<?php

define('GLOBALNEWS_VERSION', '2.0.0');
define('GLOBALNEWS_DIR', get_template_directory());
define('GLOBALNEWS_URI', get_template_directory_uri());

if (!defined('GLOBALNEWS_DEBUG')) {
    define('GLOBALNEWS_DEBUG', defined('WP_DEBUG') && WP_DEBUG);
}

$inc_files = array(
    'theme-setup',
    'enqueue',
    'helpers',
    'menu',
    'widgets',
    'customizer',
    'ads',
    'dark-mode',
    'seo',
    'performance',
    'security',
    'meta-boxes',
    'user-roles',
    'admin-settings',
    'class-sitemap',
    'class-pwa',
    'class-rss',
    'class-social-auto',
    'class-workflow',
);

foreach ($inc_files as $file) {
    $path = GLOBALNEWS_DIR . '/inc/' . $file . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
}

function globalnews_init_environment() {
    if (!is_admin() && !wp_doing_ajax() && !defined('REST_REQUEST')) {
        globalnews_add_security_headers();
    }
}
add_action('init', 'globalnews_init_environment', 0);

function globalnews_flush_rewrite_rules() {
    $version = get_option('globalnews_rewrite_rules_version', '');
    if ($version !== GLOBALNEWS_VERSION) {
        update_option('globalnews_rewrite_rules_version', GLOBALNEWS_VERSION);
        flush_rewrite_rules();
    }
}
add_action('wp_loaded', 'globalnews_flush_rewrite_rules', 999);
