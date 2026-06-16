<?php

function globalnews_header_ad() {
    $uploaded_ads = function_exists('globalnews_get_header_ads') ? globalnews_get_header_ads() : array();
    if (!empty($uploaded_ads)) {
        $ad = $uploaded_ads[array_rand($uploaded_ads)];
        echo '<div class="ad-container ad-header">';
        echo '<a href="' . esc_url($ad['link_url']) . '" target="_blank" rel="noopener noreferrer">';
        echo '<img src="' . esc_url($ad['image_url']) . '" alt="' . esc_attr($ad['title']) . '" style="max-width:100%;height:auto;display:block;">';
        echo '</a>';
        echo '</div>';
        return;
    }
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

function globalnews_sticky_mobile_ad() {
    if (!wp_is_mobile()) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_sticky_mobile_ad', '');
    if (empty($ad_code)) {
        return;
    }
    ?>
    <div id="sticky-mobile-ad" class="sticky-mobile-ad">
        <button class="sticky-ad-close" onclick="document.getElementById('sticky-mobile-ad').style.display='none'" aria-label="<?php esc_attr_e('Close ad', 'globalnews-media'); ?>">&times;</button>
        <div class="sticky-ad-content">
            <?php echo wp_kses_post($ad_code); ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'globalnews_sticky_mobile_ad', 1);

function globalnews_below_header_ad() {
    if (is_admin()) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_below_header_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-below-header">' . wp_kses_post($ad_code) . '</div>';
    }
}
add_action('globalnews_after_header', 'globalnews_below_header_ad');

function globalnews_before_footer_ad() {
    if (is_admin()) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_before_footer_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-before-footer">' . wp_kses_post($ad_code) . '</div>';
    }
}
add_action('globalnews_before_footer', 'globalnews_before_footer_ad');

function globalnews_article_top_ad() {
    if (!is_single()) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_article_top_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-article-top">' . wp_kses_post($ad_code) . '</div>';
    }
}
add_action('globalnews_before_article_content', 'globalnews_article_top_ad');

function globalnews_article_bottom_ad() {
    if (!is_single()) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_article_bottom_ad', '');
    if (!empty($ad_code)) {
        echo '<div class="ad-container ad-article-bottom">' . wp_kses_post($ad_code) . '</div>';
    }
}
add_action('globalnews_after_article_content', 'globalnews_article_bottom_ad');

function globalnews_interstitial_ad() {
    if (!wp_is_mobile()) {
        return;
    }
    if (rand(1, 100) > 5) {
        return;
    }
    $ad_code = get_theme_mod('globalnews_interstitial_ad', '');
    if (empty($ad_code)) {
        return;
    }
    ?>
    <div id="interstitial-ad" class="interstitial-ad-overlay" style="display:none;">
        <div class="interstitial-ad-container">
            <button class="interstitial-ad-close" onclick="document.getElementById('interstitial-ad').style.display='none'" aria-label="<?php esc_attr_e('Close', 'globalnews-media'); ?>">&times;</button>
            <?php echo wp_kses_post($ad_code); ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'globalnews_interstitial_ad', 2);

function globalnews_ads_customizer_section($wp_customize) {
    $wp_customize->add_section('globalnews_ads', array(
        'title' => __('Advertisement', 'globalnews-media'),
        'priority' => 200,
    ));
    $ad_positions = array(
        'globalnews_header_ad' => __('Header Ad Code', 'globalnews-media'),
        'globalnews_below_header_ad' => __('Below Header Ad Code', 'globalnews-media'),
        'globalnews_sidebar_ad' => __('Sidebar Ad Code', 'globalnews-media'),
        'globalnews_inline_ad' => __('In-Article Ad Code', 'globalnews-media'),
        'globalnews_article_top_ad' => __('Article Top Ad Code', 'globalnews-media'),
        'globalnews_article_bottom_ad' => __('Article Bottom Ad Code', 'globalnews-media'),
        'globalnews_before_footer_ad' => __('Before Footer Ad Code', 'globalnews-media'),
        'globalnews_sticky_mobile_ad' => __('Sticky Mobile Ad Code', 'globalnews-media'),
        'globalnews_interstitial_ad' => __('Interstitial Ad Code (Mobile)', 'globalnews-media'),
    );
    foreach ($ad_positions as $key => $label) {
        $wp_customize->add_setting($key, array('sanitize_callback' => 'wp_kses_post'));
        $wp_customize->add_control($key, array(
            'label' => $label,
            'section' => 'globalnews_ads',
            'type' => 'textarea',
            'description' => __('Paste your AdSense or ad network code here.', 'globalnews-media'),
        ));
    }
}
add_action('customize_register', 'globalnews_ads_customizer_section');
