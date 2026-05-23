<?php
/**
 * Advertisement Functions
 */

function globalnews_header_ad() {
    $ad_code = get_theme_mod('globalnews_header_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-header">' . wp_kses_post($ad_code) . '</div>';
    }
}

function globalnews_sidebar_ad() {
    $ad_code = get_theme_mod('globalnews_sidebar_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-sidebar">' . wp_kses_post($ad_code) . '</div>';
    }
}

function globalnews_inline_ad() {
    $ad_code = get_theme_mod('globalnews_inline_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-inline">' . wp_kses_post($ad_code) . '</div>';
    }
}

function globalnews_inject_inline_ads($content) {
    if (is_single() && !is_admin()) {
        $ad_code = get_theme_mod('globalnews_inline_ad', '');
        if (!empty($ad_code)) {
            $ad = '<div class="ad-container ad-inline">' . wp_kses_post($ad_code) . '</div>';
            $paragraph_count = substr_count($content, '<p');
            if ($paragraph_count > 3) {
                $insert_after = ceil($paragraph_count / 2);
                $paragraphs = explode('</p>', $content);
                $new_content = '';
                $count = 0;
                foreach ($paragraphs as $index => $para) {
                    $count++;
                    $new_content .= $para . '</p>';
                    if ($count === $insert_after) {
                        $new_content .= $ad;
                    }
                }
                return $new_content;
            }
        }
    }
    return $content;
}
add_filter('the_content', 'globalnews_inject_inline_ads');
