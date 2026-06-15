<?php

define('GLOBALNEWS_VERSION', '2.0.4');
define('GLOBALNEWS_DIR', get_template_directory());
define('GLOBALNEWS_URI', get_template_directory_uri());

if (!defined('GLOBALNEWS_DEBUG')) {
    define('GLOBALNEWS_DEBUG', defined('WP_DEBUG') && WP_DEBUG);
}

$inc_files = array(
    'theme-setup',
    'enqueue',
    'helpers',
    'menu',
    'widgets',
    'customizer',
    'ads',
    'dark-mode',
    'seo',
    'performance',
    'security',
    'meta-boxes',
    'user-roles',
    'admin-settings',
    'class-sitemap',
    'class-pwa',
    'class-rss',
    'class-social-auto',
    'class-workflow',
    'custom-post-types',
    'media-upload',
    'ads-manager',
    'appearance-settings',
    'landing-page-settings',
    'feedback',
);

foreach ($inc_files as $file) {
    $path = GLOBALNEWS_DIR . '/inc/' . $file . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
}

function globalnews_init_environment() {
    if (!is_admin() && !wp_doing_ajax() && !defined('REST_REQUEST')) {
        globalnews_add_security_headers();
    }
}
add_action('init', 'globalnews_init_environment', 0);

function globalnews_flush_rewrite_rules() {
    $version = get_option('globalnews_rewrite_rules_version', '');
    if ($version !== GLOBALNEWS_VERSION) {
        update_option('globalnews_rewrite_rules_version', GLOBALNEWS_VERSION);
        flush_rewrite_rules();
    }
}
add_action('wp_loaded', 'globalnews_flush_rewrite_rules', 999);

function globalnews_show_password_toggle() { ?>
    <style>
    .login-show-password { margin-bottom: 16px; }
    .login-show-password label { font-size: 13px; display: flex; align-items: center; gap: 6px; cursor: pointer; }
    .login-show-password input[type="checkbox"] { margin: 0; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var pwd = document.getElementById('user_pass');
        if (!pwd) return;
        var wrap = pwd.closest('p') || pwd.parentNode;
        var cb = document.createElement('div');
        cb.className = 'login-show-password';
        cb.innerHTML = '<label><input type="checkbox" id="show-pwd"> <?php echo esc_js(__('Show password', 'globalnews-media')); ?></label>';
        wrap.parentNode.insertBefore(cb, wrap.nextSibling);
        document.getElementById('show-pwd').addEventListener('change', function() {
            pwd.type = this.checked ? 'text' : 'password';
        });
    });
    </script>
<?php }
add_action('login_footer', 'globalnews_show_password_toggle');
