<?php

function globalnews_add_video_audio_mime_types($mimes) {
    $mimes['mp4']  = 'video/mp4';
    $mimes['webm'] = 'video/webm';
    $mimes['ogv']  = 'video/ogg';
    $mimes['mov']  = 'video/quicktime';
    $mimes['avi']  = 'video/x-msvideo';
    $mimes['mkv']  = 'video/x-matroska';
    $mimes['mp3']  = 'audio/mpeg';
    $mimes['wav']  = 'audio/wav';
    $mimes['aac']  = 'audio/aac';
    $mimes['flac'] = 'audio/flac';
    $mimes['m4a']  = 'audio/mp4';
    $mimes['ogg']  = 'audio/ogg';
    return $mimes;
}
add_filter('upload_mimes', 'globalnews_add_video_audio_mime_types');

function globalnews_add_upload_submenu_pages() {
    add_submenu_page(
        'edit.php?post_type=video',
        __('Upload Video', 'globalnews-media'),
        __('Upload Video', 'globalnews-media'),
        'edit_posts',
        'upload-video',
        'globalnews_admin_upload_page_video'
    );
    add_submenu_page(
        'edit.php?post_type=audio',
        __('Upload Audio', 'globalnews-media'),
        __('Upload Audio', 'globalnews-media'),
        'edit_posts',
        'upload-audio',
        'globalnews_admin_upload_page_audio'
    );
}
add_action('admin_menu', 'globalnews_add_upload_submenu_pages');

function globalnews_admin_upload_page_video() {
    globalnews_render_admin_upload_form('video');
}

function globalnews_admin_upload_page_audio() {
    globalnews_render_admin_upload_form('audio');
}

function globalnews_render_admin_upload_form($post_type) {
    $label = $post_type === 'video' ? __('Video', 'globalnews-media') : __('Audio', 'globalnews-media');
    $max_size = wp_max_upload_size();
    $max_size_mb = $max_size ? floor($max_size / MB_IN_BYTES) : 0;

    if (!empty($_GET['upload_error'])) {
        $error_msg = sanitize_text_field($_GET['upload_error']);
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error_msg) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(sprintf(__('Upload %s', 'globalnews-media'), $label)); ?></h1>
        <p><?php esc_html_e('Select a file from your computer to upload and publish.', 'globalnews-media'); ?>
        <br><small><?php printf(esc_html__('Maximum file size: %s MB', 'globalnews-media'), $max_size_mb); ?></small></p>
        <form method="post" enctype="multipart/form-data" style="max-width:600px;">
            <?php wp_nonce_field('globalnews_admin_media_upload', 'globalnews_admin_media_nonce'); ?>
            <input type="hidden" name="globalnews_media_type" value="<?php echo esc_attr($post_type); ?>">

            <table class="form-table">
                <tr>
                    <th scope="row"><label for="gn-media-title"><?php esc_html_e('Title', 'globalnews-media'); ?></label></th>
                    <td><input type="text" name="media_title" id="gn-media-title" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="gn-media-desc"><?php esc_html_e('Description', 'globalnews-media'); ?></label></th>
                    <td><textarea name="media_description" id="gn-media-desc" rows="4" class="large-text"></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="gn-media-file"><?php esc_html_e('File', 'globalnews-media'); ?></label></th>
                    <td>
                        <input type="file" name="media_file" id="gn-media-file" accept="video/*,audio/*" required
                               style="padding:8px 0;">
                        <p class="description" id="gn-file-name" style="margin-top:6px;"></p>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button type="submit" class="button button-primary" id="gn-upload-btn">
                    <?php echo esc_html(sprintf(__('Upload &amp; Publish %s', 'globalnews-media'), $label)); ?>
                </button>
                <span class="spinner" id="gn-upload-spinner" style="float:none;margin-left:8px;"></span>
            </p>
        </form>
    </div>

    <script>
    jQuery(function($) {
        $('#gn-media-file').on('change', function() {
            var file = this.files[0];
            if (file) {
                var size = (file.size / 1048576).toFixed(1);
                $('#gn-file-name').text(file.name + ' (' + size + ' MB)');
            } else {
                $('#gn-file-name').text('');
            }
        });
        $('#gn-upload-btn').on('click', function() {
            var file = $('#gn-media-file')[0].files[0];
            if (!file) { alert('Please select a file.'); return false; }
            if (!file.name.match(/\.(mp4|webm|ogg|mov|avi|mkv|mp3|wav|aac|flac|m4a)$/i)) {
                alert('Please select a valid video or audio file.');
                return false;
            }
            $(this).prop('disabled', true).text('Uploading...');
            $('#gn-upload-spinner').addClass('is-active');
            return true;
        });
    });
    </script>
    <?php
}

