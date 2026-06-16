<?php

class GlobalNews_PWA {
    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('template_redirect', array($this, 'handle_requests'), 0);
        add_action('wp_head', array($this, 'add_manifest_link'), 1);
        add_action('wp_head', array($this, 'add_meta_tags'), 1);
        add_action('wp_footer', array($this, 'register_service_worker'), 999);
        add_action('wp_ajax_globalnews_subscribe_push', array($this, 'handle_push_subscription'));
        add_action('wp_ajax_nopriv_globalnews_subscribe_push', array($this, 'handle_push_subscription'));
    }

    public function handle_requests() {
        $uri = isset($_SERVER['REQUEST_URI']) ? urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) : '';
        $uri = trim($uri, '/');
        $site_url = trim(parse_url(home_url(), PHP_URL_PATH) ?: '', '/');
        $uri = preg_replace('#^' . preg_quote($site_url, '#') . '/?#', '', $uri);

        if ($uri === 'manifest.json') {
            $this->serve_manifest();
            exit;
        }
        if ($uri === 'sw.js') {
            $this->serve_sw();
            exit;
        }
    }

    public function add_manifest_link() {
        echo "\t" . '<link rel="manifest" href="' . esc_url(home_url('/manifest.json')) . '">' . "\n";
    }

    public function add_meta_tags() {
        $theme_color = get_theme_mod('globalnews_theme_color', '#e50914');
        ?>
        <meta name="theme-color" content="<?php echo esc_attr($theme_color); ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>">
        <link rel="apple-touch-icon" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/images/app-icon-192.svg'); ?>">
        <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url(GLOBALNEWS_URI . '/assets/images/app-icon-192.svg'); ?>">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="<?php bloginfo('name'); ?>">
        <meta name="msapplication-TileColor" content="<?php echo esc_attr($theme_color); ?>">
        <meta name="msapplication-square144x144logo" content="<?php echo esc_url(GLOBALNEWS_URI . '/assets/images/app-icon-192.svg'); ?>">
        <?php
    }

    public function serve_manifest() {
        $name = get_bloginfo('name');
        $description = get_bloginfo('description');
        $theme_color = get_theme_mod('globalnews_theme_color', '#111111');
        $icon_192 = GLOBALNEWS_URI . '/assets/images/app-icon-192.svg';
        $icon_512 = GLOBALNEWS_URI . '/assets/images/app-icon-512.svg';
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_192 = wp_get_attachment_image_src($logo_id, array(192, 192));
            $logo_512 = wp_get_attachment_image_src($logo_id, array(512, 512));
            if ($logo_192) { $icon_192 = $logo_192[0]; }
            if ($logo_512) { $icon_512 = $logo_512[0]; }
        }
        $manifest = array(
            'name' => $name,
            'short_name' => function_exists('mb_substr') ? mb_substr($name, 0, 12) : substr($name, 0, 12),
            'description' => $description,
            'start_url' => home_url('/'),
            'scope' => home_url('/'),
            'display' => 'standalone',
            'orientation' => 'portrait-primary',
            'background_color' => '#ffffff',
            'theme_color' => $theme_color,
            'categories' => array('news', 'media'),
            'lang' => get_locale(),
            'icons' => array(
                array('src' => $icon_192, 'sizes' => '192x192', 'type' => 'image/svg+xml', 'purpose' => 'any maskable'),
                array('src' => $icon_512, 'sizes' => '512x512', 'type' => 'image/svg+xml', 'purpose' => 'any maskable'),
            ),
            'shortcuts' => array(
                array('name' => __('Latest News', 'globalnews-media'), 'short_name' => __('News', 'globalnews-media'), 'description' => __('View latest news', 'globalnews-media'), 'url' => home_url('/')),
                array('name' => __('Breaking News', 'globalnews-media'), 'short_name' => __('Breaking', 'globalnews-media'), 'description' => __('View breaking news', 'globalnews-media'), 'url' => home_url('/?breaking=1')),
            ),
            'screenshots' => array(),
            'prefer_related_applications' => false,
        );
        if (function_exists('wp_ob_end_flush_all')) {
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
        status_header(200);
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: public, max-age=86400');
        header('Access-Control-Allow-Origin: *');
        echo wp_json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function serve_sw() {
        if (function_exists('wp_ob_end_flush_all')) {
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
        status_header(200);
        header('Content-Type: text/javascript; charset=UTF-8');
        header('Service-Worker-Allowed: /');
        header('Cache-Control: public, max-age=86400');
        $sw_path = GLOBALNEWS_DIR . '/sw.js';
        if (file_exists($sw_path)) {
            readfile($sw_path);
        } else {
            echo $this->get_default_sw();
        }
    }

    private function get_default_sw() {
        ob_start(); ?>
const CACHE_NAME = 'globalnews-v3';
const OFFLINE_URL = '<?php echo home_url('/offline'); ?>';
const PRECACHE_URLS = ['/', '/wp-content/themes/globalnews-media/assets/css/main.css', '/wp-content/themes/globalnews-media/assets/css/responsive.css'];
self.addEventListener('install', function(e) { e.waitUntil(caches.open(CACHE_NAME).then(function(c) { return c.addAll(PRECACHE_URLS); }).then(function() { return self.skipWaiting(); })); });
self.addEventListener('activate', function(e) { e.waitUntil(caches.keys().then(function(n) { return Promise.all(n.filter(function(n) { return n !== CACHE_NAME; }).map(function(n) { return caches.delete(n); })); }).then(function() { return self.clients.claim(); })); });
self.addEventListener('fetch', function(e) { if (e.request.method !== 'GET' || e.request.url.includes('/wp-admin/') || e.request.url.includes('/wp-login.php')) return; e.respondWith(caches.match(e.request).then(function(r) { return r || fetch(e.request).then(function(r) { var c = r.clone(); if (r && r.status === 200 && r.type === 'basic') { caches.open(CACHE_NAME).then(function(cache) { cache.put(e.request, c); }); } return r; }).catch(function() { return caches.match(OFFLINE_URL); }); })); });
self.addEventListener('push', function(e) { if (!e.data) return; var d = e.data.json(); e.waitUntil(self.registration.showNotification(d.title || '<?php echo esc_js(get_bloginfo('name')); ?>', { body: d.body || '', icon: d.icon || '/wp-content/themes/globalnews-media/assets/images/app-icon-192.svg', badge: '/wp-content/themes/globalnews-media/assets/images/app-icon-192.svg', data: { url: d.url || '/' }, actions: [{ action: 'read', title: 'Read More' }, { action: 'close', title: 'Dismiss' }] })); });
self.addEventListener('notificationclick', function(e) { e.notification.close(); if (e.action === 'close') return; var url = e.notification.data.url || '/'; e.waitUntil(clients.matchAll({type:'window'}).then(function(l) { for (var i = 0; i < l.length; i++) { if ('focus' in l[i]) return l[i].focus(); } if (clients.openWindow) return clients.openWindow(url); })); });
<?php
        return ob_get_clean();
    }

    public function register_service_worker() {
        if (is_preview()) {
            return;
        }
        ?>
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo esc_js(home_url('/sw.js')); ?>', { scope: '<?php echo esc_js(home_url('/')); ?>' })
                .then(function(reg) { console.log('SW registered:', reg.scope); if (reg.active) { reg.active.postMessage({ type: 'SKIP_WAITING' }); } })
                .catch(function(err) { console.log('SW registration failed:', err); });
            });
        }
        </script>
        <?php
    }

    public function handle_push_subscription() {
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
        if (!wp_verify_nonce($nonce, 'globalnews_pwa_nonce')) {
            wp_die('Invalid nonce');
        }
        $subscription = isset($_POST['subscription']) ? json_decode(stripslashes($_POST['subscription']), true) : array();
        if (empty($subscription)) {
            wp_die('Invalid subscription');
        }
        $subscriptions = get_option('globalnews_push_subscriptions', array());
        $exists = false;
        foreach ($subscriptions as $key => $sub) {
            if ($sub['endpoint'] === $subscription['endpoint']) {
                $subscriptions[$key] = $subscription;
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $subscriptions[] = $subscription;
        }
        update_option('globalnews_push_subscriptions', $subscriptions);
        wp_die('ok');
    }

    public static function send_push_notification($title, $body, $url = '') {
        $subscriptions = get_option('globalnews_push_subscriptions', array());
        if (empty($subscriptions) || empty($url)) {
            return;
        }
        $payload = json_encode(array(
            'title' => $title,
            'body'  => $body,
            'url'   => $url,
            'icon'  => GLOBALNEWS_URI . '/assets/images/app-icon-192.svg',
        ));
        foreach ($subscriptions as $subscription) {
            $endpoint = $subscription['endpoint'];
            $keys = $subscription['keys'] ?? array();
            if (empty($endpoint) || empty($keys)) continue;
            $p256dh = base64_decode(strtr($keys['p256dh'], '-_', '+/'));
            $auth = base64_decode(strtr($keys['auth'], '-_', '+/'));
            if (!class_exists('Minishlink\\WebPush\\WebPush')) continue;
            try {
                $webPush = new Minishlink\WebPush\WebPush(array('VAPID' => array(
                    'subject' => home_url('/'),
                    'publicKey' => get_option('globalnews_vapid_public_key', ''),
                    'privateKey' => get_option('globalnews_vapid_private_key', ''),
                )));
                $webPush->queueNotification(new Minishlink\WebPush\Notification($endpoint, $payload, $p256dh, $auth, true));
                foreach ($webPush->flush() as $report) {
                    if (!$report->isSuccess()) error_log('Push failed: ' . $report->getReason());
                }
            } catch (Exception $e) {
                error_log('Push error: ' . $e->getMessage());
            }
        }
    }
}

GlobalNews_PWA::instance();
