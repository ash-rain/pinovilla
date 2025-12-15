<?php
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

$plugin = 'sitepress-multilingual-cms/sitepress.php';
$result = activate_plugin($plugin);

if (is_wp_error($result)) {
    echo "Error activating WPML: " . $result->get_error_message() . "\n";
} else {
    echo "WPML activated successfully.\n";
}
?>
