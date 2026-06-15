<?php

function globalnews_feedback_init() {
    add_action('wp_ajax_globalnews_add_reaction', 'globalnews_handle_reaction');
    add_action('wp_ajax_nopriv_globalnews_add_reaction', 'globalnews_handle_reaction');

    add_action('wp_ajax_globalnews_submit_feedback', 'globalnews_handle_feedback');
    add_action('wp_ajax_nopriv_globalnews_submit_feedback', 'globalnews_handle_feedback');

    add_action('wp_ajax_globalnews_site_feedback', 'globalnews_handle_site_feedback');
    add_action('wp_ajax_nopriv_globalnews_site_feedback', 'globalnews_handle_site_feedback');

    if (!is_admin() && !wp_doing_ajax()) {
        add_action('wp_footer', 'globalnews_feedback_js_data');
    }
}
add_action('init', 'globalnews_feedback_init');

add_action('admin_menu', 'globalnews_feedback_admin_menu');
add_action('admin_enqueue_scripts', 'globalnews_feedback_admin_styles');

function globalnews_get_reactions($post_id) {
    $default = array('like' => 0, 'love' => 0, 'wow' => 0, 'sad' => 0, 'fire' => 0);
    $saved = get_post_meta($post_id, '_globalnews_reactions', true);
    return is_array($saved) ? array_merge($default, $saved) : $default;
}

function globalnews_get_user_reactions($post_id) {
    $cookie = isset($_COOKIE['globalnews_reactions']) ? json_decode(stripslashes($_COOKIE['globalnews_reactions']), true) : array();
    return isset($cookie[$post_id]) ? $cookie[$post_id] : array();
}

function globalnews_handle_reaction() {
    check_ajax_referer('globalnews_nonce', 'nonce');

    $post_id = absint($_POST['post_id']);
    $reaction = sanitize_key($_POST['reaction']);

    if (!$post_id || !get_post($post_id) || !in_array($reaction, array('like', 'love', 'wow', 'sad', 'fire'), true)) {
        wp_send_json_error();
    }

    $reactions = globalnews_get_reactions($post_id);
    $user_reactions = globalnews_get_user_reactions($post_id);

    if (in_array($reaction, $user_reactions, true)) {
        $reactions[$reaction] = max(0, $reactions[$reaction] - 1);
        $user_reactions = array_values(array_diff($user_reactions, array($reaction)));
    } else {
        $reactions[$reaction] += 1;
        $user_reactions[] = $reaction;
    }

    update_post_meta($post_id, '_globalnews_reactions', $reactions);

    $cookie = isset($_COOKIE['globalnews_reactions']) ? json_decode(stripslashes($_COOKIE['globalnews_reactions']), true) : array();
    $cookie[$post_id] = $user_reactions;
    setcookie('globalnews_reactions', json_encode($cookie), time() + 31536000, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);

    wp_send_json_success(array(
        'reactions' => $reactions,
        'user'      => $user_reactions,
        'total'     => array_sum($reactions),
    ));
}

function globalnews_handle_feedback() {
    check_ajax_referer('globalnews_nonce', 'nonce');

    $post_id = absint($_POST['post_id']);
    $type = sanitize_text_field($_POST['feedback_type']);
    $message = sanitize_textarea_field($_POST['feedback_message']);

    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error();
    }

    $feedback = get_post_meta($post_id, '_globalnews_feedback', true) ?: array();
    $feedback[] = array(
        'type'      => $type,
        'message'   => $message,
        'time'      => current_time('mysql'),
        'ip'        => $_SERVER['REMOTE_ADDR'] ?? '',
    );
    update_post_meta($post_id, '_globalnews_feedback', $feedback);

    $helpful = get_post_meta($post_id, '_globalnews_helpful', true) ?: array('yes' => 0, 'no' => 0);
    if (in_array($type, array('yes', 'no'), true)) {
        $helpful[$type] += 1;
        update_post_meta($post_id, '_globalnews_helpful', $helpful);
    }

    wp_send_json_success(array('message' => __('Thank you for your feedback!', 'globalnews-media')));
}

function globalnews_handle_site_feedback() {
    check_ajax_referer('globalnews_nonce', 'nonce');

    $message = sanitize_textarea_field($_POST['message'] ?? '');
    if (empty($message)) {
        wp_send_json_error(array('message' => __('Please write your feedback.', 'globalnews-media')));
    }

    $feedback = get_option('globalnews_site_feedback', array());
    $feedback[] = array(
        'message' => $message,
        'time'    => current_time('mysql'),
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
        'read'    => false,
    );
    update_option('globalnews_site_feedback', $feedback);

    wp_send_json_success(array('message' => __('Thank you for your feedback!', 'globalnews-media')));
}

