<?php

function globalnews_feedback_init() {
    add_action('wp_ajax_globalnews_add_reaction', 'globalnews_handle_reaction');
    add_action('wp_ajax_nopriv_globalnews_add_reaction', 'globalnews_handle_reaction');

    add_action('wp_ajax_globalnews_submit_feedback', 'globalnews_handle_feedback');
    add_action('wp_ajax_nopriv_globalnews_submit_feedback', 'globalnews_handle_feedback');

    if (!is_admin() && !wp_doing_ajax()) {
        add_action('wp_footer', 'globalnews_feedback_js_data');
    }
}
add_action('init', 'globalnews_feedback_init');

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
