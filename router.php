<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$wpDir = __DIR__ . '/globalnews-dev/wordpress';
$fullPath = rtrim($wpDir . $uri, '/');

// Serve existing static files directly
if (is_file($fullPath)) {
    return false;
}

// For directories, serve index.php if it exists
$indexPath = $fullPath . '/index.php';
if (is_dir($fullPath) && is_file($indexPath)) {
    chdir($fullPath);
    require $indexPath;
    return true;
}

// Route through WordPress front controller
chdir($wpDir);
require $wpDir . '/index.php';
