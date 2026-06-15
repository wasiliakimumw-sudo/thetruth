<?php

function globalnews_ads_admin_menu() {
    add_menu_page(
        __('Ads', 'globalnews-media'),
        __('Ads', 'globalnews-media'),
        'manage_options',
        'globalnews-ads',
        'globalnews_ads_admin_page',
        'dashicons-money-alt',
        25
    );
    add_submenu_page(
        'globalnews-ads',
        __('Header Ads', 'globalnews-media'),
        __('Header Ads', 'globalnews-media'),
        'manage_options',
        'globalnews-header-ads',
        'globalnews_header_ads_admin_page'
    );
    remove_submenu_page('globalnews-ads', 'globalnews-ads');
}
add_action('admin_menu', 'globalnews_ads_admin_menu');

function globalnews_ads_admin_page() {
    wp_safe_redirect(admin_url('admin.php?page=globalnews-header-ads'));
    exit;
}

function globalnews_get_header_ads() {
    return get_option('globalnews_header_ads', array());
}

function globalnews_header_ads_admin_page() {
    $ads = globalnews_get_header_ads();

    if (!empty($_GET['ad_deleted'])) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Ad deleted.', 'globalnews-media') . '</p></div>';
    }
    if (!empty($_GET['ad_added'])) {
        $count = !empty($_GET['ad_count']) ? intval($_GET['ad_count']) : 1;
        $msg = $count > 1 ? sprintf(__('%d ads uploaded successfully.', 'globalnews-media'), $count) : __('Ad uploaded successfully.', 'globalnews-media');
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($msg) . '</p></div>';
    }
    if (!empty($_GET['upload_error'])) {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html(sanitize_text_field($_GET['upload_error'])) . '</p></div>';
    }

    $max_size = wp_max_upload_size();
    $max_size_mb = $max_size ? floor($max_size / MB_IN_BYTES) : 0;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Header Ads', 'globalnews-media'); ?></h1>
        <p><?php esc_html_e('Upload banner images for the header ad slot.', 'globalnews-media'); ?></p>

        <hr>
        <h2><?php esc_html_e('Add New Ads', 'globalnews-media'); ?></h2>
        <p><small><?php printf(esc_html__('Max file size: %s MB per image. Recommended: 728x90 or 468x60 banner.', 'globalnews-media'), $max_size_mb); ?></small></p>
        <form method="post" enctype="multipart/form-data" id="gn-ad-form">
            <?php wp_nonce_field('globalnews_upload_header_ad', 'globalnews_header_ad_nonce'); ?>
            <div id="gn-ad-rows">
                <div class="gn-ad-row" style="background:#f6f7f7;padding:12px;margin-bottom:10px;border:1px solid #c3c4c7;border-radius:4px;">
                    <table class="form-table" style="margin:0;">
                        <tr>
                            <th scope="row" style="width:100px;"><label><?php esc_html_e('Label', 'globalnews-media'); ?></label></th>
                            <td><input type="text" name="ad_title[]" class="regular-text" placeholder="e.g. Sponsor A" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php esc_html_e('Link URL', 'globalnews-media'); ?></label></th>
                            <td><input type="url" name="ad_link[]" class="regular-text" placeholder="https://example.com" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label><?php esc_html_e('Image', 'globalnews-media'); ?></label></th>
                            <td>
                                <input type="file" name="ad_image[]" accept="image/png,image/jpeg,image/gif,image/webp" required
                                       style="padding:4px 0;">
                                <p class="description gn-ad-file-info" style="margin:2px 0 0;"></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <p>
                <button type="button" class="button" id="gn-add-ad-row">+ Add Another Ad</button>
            </p>
            <p class="submit">
                <button type="submit" class="button button-primary" id="gn-ad-upload-btn">
                    <?php esc_html_e('Upload All Ads', 'globalnews-media'); ?>
                </button>
                <span class="spinner" id="gn-ad-upload-spinner" style="float:none;margin-left:8px;"></span>
            </p>
        </form>

        <?php if (!empty($ads)) : ?>
        <hr>
        <h2><?php esc_html_e('Existing Ads', 'globalnews-media'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width:120px;"><?php esc_html_e('Banner', 'globalnews-media'); ?></th>
                    <th><?php esc_html_e('Label', 'globalnews-media'); ?></th>
                    <th><?php esc_html_e('Link URL', 'globalnews-media'); ?></th>
                    <th style="width:100px;"><?php esc_html_e('Added', 'globalnews-media'); ?></th>
                    <th style="width:80px;"><?php esc_html_e('Actions', 'globalnews-media'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ads as $key => $ad) : ?>
                <tr>
                    <td>
                        <img src="<?php echo esc_url($ad['image_url']); ?>"
                             alt="<?php echo esc_attr($ad['title']); ?>"
                             style="max-width:100px;height:auto;display:block;">
                    </td>
                    <td><?php echo esc_html($ad['title']); ?></td>
                    <td><a href="<?php echo esc_url($ad['link_url']); ?>" target="_blank"><?php echo esc_html($ad['link_url']); ?></a></td>
                    <td><?php echo esc_html($ad['added']); ?></td>
                    <td>
                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=globalnews_delete_header_ad&ad_key=' . $key), 'delete_header_ad_' . $key)); ?>"
                           class="button button-small button-link-delete"
                           onclick="return confirm('<?php esc_attr_e('Delete this ad?', 'globalnews-media'); ?>');">
                            <?php esc_html_e('Delete', 'globalnews-media'); ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <script>
    jQuery(function($) {
        function addRow() {
            var html = '<div class="gn-ad-row" style="background:#f6f7f7;padding:12px;margin-bottom:10px;border:1px solid #c3c4c7;border-radius:4px;">';
            html += '<table class="form-table" style="margin:0;">';
            html += '<tr><th scope="row" style="width:100px;"><label>Label</label></th>';
            html += '<td><input type="text" name="ad_title[]" class="regular-text" placeholder="e.g. Sponsor A"></td></tr>';
            html += '<tr><th scope="row"><label>Link URL</label></th>';
            html += '<td><input type="url" name="ad_link[]" class="regular-text" placeholder="https://example.com"></td></tr>';
            html += '<tr><th scope="row"><label>Image</label></th>';
            html += '<td><input type="file" name="ad_image[]" accept="image/png,image/jpeg,image/gif,image/webp" style="padding:4px 0;">';
            html += '<p class="description gn-ad-file-info" style="margin:2px 0 0;"></p></td></tr>';
            html += '</table>';
            html += '<p style="margin:4px 0 0;"><button type="button" class="button button-small gn-remove-ad-row" style="color:#b32d2e;">Remove</button></p>';
            html += '</div>';
            var $row = $(html).appendTo('#gn-ad-rows');
            $row.find('input[type="file"]').on('change', function() {
                var file = this.files[0];
                var $info = $(this).closest('td').find('.gn-ad-file-info');
                if (file) {
                    var size = (file.size / 1048576).toFixed(1);
                    $info.text(file.name + ' (' + size + ' MB)');
                } else {
                    $info.text('');
                }
            });
        }

        $('#gn-add-ad-row').on('click', addRow);

        $('#gn-ad-rows').on('click', '.gn-remove-ad-row', function() {
            $(this).closest('.gn-ad-row').remove();
        });

        $('#gn-ad-upload-btn').on('click', function(e) {
            var files = $('#gn-ad-rows input[type="file"]');
            var allFilled = true;
            files.each(function() {
                if (!this.files || !this.files[0]) {
                    allFilled = false;
                    return false;
                }
            });
            if (!allFilled) {
                alert('Please select an image for each ad row.');
                e.preventDefault();
                return false;
            }
            $(this).prop('disabled', true).text('Uploading...');
            $('#gn-ad-upload-spinner').addClass('is-active');
            return true;
        });
    });
    </script>
    <?php
}

