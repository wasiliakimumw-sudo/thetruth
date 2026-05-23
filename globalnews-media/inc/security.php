<?php

remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

function globalnews_hide_wp_version() {
    return '';
}
add_filter('style_loader_src', 'globalnews_hide_wp_version');
add_filter('script_loader_src', 'globalnews_hide_wp_version');

function globalnews_remove_x_pingback($headers) {
    unset($headers['X-Pingback']);
    return $headers;
}
add_filter('wp_headers', 'globalnews_remove_x_pingback');

function globalnews_secure_login_errors() {
    return esc_html__('Invalid credentials.', 'globalnews-media');
}
add_filter('login_errors', 'globalnews_secure_login_errors');

add_filter('rest_authentication_errors', function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', esc_html__('You must be logged in to access the REST API.', 'globalnews-media'), array('status' => 401));
    }
    return $result;
});

function globalnews_disable_xmlrpc() {
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
        wp_die(esc_html__('XML-RPC is disabled for security reasons.', 'globalnews-media'));
    }
}
add_action('init', 'globalnews_disable_xmlrpc');

function globalnews_remove_script_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'globalnews_remove_script_version', 15, 1);
add_filter('style_loader_src', 'globalnews_remove_script_version', 15, 1);

function globalnews_add_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(self), microphone=(), camera=(), fullscreen=(self)');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
add_action('send_headers', 'globalnews_add_security_headers');

function globalnews_clean_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('template_redirect', 'rest_output_link_header', 11, 0);
}
add_action('init', 'globalnews_clean_head');

function globalnews_disable_file_edit() {
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('init', 'globalnews_disable_file_edit');

class GlobalNews_LimitLogin {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('authenticate', array($this, 'check_attempts'), 30, 3);
        add_action('wp_login_failed', array($this, 'log_failed_attempt'));
        add_action('wp_login', array($this, 'clear_attempts'), 10, 2);
    }

    public function check_attempts($user, $username, $password) {
        if (empty($username)) {
            return $user;
        }
        $ip = $this->get_ip();
        $attempts = $this->get_attempts($ip);
        if ($attempts >= 5) {
            $lockout_time = get_transient('globalnews_lockout_' . $ip);
            if ($lockout_time && $lockout_time > time()) {
                $remaining = ceil(($lockout_time - time()) / 60);
                return new WP_Error('too_many_attempts', sprintf(__('Too many login attempts. Please try again in %d minutes.', 'globalnews-media'), $remaining));
            }
            delete_transient('globalnews_attempts_' . $ip);
        }
        return $user;
    }

    public function log_failed_attempt($username) {
        if (empty($username)) {
            return;
        }
        $ip = $this->get_ip();
        $attempts = $this->get_attempts($ip) + 1;
        set_transient('globalnews_attempts_' . $ip, $attempts, HOUR_IN_SECONDS);
        if ($attempts >= 5) {
            set_transient('globalnews_lockout_' . $ip, time() + 900, 900);
        }
    }

    public function clear_attempts($user_login, $user) {
        $ip = $this->get_ip();
        delete_transient('globalnews_attempts_' . $ip);
        delete_transient('globalnews_lockout_' . $ip);
    }

    private function get_attempts($ip) {
        return (int) get_transient('globalnews_attempts_' . $ip);
    }

    private function get_ip() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}
GlobalNews_LimitLogin::instance();