function globalnews_handle_admin_media_upload() {
    if (empty($_POST['globalnews_admin_media_nonce']) || !wp_verify_nonce($_POST['globalnews_admin_media_nonce'], 'globalnews_admin_media_upload')) {
        return;
    }
    if (!current_user_can('edit_posts')) {
        wp_die(__('Insufficient permissions.', 'globalnews-media'));
    }

    $post_type = $_POST['globalnews_media_type'] === 'audio' ? 'audio' : 'video';
    $title = sanitize_text_field($_POST['media_title']);
    $description = sanitize_textarea_field($_POST['media_description']);

    if (empty($title) || empty($_FILES['media_file']['name'])) {
        globalnews_media_upload_error(__('Please provide a title and select a file.', 'globalnews-media'), $post_type);
    }

    if ($_FILES['media_file']['error'] !== UPLOAD_ERR_OK) {
        globalnews_media_upload_error(__('File upload error. Please try again.', 'globalnews-media'), $post_type);
    }

    $file_ext = strtolower(pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION));
    $video_exts = array('mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv');
    $audio_exts = array('mp3', 'wav', 'aac', 'flac', 'ogg', 'm4a');

    if ($post_type === 'video' && !in_array($file_ext, $video_exts)) {
        globalnews_media_upload_error(__('Invalid video format. Allowed: mp4, webm, ogg, mov, avi, mkv.', 'globalnews-media'), $post_type);
    }
    if ($post_type === 'audio' && !in_array($file_ext, $audio_exts)) {
        globalnews_media_upload_error(__('Invalid audio format. Allowed: mp3, wav, aac, flac, ogg, m4a.', 'globalnews-media'), $post_type);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_id = media_handle_upload('media_file', 0);
    if (is_wp_error($attachment_id)) {
        globalnews_media_upload_error(__('Failed to process file: ', 'globalnews-media') . $attachment_id->get_error_message(), $post_type);
    }

    $file_url = wp_get_attachment_url($attachment_id);
    $shortcode = $post_type === 'video'
        ? '[video src="' . esc_url($file_url) . '" poster="" width="720"]'
        : '[audio src="' . esc_url($file_url) . '"]';

    $post_content = $shortcode . "\n\n" . $description;

    $post_id = wp_insert_post(array(
        'post_title'     => $title,
        'post_content'   => $post_content,
        'post_status'    => 'publish',
        'post_author'    => get_current_user_id(),
        'post_type'      => $post_type,
    ));

    if ($post_id) {
        if (wp_attachment_is_image($attachment_id)) {
            set_post_thumbnail($post_id, $attachment_id);
        }
        wp_redirect(admin_url('edit.php?post_type=' . $post_type . '&media_uploaded=1&media_title=' . urlencode($title)));
        exit;
    }

    globalnews_media_upload_error(__('Failed to create post.', 'globalnews-media'), $post_type);
}
add_action('admin_init', 'globalnews_handle_admin_media_upload');

function globalnews_media_upload_error($message, $post_type) {
    $page = $post_type === 'audio' ? 'upload-audio' : 'upload-video';
    wp_safe_redirect(add_query_arg(array(
        'post_type'    => $post_type,
        'page'         => $page,
        'upload_error' => urlencode($message),
    ), admin_url('edit.php')));
    exit;
}

function globalnews_media_uploaded_notice() {
    if (empty($_GET['media_uploaded']) || empty($_GET['media_title'])) {
        return;
    }
    $title = esc_html(sanitize_text_field($_GET['media_title']));
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo sprintf(esc_html__('Media "%s" uploaded and published successfully.', 'globalnews-media'), $title); ?></p>
    </div>
    <?php
}
add_action('admin_notices', 'globalnews_media_uploaded_notice');
