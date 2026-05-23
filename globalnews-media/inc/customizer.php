<?php
/**
 * Theme Customizer
 */

function globalnews_customize_register($wp_customize) {
    $wp_customize->add_section('globalnews_colors', array(
        'title'    => esc_html__('GlobalNews Colors', 'globalnews-media'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('globalnews_primary_color', array(
        'default'           => '#e50914',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'globalnews_primary_color', array(
        'label'    => esc_html__('Primary Color (Red Accent)', 'globalnews-media'),
        'section'  => 'globalnews_colors',
    )));

    $wp_customize->add_setting('globalnews_header_bg', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'globalnews_header_bg', array(
        'label'    => esc_html__('Header Background', 'globalnews-media'),
        'section'  => 'globalnews_colors',
    )));

    $wp_customize->add_section('globalnews_header_options', array(
        'title'    => esc_html__('GlobalNews Header', 'globalnews-media'),
        'priority' => 35,
    ));

    $wp_customize->add_setting('globalnews_breaking_news_text', array(
        'default'           => esc_html__('Breaking News', 'globalnews-media'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('globalnews_breaking_news_text', array(
        'label'       => esc_html__('Breaking News Label', 'globalnews-media'),
        'section'     => 'globalnews_header_options',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('globalnews_ticker_speed', array(
        'default'           => 4000,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('globalnews_ticker_speed', array(
        'label'       => esc_html__('Ticker Speed (ms)', 'globalnews-media'),
        'section'     => 'globalnews_header_options',
        'type'        => 'number',
        'input_attrs' => array('min' => 2000, 'max' => 10000, 'step' => 500),
    ));

    $wp_customize->add_section('globalnews_ads', array(
        'title'    => esc_html__('GlobalNews Ads', 'globalnews-media'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('globalnews_header_ad', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('globalnews_header_ad', array(
        'label'       => esc_html__('Header Advertisement Code', 'globalnews-media'),
        'section'     => 'globalnews_header_options',
        'type'        => 'textarea',
        'description' => esc_html__('Paste ad code (e.g., Google AdSense) for the header banner.', 'globalnews-media'),
    ));

    $wp_customize->add_setting('globalnews_sidebar_ad', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('globalnews_sidebar_ad', array(
        'label'       => esc_html__('Sidebar Advertisement Code', 'globalnews-media'),
        'section'     => 'globalnews_ads',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('globalnews_inline_ad', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('globalnews_inline_ad', array(
        'label'       => esc_html__('Inline Article Advertisement Code', 'globalnews-media'),
        'section'     => 'globalnews_ads',
        'type'        => 'textarea',
    ));

    $wp_customize->add_section('globalnews_footer', array(
        'title'    => esc_html__('GlobalNews Footer', 'globalnews-media'),
        'priority' => 45,
    ));

    $wp_customize->add_setting('globalnews_footer_text', array(
        'default'           => sprintf(esc_html__('© %d GlobalNews Media. All rights reserved.', 'globalnews-media'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('globalnews_footer_text', array(
        'label'   => esc_html__('Footer Copyright Text', 'globalnews-media'),
        'section' => 'globalnews_footer',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting('globalnews_footer_logo', array(
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'globalnews_footer_logo', array(
        'label'    => esc_html__('Footer Logo', 'globalnews-media'),
        'section'  => 'globalnews_footer',
        'mime_type' => 'image',
    )));

    $wp_customize->add_section('globalnews_social', array(
        'title'    => esc_html__('GlobalNews Social Links', 'globalnews-media'),
        'priority' => 50,
    ));

    $socials = array('facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok', 'telegram');
    foreach ($socials as $social) {
        $wp_customize->add_setting("globalnews_social_{$social}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("globalnews_social_{$social}", array(
            'label'       => ucfirst($social),
            'section'     => 'globalnews_social',
            'type'        => 'url',
        ));
    }

    $wp_customize->add_section('globalnews_performance', array(
        'title'    => esc_html__('GlobalNews Performance', 'globalnews-media'),
        'priority' => 55,
    ));

    $wp_customize->add_setting('globalnews_lazy_load', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('globalnews_lazy_load', array(
        'label'   => esc_html__('Enable Lazy Loading for Images', 'globalnews-media'),
        'section' => 'globalnews_performance',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting('globalnews_minify_css', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('globalnews_minify_css', array(
        'label'   => esc_html__('Enable CSS Minification', 'globalnews-media'),
        'section' => 'globalnews_performance',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'globalnews_customize_register');
