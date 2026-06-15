<?php

function globalnews_register_landing_settings() {
    $settings = array(
        // Contact Info
        'globalnews_contact_address' => array(
            'label' => __('Address', 'globalnews-media'),
            'section' => 'contact',
            'type' => 'text',
            'default' => '123 News Street, Media City',
        ),
        'globalnews_contact_email' => array(
            'label' => __('Email', 'globalnews-media'),
            'section' => 'contact',
            'type' => 'email',
            'default' => 'info@globalnewsmedia.com',
        ),
        'globalnews_contact_phone' => array(
            'label' => __('Phone (display)', 'globalnews-media'),
            'section' => 'contact',
            'type' => 'text',
            'default' => '+1 (234) 567-890',
        ),
        'globalnews_contact_phone_tel' => array(
            'label' => __('Phone (tel: link)', 'globalnews-media'),
            'section' => 'contact',
            'type' => 'text',
            'default' => '+1234567890',
            'description' => __('Used for the click-to-call href attribute.', 'globalnews-media'),
        ),

        // Footer About
        'globalnews_about_text' => array(
            'label' => __('About Text', 'globalnews-media'),
            'section' => 'about',
            'type' => 'textarea',
            'default' => '%s is a leading international news organization delivering breaking news, in-depth analysis, and expert commentary on politics, business, technology, sports, entertainment, and world affairs.',
            'description' => __('Use %s to insert the site name.', 'globalnews-media'),
        ),

        // Footer Subscribe Bar
        'globalnews_footer_subscribe_title' => array(
            'label' => __('Title', 'globalnews-media'),
            'section' => 'footer_subscribe',
            'type' => 'text',
            'default' => 'Stay Updated',
        ),
        'globalnews_footer_subscribe_desc' => array(
            'label' => __('Description', 'globalnews-media'),
            'section' => 'footer_subscribe',
            'type' => 'text',
            'default' => 'Get the latest breaking news delivered to your inbox.',
        ),
        'globalnews_footer_subscribe_placeholder' => array(
            'label' => __('Email Placeholder', 'globalnews-media'),
            'section' => 'footer_subscribe',
            'type' => 'text',
            'default' => 'Enter your email',
        ),
        'globalnews_footer_subscribe_button' => array(
            'label' => __('Button Text', 'globalnews-media'),
            'section' => 'footer_subscribe',
            'type' => 'text',
            'default' => 'Subscribe',
        ),

        // Newsletter Main Section
        'globalnews_newsletter_badge' => array(
            'label' => __('Badge Text', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'Stay Informed',
        ),
        'globalnews_newsletter_title' => array(
            'label' => __('Title', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'Get the Latest News Delivered to Your Inbox',
        ),
        'globalnews_newsletter_desc' => array(
            'label' => __('Description', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'textarea',
            'default' => 'Join thousands of subscribers. Get breaking news, exclusive stories, and expert analysis straight to your inbox every morning.',
        ),
        'globalnews_newsletter_name_placeholder' => array(
            'label' => __('Name Placeholder', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'Your name',
        ),
        'globalnews_newsletter_email_placeholder' => array(
            'label' => __('Email Placeholder', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'Your email address',
        ),
        'globalnews_newsletter_button' => array(
            'label' => __('Button Text', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'Subscribe Now',
        ),
        'globalnews_newsletter_disclaimer' => array(
            'label' => __('Disclaimer', 'globalnews-media'),
            'section' => 'newsletter',
            'type' => 'text',
            'default' => 'No spam. Unsubscribe anytime.',
        ),

        // Sidebar Newsletter
        'globalnews_sidebar_newsletter_title' => array(
            'label' => __('Widget Title', 'globalnews-media'),
            'section' => 'sidebar_newsletter',
            'type' => 'text',
            'default' => 'Newsletter',
        ),
        'globalnews_sidebar_newsletter_desc' => array(
            'label' => __('Description', 'globalnews-media'),
            'section' => 'sidebar_newsletter',
            'type' => 'text',
            'default' => 'Get the latest news delivered to your inbox.',
        ),
        'globalnews_sidebar_newsletter_placeholder' => array(
            'label' => __('Email Placeholder', 'globalnews-media'),
            'section' => 'sidebar_newsletter',
            'type' => 'text',
            'default' => 'Your email',
        ),
        'globalnews_sidebar_newsletter_button' => array(
            'label' => __('Button Text', 'globalnews-media'),
            'section' => 'sidebar_newsletter',
            'type' => 'text',
            'default' => 'Subscribe',
        ),

        // Headline Ticker
        'globalnews_ticker_label' => array(
            'label' => __('Ticker Label', 'globalnews-media'),
            'section' => 'ticker',
            'type' => 'text',
            'default' => 'Headlines',
        ),

        // Media Gallery
        'globalnews_media_gallery_title' => array(
            'label' => __('Section Title', 'globalnews-media'),
            'section' => 'media_gallery',
            'type' => 'text',
            'default' => 'Videos & Audio',
        ),

        // Trending Sidebar
        'globalnews_trending_title' => array(
            'label' => __('Trending Widget Title', 'globalnews-media'),
            'section' => 'trending',
            'type' => 'text',
            'default' => 'Trending Now',
        ),
        'globalnews_follow_title' => array(
            'label' => __('Follow Us Widget Title', 'globalnews-media'),
            'section' => 'trending',
            'type' => 'text',
            'default' => 'Follow Us',
        ),

        // SEO Schema
        'globalnews_schema_telephone' => array(
            'label' => __('Schema Telephone', 'globalnews-media'),
            'section' => 'schema',
            'type' => 'text',
            'default' => '+1-234-567-890',
            'description' => __('Used in Organization schema markup.', 'globalnews-media'),
        ),
        'globalnews_schema_founding_date' => array(
            'label' => __('Schema Founding Date', 'globalnews-media'),
            'section' => 'schema',
            'type' => 'text',
            'default' => '2024',
        ),
        'globalnews_schema_employees_min' => array(
            'label' => __('Employees (min)', 'globalnews-media'),
            'section' => 'schema',
            'type' => 'number',
            'default' => 10,
        ),
        'globalnews_schema_employees_max' => array(
            'label' => __('Employees (max)', 'globalnews-media'),
            'section' => 'schema',
            'type' => 'number',
            'default' => 50,
        ),
    );

    foreach ($settings as $key => $args) {
        register_setting('globalnews_landing_settings', $key, array(
            'sanitize_callback' => $args['type'] === 'textarea' ? 'wp_kses_post' : 'sanitize_text_field',
            'default' => $args['default'],
        ));
    }

    return $settings;
}

function globalnews_landing_settings_page() {
    $settings = globalnews_register_landing_settings();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php esc_html_e('Manage your frontend landing page content — all text except news stories.', 'globalnews-media'); ?></p>

        <form action="options.php" method="post">
            <?php settings_fields('globalnews_landing_settings'); ?>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Contact Info', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Displayed in the footer contact column.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'contact'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Footer About Text', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Displayed in the footer brand column.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'about'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Footer Subscribe Bar', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Displayed above the main footer.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'footer_subscribe'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Newsletter Section (Main)', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Displayed at the bottom of the front page above the footer.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'newsletter'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Sidebar Newsletter', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Displayed in the front page sidebar.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'sidebar_newsletter'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Headline Ticker', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('The label shown before the scrolling headlines.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'ticker'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Media Gallery', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Section label for videos and audio.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'media_gallery'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('Trending / Follow Sidebar', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Widget titles in the front page sidebar.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'trending'); ?>
                </table>
            </div>

            <div class="gn-settings-card">
                <h2 class="gn-section-heading"><?php esc_html_e('SEO Schema', 'globalnews-media'); ?></h2>
                <p class="description"><?php esc_html_e('Used in Organization structured data.', 'globalnews-media'); ?></p>
                <table class="form-table">
                    <?php globalnews_render_fields($settings, 'schema'); ?>
                </table>
            </div>

            <p class="submit">
                <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'globalnews-media'); ?></button>
            </p>
        </form>
    </div>

    <style>
    .gn-settings-card {
        background: #fff;
        border: 1px solid #c3c4c7;
        padding: 20px 24px;
        margin-bottom: 20px;
        max-width: 900px;
        border-radius: 4px;
    }
    .gn-settings-card h2.gn-section-heading {
        margin-top: 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #e50914;
        font-size: 16px;
    }
    .gn-settings-card > p.description {
        margin-bottom: 16px;
        color: #666;
    }
    .gn-settings-card .form-table th {
        width: 180px;
        padding: 12px 10px 12px 0;
    }
    .gn-settings-card .form-table td {
        padding: 12px 0;
    }
    .gn-settings-card .form-table textarea {
        width: 100%;
        min-height: 80px;
    }
    .gn-settings-card .form-table input.regular-text {
        width: 100%;
    }
    </style>
    <?php
}

function globalnews_render_fields($settings, $section) {
    foreach ($settings as $key => $args) {
        if ($args['section'] !== $section) continue;
        $value = get_option($key, $args['default']);
        ?>
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($args['label']); ?></label></th>
            <td>
                <?php if ($args['type'] === 'textarea'): ?>
                    <textarea id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" class="large-text"><?php echo esc_textarea($value); ?></textarea>
                <?php elseif ($args['type'] === 'number'): ?>
                    <input type="number" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="small-text">
                <?php else: ?>
                    <input type="<?php echo esc_attr($args['type']); ?>" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
                <?php endif; ?>
                <?php if (!empty($args['description'])): ?>
                    <p class="description"><?php echo esc_html($args['description']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
}

function globalnews_register_landing_page() {
    add_submenu_page(
        'options-general.php',
        esc_html__('Landing Page Settings', 'globalnews-media'),
        esc_html__('Landing Page', 'globalnews-media'),
        'manage_options',
        'globalnews-landing-settings',
        'globalnews_landing_settings_page'
    );
}
add_action('admin_menu', 'globalnews_register_landing_page');

function globalnews_get_landing_setting($key, $default = '') {
    $settings = array();
    globalnews_register_landing_settings();
    $value = get_option($key);
    if ($value === false) {
        $settings = globalnews_register_landing_settings();
        $value = isset($settings[$key]) ? $settings[$key]['default'] : $default;
    }
    return $value;
}
