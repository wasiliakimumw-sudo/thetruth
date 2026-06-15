<?php

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
    $async_scripts = array();
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
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
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">
    <?php
}
add_action('wp_head', 'globalnews_add_prefetch', 1);

function globalnews_preload_key_assets() {
    ?>
    <link rel="preload" as="font" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/fonts/inter-var.woff2'); ?>" crossorigin>
    <link rel="stylesheet" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/css/dark-mode.css'); ?>">
    <link rel="stylesheet" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/css/responsive.css'); ?>">
    <?php
    if (has_post_thumbnail() && is_single()) {
        $image = get_the_post_thumbnail_url(null, 'large');
        if ($image) {
            echo "\t" . '<link rel="preload" as="image" href="' . esc_url($image) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'globalnews_preload_key_assets', 0);

function globalnews_load_scripts_footer() {
    ?>
    <script src="<?php echo esc_url(GLOBALNEWS_URI . '/assets/js/main.js'); ?>"></script>
    <script src="<?php echo esc_url(GLOBALNEWS_URI . '/assets/js/dark-mode.js'); ?>"></script>
    <script src="<?php echo esc_url(GLOBALNEWS_URI . '/assets/js/breaking-news.js'); ?>"></script>
    <?php
}
add_action('wp_footer', 'globalnews_load_scripts_footer', 100);

function globalnews_add_cache_headers() {
    if (is_user_logged_in() || is_admin()) {
        return;
    }
    if (is_404()) {
        header('Cache-Control: no-cache, must-revalidate');
        return;
    }
    if (is_single()) {
        header('Cache-Control: public, max-age=3600, must-revalidate');
        header('X-Cacheable: yes');
    } elseif (is_front_page() || is_home()) {
        header('Cache-Control: public, max-age=600, must-revalidate');
    } elseif (is_page()) {
        header('Cache-Control: public, max-age=7200, must-revalidate');
    } elseif (is_category() || is_tag()) {
        header('Cache-Control: public, max-age=1800, must-revalidate');
    } else {
        header('Cache-Control: public, max-age=300, must-revalidate');
    }
    header('X-Content-Type-Options: nosniff');
}
add_action('send_headers', 'globalnews_add_cache_headers');

function globalnews_add_critical_css() {
    if (is_admin()) {
        return;
    }
    ?>
    <style>
    .site-header,.top-header-bar,.main-navigation{visibility:visible}.entry-content img{max-width:100%;height:auto}.screen-reader-text{clip:rect(1px,1px,1px,1px);position:absolute!important;height:1px;width:1px;overflow:hidden}.container{max-width:1200px;margin:0 auto;padding:0 20px}
    </style>
    <?php
}
add_action('wp_head', 'globalnews_add_critical_css', 0);

function globalnews_optimize_google_fonts() {
    $fonts_url = globalnews_fonts_url();
    if (!empty($fonts_url)) {
        echo "\t" . '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo "\t" . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo "\t" . '<link rel="stylesheet" href="' . esc_url($fonts_url) . '" media="print" onload="this.media=\'all\'">' . "\n";
        echo "\t" . '<noscript><link rel="stylesheet" href="' . esc_url($fonts_url) . '"></noscript>' . "\n";
    }
}
add_action('wp_head', 'globalnews_optimize_google_fonts', 0);

function globalnews_add_font_display() {
    echo "\t" . '<style>@font-face{font-family:"Inter";font-display:swap}@font-face{font-family:"Poppins";font-display:swap}</style>' . "\n";
}
add_action('wp_head', 'globalnews_add_font_display', 0);

function globalnews_add_webp_support() {
    if (function_exists('wp_check_filetype')) {
        add_filter('wp_get_attachment_image_attributes', function ($attr, $attachment) {
            $file = get_attached_file($attachment->ID);
            if ($file) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array(strtolower($ext), array('jpg', 'jpeg', 'png'))) {
                    $webp = str_replace('.' . $ext, '.webp', $file);
                    if (file_exists($webp)) {
                        $attr['data-webp'] = str_replace('.' . $ext, '.webp', $attr['src']);
                    }
                }
            }
            return $attr;
        }, 10, 2);
    }
}
add_action('init', 'globalnews_add_webp_support');