function globalnews_handle_header_ad_upload() {
    if (empty($_POST['globalnews_header_ad_nonce']) || !wp_verify_nonce($_POST['globalnews_header_ad_nonce'], 'globalnews_upload_header_ad')) {
        return;
    }
    if (!current_user_can('manage_options')) {
        wp_die(__('Insufficient permissions.', 'globalnews-media'));
    }

    $titles = $_POST['ad_title'] ?? array();
    $links  = $_POST['ad_link'] ?? array();
    $files  = $_FILES['ad_image'] ?? array();

    if (empty($titles) || empty($links) || empty($files['name'][0])) {
        globalnews_header_ad_redirect_error(__('Please fill in all fields and select at least one image.', 'globalnews-media'));
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $ads = globalnews_get_header_ads();
    $uploaded = 0;

    foreach ($files['name'] as $i => $name) {
        if (empty($name) || empty($titles[$i]) || empty($links[$i])) {
            continue;
        }

        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $file = array(
            'name'     => sanitize_file_name($files['name'][$i]),
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i],
        );

        $attachment_id = media_handle_sideload($file, 0);
        if (is_wp_error($attachment_id)) {
            continue;
        }

        $image_url = wp_get_attachment_url($attachment_id);
        if (!$image_url) {
            wp_delete_attachment($attachment_id, true);
            continue;
        }

        $ads[] = array(
            'image_id'  => $attachment_id,
            'image_url' => $image_url,
            'link_url'  => esc_url_raw($links[$i]),
            'title'     => sanitize_text_field($titles[$i]),
            'added'     => current_time('Y-m-d'),
        );
        $uploaded++;
    }

    update_option('globalnews_header_ads', $ads);

    if ($uploaded === 0) {
        globalnews_header_ad_redirect_error(__('No ads were uploaded. Please check your files and try again.', 'globalnews-media'));
    }

    $count = $uploaded > 1 ? sprintf(__('%d ads uploaded successfully.', 'globalnews-media'), $uploaded) : __('Ad uploaded successfully.', 'globalnews-media');
    wp_safe_redirect(admin_url('admin.php?page=globalnews-header-ads&ad_added=1&ad_count=' . $uploaded));
    exit;
}
add_action('admin_init', 'globalnews_handle_header_ad_upload');

function globalnews_delete_header_ad() {
    if (empty($_GET['ad_key']) || empty($_GET['_wpnonce'])) {
        return;
    }
    $key = sanitize_text_field($_GET['ad_key']);
    if (!wp_verify_nonce($_GET['_wpnonce'], 'delete_header_ad_' . $key)) {
        return;
    }
    if (!current_user_can('manage_options')) {
        return;
    }

    $ads = globalnews_get_header_ads();
    if (isset($ads[$key])) {
        wp_delete_attachment($ads[$key]['image_id'], true);
        unset($ads[$key]);
        $ads = array_values($ads);
        update_option('globalnews_header_ads', $ads);
    }

    wp_safe_redirect(admin_url('admin.php?page=globalnews-header-ads&ad_deleted=1'));
    exit;
}
add_action('admin_post_globalnews_delete_header_ad', 'globalnews_delete_header_ad');

function globalnews_header_ad_redirect_error($message) {
    wp_safe_redirect(add_query_arg(array(
        'page'         => 'globalnews-header-ads',
        'upload_error' => urlencode($message),
    ), admin_url('admin.php')));
    exit;
}
