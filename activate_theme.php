<?php
require_once('wp-load.php');

$current_theme = get_option('stylesheet');
echo "Current theme: " . $current_theme . "\n";

if ($current_theme !== 'pinovilla') {
    echo "Switching to 'pinovilla'...\n";
    switch_theme('pinovilla');
    echo "Theme switched to 'pinovilla'.\n";
} else {
    echo "Theme 'pinovilla' is already active.\n";
}
?>
