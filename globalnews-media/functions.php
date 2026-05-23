<?php
/**
 * GlobalNews Media Theme Functions
 */

define('GLOBALNEWS_VERSION', '1.0.0');
define('GLOBALNEWS_DIR', get_template_directory());
define('GLOBALNEWS_URI', get_template_directory_uri());

if (!defined('GLOBALNEWS_DEBUG')) {
    define('GLOBALNEWS_DEBUG', defined('WP_DEBUG') && WP_DEBUG);
}

require_once GLOBALNEWS_DIR . '/inc/theme-setup.php';
require_once GLOBALNEWS_DIR . '/inc/enqueue.php';
require_once GLOBALNEWS_DIR . '/inc/helpers.php';
require_once GLOBALNEWS_DIR . '/inc/menu.php';
require_once GLOBALNEWS_DIR . '/inc/widgets.php';
require_once GLOBALNEWS_DIR . '/inc/customizer.php';
require_once GLOBALNEWS_DIR . '/inc/ads.php';
require_once GLOBALNEWS_DIR . '/inc/dark-mode.php';
require_once GLOBALNEWS_DIR . '/inc/seo.php';
require_once GLOBALNEWS_DIR . '/inc/performance.php';
require_once GLOBALNEWS_DIR . '/inc/security.php';
require_once GLOBALNEWS_DIR . '/inc/meta-boxes.php';
require_once GLOBALNEWS_DIR . '/inc/user-roles.php';
require_once GLOBALNEWS_DIR . '/inc/admin-settings.php';
