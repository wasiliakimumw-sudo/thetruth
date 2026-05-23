<?php

function globalnews_ajax_save_site_settings() {
    if (!wp_verify_nonce($_POST['nonce'], 'globalnews_admin_nonce')) {
        wp_send_json_error('Invalid nonce.');
    }
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions.');
    }

    $site_name = sanitize_text_field($_POST['site_name']);
    $site_tagline = sanitize_text_field($_POST['site_tagline']);
    $site_logo = absint($_POST['site_logo']);

    update_option('blogname', $site_name);
    update_option('blogdescription', $site_tagline);

    if ($site_logo) {
        set_theme_mod('custom_logo', $site_logo);
    } else {
        remove_theme_mod('custom_logo');
    }

    wp_send_json_success(array(
        'site_name'    => $site_name,
        'site_tagline' => $site_tagline,
        'site_logo'    => $site_logo,
    ));
}
add_action('wp_ajax_globalnews_save_site_settings', 'globalnews_ajax_save_site_settings');

function globalnews_register_settings_page() {
    add_submenu_page(
        'options-general.php',
        esc_html__('GlobalNews Site Settings', 'globalnews-media'),
        esc_html__('Site Settings', 'globalnews-media'),
        'manage_options',
        'globalnews-site-settings',
        'globalnews_settings_page_html'
    );
}
add_action('admin_menu', 'globalnews_register_settings_page');

function globalnews_register_settings() {
    register_setting('globalnews_site_settings', 'globalnews_site_name');
    register_setting('globalnews_site_settings', 'globalnews_site_logo');
    register_setting('globalnews_site_settings', 'globalnews_site_tagline');
}
add_action('admin_init', 'globalnews_register_settings');

function globalnews_settings_page_html() {
    $site_name = get_option('globalnews_site_name', get_bloginfo('name'));
    $site_logo = get_option('globalnews_site_logo', get_theme_mod('custom_logo'));
    $site_tagline = get_option('globalnews_site_tagline', get_bloginfo('description'));
    $admin_email = get_option('admin_email');
    $site_url = get_site_url();
    $logo_url = $site_logo ? wp_get_attachment_url($site_logo) : '';
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php esc_html_e('Manage your site identity settings from one place.', 'globalnews-media'); ?></p>

        <div class="gn-settings-card">
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Site Name', 'globalnews-media'); ?></th>
                    <td>
                        <input type="text" id="gn-site-name" class="regular-text" value="<?php echo esc_attr($site_name); ?>">
                        <p class="description"><?php esc_html_e('The name of your website.', 'globalnews-media'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Tagline', 'globalnews-media'); ?></th>
                    <td>
                        <input type="text" id="gn-site-tagline" class="regular-text" value="<?php echo esc_attr($site_tagline); ?>">
                        <p class="description"><?php esc_html_e('A short description of your site.', 'globalnews-media'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Site Logo', 'globalnews-media'); ?></th>
                    <td>
                        <div class="gn-logo-preview" id="gn-logo-preview" style="<?php echo $logo_url ? '' : 'display:none;'; ?>">
                            <img id="gn-logo-img" src="<?php echo esc_url($logo_url); ?>" style="max-width:200px;max-height:80px;">
                        </div>
                        <button type="button" class="button" id="gn-upload-logo"><?php esc_html_e('Select Logo', 'globalnews-media'); ?></button>
                        <button type="button" class="button" id="gn-remove-logo" style="<?php echo $logo_url ? '' : 'display:none;'; ?>"><?php esc_html_e('Remove Logo', 'globalnews-media'); ?></button>
                        <input type="hidden" id="gn-logo-id" value="<?php echo esc_attr($site_logo); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Admin Email', 'globalnews-media'); ?></th>
                    <td>
                        <input type="email" class="regular-text" value="<?php echo esc_attr($admin_email); ?>" readonly disabled>
                        <p class="description"><?php esc_html_e('Change in Settings > General.', 'globalnews-media'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Site URL', 'globalnews-media'); ?></th>
                    <td>
                        <input type="text" class="regular-text" value="<?php echo esc_attr($site_url); ?>" readonly disabled>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button type="button" class="button button-primary" id="gn-save-settings"><?php esc_html_e('Save Settings', 'globalnews-media'); ?></button>
                <span class="spinner" id="gn-settings-spinner" style="float:none;margin-left:8px;"></span>
                <span id="gn-settings-message" style="margin-left:8px;"></span>
            </p>
        </div>
    </div>

    <div id="gn-success-modal" class="gn-modal" style="display:none;">
        <div class="gn-modal-overlay"></div>
        <div class="gn-modal-content">
            <div class="gn-modal-header">
                <h2><?php esc_html_e('Settings Saved', 'globalnews-media'); ?></h2>
                <button type="button" class="gn-modal-close">&times;</button>
            </div>
            <div class="gn-modal-body">
                <div class="gn-success-icon">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <p><?php esc_html_e('Your site settings have been updated successfully.', 'globalnews-media'); ?></p>
                <table class="gn-summary-table">
                    <tr><td><?php esc_html_e('Site Name', 'globalnews-media'); ?></td><td id="gn-summary-name"></td></tr>
                    <tr><td><?php esc_html_e('Tagline', 'globalnews-media'); ?></td><td id="gn-summary-tagline"></td></tr>
                    <tr><td><?php esc_html_e('Logo', 'globalnews-media'); ?></td><td id="gn-summary-logo"></td></tr>
                </table>
            </div>
            <div class="gn-modal-footer">
                <button type="button" class="button button-primary gn-modal-close-btn"><?php esc_html_e('Done', 'globalnews-media'); ?></button>
            </div>
        </div>
    </div>
    <?php
}
