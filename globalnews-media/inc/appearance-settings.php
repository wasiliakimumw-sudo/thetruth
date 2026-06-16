<?php

function globalnews_register_appearance_page() {
    add_submenu_page(
        'options-general.php',
        esc_html__('Appearance Settings', 'globalnews-media'),
        esc_html__('Appearance', 'globalnews-media'),
        'manage_options',
        'globalnews-appearance-settings',
        'globalnews_appearance_page_html'
    );
}
add_action('admin_menu', 'globalnews_register_appearance_page');

function globalnews_register_appearance_settings() {
    register_setting('globalnews_appearance_settings', 'globalnews_color_scheme');
    register_setting('globalnews_appearance_settings', 'globalnews_primary_color');
    register_setting('globalnews_appearance_settings', 'globalnews_header_bg');
}
add_action('admin_init', 'globalnews_register_appearance_settings');

function globalnews_ajax_save_appearance_settings() {
    if (!wp_verify_nonce($_POST['nonce'], 'globalnews_admin_nonce')) {
        wp_send_json_error('Invalid nonce.');
    }
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions.');
    }

    $scheme = sanitize_text_field($_POST['color_scheme']);
    $primary = sanitize_hex_color($_POST['primary_color']);
    $header_bg = sanitize_hex_color($_POST['header_bg']);
    if (!$primary) $primary = '#e50914';
    if (!$header_bg) $header_bg = '#e50914';

    update_option('globalnews_color_scheme', $scheme);
    update_option('globalnews_primary_color', $primary);
    update_option('globalnews_header_bg', $header_bg);

    set_theme_mod('globalnews_primary_color', $primary);
    set_theme_mod('globalnews_header_bg', $header_bg);

    wp_send_json_success(array(
        'color_scheme'  => $scheme,
        'primary_color' => $primary,
        'header_bg'     => $header_bg,
    ));
}
add_action('wp_ajax_globalnews_save_appearance_settings', 'globalnews_ajax_save_appearance_settings');

function globalnews_get_color_schemes() {
    return array(
        'default'  => array('label' => 'Default Red',   'primary' => '#e50914', 'header' => '#e50914'),
        'blue'     => array('label' => 'Blue',           'primary' => '#1a73e8', 'header' => '#1a73e8'),
        'green'    => array('label' => 'Green',          'primary' => '#0d9e3e', 'header' => '#0d9e3e'),
        'orange'   => array('label' => 'Orange',         'primary' => '#ff6d00', 'header' => '#ff6d00'),
        'purple'   => array('label' => 'Purple',         'primary' => '#7c4dff', 'header' => '#7c4dff'),
        'darkred'  => array('label' => 'Dark Red',       'primary' => '#b71c1c', 'header' => '#000000'),
        'custom'   => array('label' => 'Custom',         'primary' => '',        'header' => ''),
    );
}

