<?php
/**
 * Security Functions
 */

remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

function globalnews_hide_wp_version() {
    return '';
}
add_filter('style_loader_src', 'globalnews_hide_wp_version');
add_filter('script_loader_src', 'globalnews_hide_wp_version');

function globalnews_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'globalnews_remove_x_pingback');

function globalnews_secure_login_errors() {
    return esc_html__('Invalid credentials.', 'globalnews-media');
}
add_filter('login_errors', 'globalnews_secure_login_errors');

add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', esc_html__('You must be logged in to access the REST API.', 'globalnews-media'), array('status' => 401));
    }
    return $result;
});

function globalnews_disable_xmlrpc() {
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
        wp_die(esc_html__('XML-RPC is disabled for security reasons.', 'globalnews-media'));
    }
}
add_action('init', 'globalnews_disable_xmlrpc');

function globalnews_remove_script_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'globalnews_remove_script_version', 15, 1);
add_filter('style_loader_src', 'globalnews_remove_script_version', 15, 1);

function globalnews_add_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
add_action('send_headers', 'globalnews_add_security_headers');

function globalnews_clean_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);
}
add_action('init', 'globalnews_clean_head');

function globalnews_disable_file_edit() {
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'globalnews_disable_file_edit');
