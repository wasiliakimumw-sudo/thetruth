<?php
$db = new PDO('sqlite:D:\updated data\thetruth\globalnews-dev\wordpress\wp-content\database\.ht.sqlite');
$opts = $db->query("SELECT option_value FROM wp_options WHERE option_name='active_plugins'")->fetchColumn();
$plugins = unserialize($opts);
echo 'Active plugins:' . PHP_EOL;
foreach ($plugins as $p) { echo '  - ' . $p . PHP_EOL; }