function globalnews_appearance_page_html() {
    $scheme    = get_option('globalnews_color_scheme', 'default');
    $primary   = get_option('globalnews_primary_color', '#e50914');
    $header_bg = get_option('globalnews_header_bg', '#e50914');
    $schemes   = globalnews_get_color_schemes();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p><?php esc_html_e('Customize your site colours and theme appearance.', 'globalnews-media'); ?></p>

        <div class="gn-settings-card">
            <form id="gn-appearance-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Colour Scheme', 'globalnews-media'); ?></th>
                        <td>
                            <div class="gn-scheme-grid">
                                <?php foreach ($schemes as $key => $s): ?>
                                    <label class="gn-scheme-option <?php echo $scheme === $key ? 'selected' : ''; ?>">
                                        <input type="radio" name="color_scheme" value="<?php echo esc_attr($key); ?>" <?php checked($scheme, $key); ?>>
                                        <span class="gn-scheme-swatch" style="background:<?php echo esc_attr($s['primary'] ?: '#e50914'); ?>;"></span>
                                        <span class="gn-scheme-label"><?php echo esc_html($s['label']); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <tr class="gn-custom-row" style="<?php echo $scheme !== 'custom' ? 'display:none;' : ''; ?>">
                        <th scope="row"><?php esc_html_e('Custom Colors', 'globalnews-media'); ?></th>
                        <td>
                            <p>
                                <label><?php esc_html_e('Primary Color', 'globalnews-media'); ?><br>
                                    <input type="text" id="gn-primary-color" class="gn-color-picker" value="<?php echo esc_attr($primary); ?>" data-default="#e50914">
                                </label>
                            </p>
                            <p>
                                <label><?php esc_html_e('Header Background', 'globalnews-media'); ?><br>
                                    <input type="text" id="gn-header-bg" class="gn-color-picker" value="<?php echo esc_attr($header_bg); ?>" data-default="#e50914">
                                </label>
                            </p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary" id="gn-save-appearance"><?php esc_html_e('Save Settings', 'globalnews-media'); ?></button>
                    <span class="spinner" id="gn-appearance-spinner" style="float:none;margin-left:8px;"></span>
                    <span id="gn-appearance-message" style="margin-left:8px;"></span>
                </p>
            </form>
        </div>

        <div class="gn-preview-section">
            <h2><?php esc_html_e('Preview', 'globalnews-media'); ?></h2>
            <div class="gn-preview-card">
                <div class="gn-preview-header" style="background:<?php echo esc_attr($header_bg); ?>;">
                    <span class="gn-preview-logo">THETRUTH</span>
                </div>
                <div class="gn-preview-body">
                    <span class="gn-preview-badge" style="background:<?php echo esc_attr($primary); ?>;"><?php esc_html_e('Breaking', 'globalnews-media'); ?></span>
                    <span class="gn-preview-text"><?php esc_html_e('Sample headline with primary colour accent.', 'globalnews-media'); ?></span>
                </div>
            </div>
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
                <p><?php esc_html_e('Your appearance settings have been updated successfully.', 'globalnews-media'); ?></p>
            </div>
            <div class="gn-modal-footer">
                <button type="button" class="button button-primary gn-modal-close-btn"><?php esc_html_e('Done', 'globalnews-media'); ?></button>
            </div>
        </div>
    </div>

    <style>
        .gn-scheme-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .gn-scheme-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 16px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
            min-width: 90px;
        }
        .gn-scheme-option.selected,
        .gn-scheme-option:hover {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
        }
        .gn-scheme-option input[type="radio"] {
            display: none;
        }
        .gn-scheme-swatch {
            display: block;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-bottom: 6px;
            border: 1px solid rgba(0,0,0,.1);
        }
        .gn-scheme-label {
            font-size: 12px;
            font-weight: 500;
        }
        .gn-preview-section {
            margin-top: 30px;
        }
        .gn-preview-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            max-width: 360px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }
        .gn-preview-header {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            transition: background .3s;
        }
        .gn-preview-logo {
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .gn-preview-body {
            padding: 16px 20px;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .gn-preview-badge {
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            text-transform: uppercase;
            transition: background .3s;
        }
        .gn-preview-text {
            font-size: 13px;
            color: #333;
        }
    </style>

    <script>
    jQuery(function($) {
        var $form = $('#gn-appearance-form');
        var $submit = $('#gn-save-appearance');
        var $spinner = $('#gn-appearance-spinner');
        var $message = $('#gn-appearance-message');
        var $schemeRadios = $('input[name="color_scheme"]');
        var $customRow = $('.gn-custom-row');
        var $primaryInput = $('#gn-primary-color');
        var $headerInput = $('#gn-header-bg');
        var $previewHeader = $('.gn-preview-header');
        var $previewBadge = $('.gn-preview-badge');

        function getSchemeColors(scheme) {
            var schemes = {};
            $schemeRadios.each(function() {
                var val = $(this).val();
                var $opt = $(this).closest('.gn-scheme-option');
                var swatch = $opt.find('.gn-scheme-swatch').css('background-color');
                if (val === 'custom') {
                    schemes[val] = { primary: $primaryInput.val(), header: $headerInput.val() };
                } else {
                    schemes[val] = { primary: swatch, header: swatch };
                }
            });
            if (scheme === 'darkred') {
                schemes[scheme] = { primary: '#b71c1c', header: '#000000' };
            }
            return schemes;
        }

        function updatePreview(scheme) {
            var colors;
            if (scheme === 'custom') {
                colors = { primary: $primaryInput.val() || '#e50914', header: $headerInput.val() || '#e50914' };
            } else if (scheme === 'darkred') {
                colors = { primary: '#b71c1c', header: '#000000' };
            } else if (scheme === 'blue') {
                colors = { primary: '#1a73e8', header: '#1a73e8' };
            } else if (scheme === 'green') {
                colors = { primary: '#0d9e3e', header: '#0d9e3e' };
            } else if (scheme === 'orange') {
                colors = { primary: '#ff6d00', header: '#ff6d00' };
            } else if (scheme === 'purple') {
                colors = { primary: '#7c4dff', header: '#7c4dff' };
            } else {
                colors = { primary: '#e50914', header: '#e50914' };
            }
            $previewHeader.css('background', colors.header);
            $previewBadge.css('background', colors.primary);
        }

        $schemeRadios.on('change', function() {
            var val = $(this).val();
            $('.gn-scheme-option').removeClass('selected');
            $(this).closest('.gn-scheme-option').addClass('selected');
            $customRow.toggle(val === 'custom');
            updatePreview(val);
        });

        $primaryInput.add($headerInput).on('input', function() {
            if ($('input[name="color_scheme"]:checked').val() === 'custom') {
                updatePreview('custom');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            $spinner.addClass('is-active');
            $message.text('').css('color', '');
            $submit.prop('disabled', true);

            var scheme = $('input[name="color_scheme"]:checked').val();
            var primary = $primaryInput.val();
            var header = $headerInput.val();
            $.post(ajaxurl, {
                action: 'globalnews_save_appearance_settings',
                nonce: '<?php echo esc_js(wp_create_nonce('globalnews_admin_nonce')); ?>',
                color_scheme: scheme,
                primary_color: primary,
                header_bg: header
            }).done(function(res) {
                if (res.success) {
                    $('#gn-success-modal').fadeIn(200);
                    $message.text('Settings saved.').css('color', '#10b981');
                } else {
                    $message.text(res.data || 'Error saving settings.').css('color', '#d63638');
                }
            }).fail(function() {
                $message.text('Server error.').css('color', '#d63638');
            }).always(function() {
                $spinner.removeClass('is-active');
                $submit.prop('disabled', false);
            });
        });

        $('.gn-modal-close, .gn-modal-overlay, .gn-modal-close-btn').on('click', function() {
            $('#gn-success-modal').fadeOut(200);
        });
    });
    </script>
    <?php
}

function globalnews_inject_theme_colors() {
    $primary   = get_theme_mod('globalnews_primary_color', '#e50914');
    $header_bg = get_theme_mod('globalnews_header_bg', '#e50914');

    if (!$primary) $primary = '#e50914';
    if (!$header_bg) $header_bg = '#e50914';

    $css = ':root{'
        . '--gm-primary:' . esc_attr($primary) . ';'
        . '--gm-primary-hover:' . esc_attr($primary) . ';'
        . '--gm-primary-dark:' . esc_attr($primary) . ';'
        . '--gm-header-bg:' . esc_attr($header_bg) . ';'
        . '}';

    wp_add_inline_style('globalnews-style', $css);
}
add_action('wp_enqueue_scripts', 'globalnews_inject_theme_colors', 20);

function globalnews_admin_appearance_enqueue($hook) {
    if ('settings_page_globalnews-appearance-settings' === $hook) {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_add_inline_script('wp-color-picker', '
            jQuery(function($) {
                $(".gn-color-picker").wpColorPicker();
            });
        ');
        wp_enqueue_script('globalnews-admin-settings', GLOBALNEWS_URI . '/assets/js/admin-settings.js', array('jquery'), GLOBALNEWS_VERSION, true);
        wp_localize_script('globalnews-admin-settings', 'globalnewsAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('globalnews_admin_nonce'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'globalnews_admin_appearance_enqueue');
