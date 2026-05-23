<?php
/**
 * Performance Optimization Functions
 */

function globalnews_add_lazy_loading($content) {
    if (!get_theme_mod('globalnews_lazy_load', true)) {
        return $content;
    }
    if (is_feed() || is_admin()) {
        return $content;
    }
    if (false !== strpos($content, 'data-src')) {
        return $content;
    }
    $content = preg_replace_callback('/<img\s+([^>]*?)src=["\']([^"\']+?)["\']([^>]*?)>/i', function ($matches) {
        $attrs = $matches[1] . $matches[3];
        if (preg_match('/class=["\'][^"\']*skip-lazy[^"\']*["\']/i', $attrs)) {
            return $matches[0];
        }
        if (preg_match('/src=["\']data:/i', $matches[0])) {
            return $matches[0];
        }
        return '<img ' . $attrs . ' src="data:image/svg+xml,%3Csvg%20xmlns=%22http://www.w3.org/2000/svg%22%20viewBox=%220%200%20600%20400%22%3E%3C/svg%3E" data-src="' . $matches[2] . '" loading="lazy">';
    }, $content);

    return $content;
}
add_filter('the_content', 'globalnews_add_lazy_loading');
add_filter('post_thumbnail_html', 'globalnews_add_lazy_loading');

function globalnews_defer_scripts($tag, $handle) {
    $defer_scripts = array('globalnews-main', 'globalnews-dark-mode', 'globalnews-breaking');
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'globalnews_defer_scripts', 10, 2);

function globalnews_remove_emoji_scripts() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('init', 'globalnews_remove_emoji_scripts');

add_filter('jpeg_quality', function () { return 82; });
add_filter('wp_editor_set_quality', function () { return 82; });

function globalnews_remove_wp_block_library_css() {
    if (!is_single() && !is_page()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
}
add_action('wp_enqueue_scripts', 'globalnews_remove_wp_block_library_css', 100);

function globalnews_disable_self_pingback(&$links) {
    foreach ($links as $l => $link) {
        if (0 === strpos($link, home_url())) {
            unset($links[$l]);
        }
    }
}
add_action('pre_ping', 'globalnews_disable_self_pingback');

function globalnews_add_prefetch() {
    ?>
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="dns-prefetch" href="//www.google-analytics.com">
    <?php
}
add_action('wp_head', 'globalnews_add_prefetch', 1);