class GlobalNews_SpamProtection {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('preprocess_comment', array($this, 'check_comment_spam'));
        add_filter('pre_comment_approved', array($this, 'hold_spam_comments'), 10, 2);
        add_action('wp_ajax_globalnews_spam_report', array($this, 'handle_spam_report'));
        add_action('admin_init', array($this, 'add_spam_settings'));
    }

    public function check_comment_spam($commentdata) {
        if (current_user_can('moderate_comments')) {
            return $commentdata;
        }
        $spam_keywords = array(
            'buy now', 'click here', 'free', 'subscribe', 'httpss?://', 'www\.',
            '\bviagra\b', '\bcasino\b', '\bloans?\b', 'act now', 'limited time',
            'congratulations', 'you won', 'prize', 'lottery', '\bsex\b',
        );
        $content = strtolower($commentdata['comment_content']);
        foreach ($spam_keywords as $pattern) {
            if (preg_match('/' . $pattern . '/i', $content)) {
                $commentdata['comment_approved'] = 'spam';
                break;
            }
        }
        $link_count = preg_match_all('/https?:\/\//i', $content);
        if ($link_count > 2) {
            $commentdata['comment_approved'] = 'spam';
        }
        if (empty($commentdata['comment_author']) || empty($commentdata['comment_content'])) {
            $commentdata['comment_approved'] = 'spam';
        }
        return $commentdata;
    }

    public function hold_spam_comments($approved, $commentdata) {
        if ($approved === 'spam') {
            return 'spam';
        }
        if (wp_is_mobile() && empty($commentdata['comment_author_url'])) {
            return 1;
        }
        return $approved;
    }

    public function add_spam_settings() {
        add_settings_field(
            'globalnews_spam_protection',
            __('Spam Protection', 'globalnews-media'),
            function () {
                $enabled = get_option('globalnews_spam_protection', true);
                echo '<label><input type="checkbox" name="globalnews_spam_protection" value="1" ' . checked($enabled, 1, false) . '> ' . __('Enable spam protection for comments', 'globalnews-media') . '</label>';
            },
            'discussion',
            'default'
        );
        register_setting('discussion', 'globalnews_spam_protection');
    }

    public function handle_spam_report() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'globalnews_spam_nonce')) {
            wp_die('Invalid nonce');
        }
        $comment_id = intval($_POST['comment_id'] ?? 0);
        if ($comment_id && current_user_can('moderate_comments')) {
            wp_set_comment_status($comment_id, 'spam');
        }
        wp_die('ok');
    }
}
GlobalNews_SpamProtection::instance();

function globalnews_add_captcha_to_login() {
    ?>
    <p>
        <label for="globalnews_login_captcha"><?php esc_html_e('Security Question: What is 3 + 4?', 'globalnews-media'); ?></label>
        <input type="number" name="globalnews_login_captcha" id="globalnews_login_captcha" class="input" value="" size="20" required>
    </p>
    <?php
}
add_action('login_form', 'globalnews_add_captcha_to_login');

function globalnews_verify_login_captcha($user, $password) {
    if (isset($_POST['globalnews_login_captcha'])) {
        $answer = intval($_POST['globalnews_login_captcha']);
        if ($answer !== 7) {
            return new WP_Error('captcha_error', __('Security answer is incorrect.', 'globalnews-media'));
        }
    }
    return $user;
}
add_filter('wp_authenticate_user', 'globalnews_verify_login_captcha', 10, 2);

function globalnews_hide_admin_users() {
    ?>
    <style>
    .user-admin-color-wrap, .show-admin-bar { display: none; }
    </style>
    <?php
}
add_action('admin_head-user-edit.php', 'globalnews_hide_admin_users');
add_action('admin_head-profile.php', 'globalnews_hide_admin_users');

function globalnews_backup_support() {
    add_filter('cron_schedules', function ($schedules) {
        $schedules['weekly'] = array(
            'interval' => 604800,
            'display' => __('Once Weekly', 'globalnews-media'),
        );
        return $schedules;
    });
    if (!wp_next_scheduled('globalnews_database_backup')) {
        wp_schedule_event(time(), 'weekly', 'globalnews_database_backup');
    }
    add_action('globalnews_database_backup', function () {
        $backup_dir = WP_CONTENT_DIR . '/backups';
        if (!file_exists($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }
        $backup_file = $backup_dir . '/db-backup-' . date('Y-m-d-H-i-s') . '.sql';
        $admin_email = get_option('admin_email');
        if (file_exists($backup_file) && filesize($backup_file) > 100) {
            wp_mail($admin_email, sprintf(__('Database Backup - %s', 'globalnews-media'), get_bloginfo('name')), sprintf(__('Database backup completed: %s', 'globalnews-media'), $backup_file));
        }
    });
}
add_action('init', 'globalnews_backup_support');

function globalnews_auto_update_check() {
    if (!defined('WP_AUTO_UPDATE_CORE')) {
        define('WP_AUTO_UPDATE_CORE', 'minor');
    }
}
add_action('init', 'globalnews_auto_update_check');
