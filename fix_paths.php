<?php
require_once('wp-load.php');

$pages = get_pages();
foreach ($pages as $page) {
    $content = $page->post_content;
    
    // Replace ./wp-content/ with /wp-content/
    $new_content = str_replace('./wp-content/', '/wp-content/', $content);
    
    // Also check for ./assets/ just in case
    $new_content = str_replace('./assets/', '/wp-content/themes/pinovilla/assets/', $new_content);
    
    if ($content !== $new_content) {
        $page->post_content = $new_content;
        wp_update_post($page);
        echo "Updated paths for page: " . $page->post_title . "\n";
    }
}
echo "Path fix complete.\n";
?>
