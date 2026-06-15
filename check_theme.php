<?php
$db = new PDO('sqlite:D:\updated data\thetruth\globalnews-dev\wordpress\wp-content\database\.ht.sqlite');
$template = $db->query("SELECT option_value FROM wp_options WHERE option_name='template'")->fetchColumn();
$stylesheet = $db->query("SELECT option_value FROM wp_options WHERE option_name='stylesheet'")->fetchColumn();
echo 'Template: ' . $template . PHP_EOL;
echo 'Stylesheet: ' . $stylesheet . PHP_EOL;