function globalnews_feedback_admin_menu() {
    $feedback = get_option('globalnews_site_feedback', array());
    $unread = 0;
    foreach ($feedback as $item) {
        if (empty($item['read'])) {
            $unread++;
        }
    }

    $hook = add_menu_page(
        __('Feedback', 'globalnews-media'),
        $unread > 0 ? sprintf(__('Feedback %s', 'globalnews-media'), '<span class="update-plugins count-' . $unread . '"><span class="plugin-count">' . $unread . '</span></span>') : __('Feedback', 'globalnews-media'),
        'manage_options',
        'globalnews-feedback',
        'globalnews_feedback_admin_page',
        'dashicons-email-alt',
        30
    );
}

function globalnews_feedback_admin_page() {
    if (!empty($_POST['globalnews_feedback_action']) && check_admin_referer('globalnews_feedback_mark_read')) {
        $feedback = get_option('globalnews_site_feedback', array());
        if ($_POST['globalnews_feedback_action'] === 'mark_read_all') {
            foreach ($feedback as $i => $item) {
                $feedback[$i]['read'] = true;
            }
            update_option('globalnews_site_feedback', $feedback);
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('All feedback marked as read.', 'globalnews-media') . '</p></div>';
        }
        if ($_POST['globalnews_feedback_action'] === 'clear_all') {
            update_option('globalnews_site_feedback', array());
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('All feedback cleared.', 'globalnews-media') . '</p></div>';
        }
    }

    $feedback = get_option('globalnews_site_feedback', array());
    $feedback = array_reverse($feedback);
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Site Feedback', 'globalnews-media'); ?></h1>
        <p><?php esc_html_e('Feedback submitted by visitors from the footer form.', 'globalnews-media'); ?></p>

        <form method="post" style="display:inline-block;margin-bottom:15px;">
            <?php wp_nonce_field('globalnews_feedback_mark_read'); ?>
            <input type="hidden" name="globalnews_feedback_action" value="mark_read_all">
            <button type="submit" class="button"><?php esc_html_e('Mark All Read', 'globalnews-media'); ?></button>
        </form>
        <form method="post" style="display:inline-block;margin-left:5px;">
            <?php wp_nonce_field('globalnews_feedback_mark_read'); ?>
            <input type="hidden" name="globalnews_feedback_action" value="clear_all">
            <button type="submit" class="button button-link-delete" onclick="return confirm('<?php esc_attr_e('Delete all feedback?', 'globalnews-media'); ?>');"><?php esc_html_e('Clear All', 'globalnews-media'); ?></button>
        </form>

        <?php if (empty($feedback)) : ?>
            <p><em><?php esc_html_e('No feedback yet.', 'globalnews-media'); ?></em></p>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:30px;"><?php esc_html_e('Status', 'globalnews-media'); ?></th>
                        <th><?php esc_html_e('Message', 'globalnews-media'); ?></th>
                        <th style="width:160px;"><?php esc_html_e('Date', 'globalnews-media'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedback as $item) : ?>
                        <tr style="<?php echo empty($item['read']) ? 'background:#f0f6fc;' : ''; ?>">
                            <td><?php echo empty($item['read']) ? '<span style="color:#d63638;font-weight:600;">' . esc_html__('New', 'globalnews-media') . '</span>' : ''; ?></td>
                            <td><?php echo esc_html($item['message']); ?></td>
                            <td><?php echo esc_html($item['time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

function globalnews_feedback_admin_styles($hook) {
    if ($hook !== 'toplevel_page_globalnews-feedback') {
        return;
    }
    ?>
    <style>
        .feedback-unread { background: #f0f6fc; }
        tbody tr:hover { background: #e8f0fa !important; }
    </style>
    <?php
}

function globalnews_feedback_js_data() {
    if (!is_singular('post')) return;
    ?>
    <script>
    window.globalnewsFeedback = {
        ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('globalnews_nonce'); ?>',
        postId: <?php echo get_the_ID(); ?>
    };
    </script>
    <?php
}

function globalnews_site_feedback_js() {
    ?>
    <script>
    jQuery(function($) {
        var form = $('#gn-site-feedback-form');
        if (!form.length) return;

        form.on('submit', function(e) {
            e.preventDefault();
            var textarea = form.find('textarea');
            var btn = form.find('button[type="submit"]');
            var msg = textarea.val().trim();

            if (!msg) {
                alert('<?php echo esc_js(__('Please write your feedback.', 'globalnews-media')); ?>');
                return;
            }

            btn.prop('disabled', true).text('<?php echo esc_js(__('Sending...', 'globalnews-media')); ?>');

            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'globalnews_site_feedback',
                nonce: '<?php echo wp_create_nonce('globalnews_nonce'); ?>',
                message: msg
            }, function(res) {
                if (res.success) {
                    textarea.val('');
                    form.find('.gn-feedback-success').show().fadeOut(4000);
                } else {
                    alert(res.data.message || '<?php echo esc_js(__('Error sending feedback.', 'globalnews-media')); ?>');
                }
            }).always(function() {
                btn.prop('disabled', false).text('<?php echo esc_js(__('Send Feedback', 'globalnews-media')); ?>');
            });
        });
    });
    </script>
    <?php
}
