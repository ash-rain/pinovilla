<?php
require_once('wp-load.php');

$theme_dir = get_template_directory();
$upload_dir = wp_upload_dir();
$base_url = get_site_url();

echo "Theme Dir: $theme_dir\n";
echo "Base URL: $base_url\n";

$pages = get_pages();
echo "Found " . count($pages) . " pages.\n";
foreach ($pages as $page) {
    echo "Processing page: " . $page->post_title . "\n";
    $content = $page->post_content;
    
    // Find all img src
    preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
    $images = $matches[1];
    
    // Find all background images
    preg_match_all('/url\([\'"]?([^)]+)[\'"]?\)/', $content, $matches_bg);
    $images = array_merge($images, $matches_bg[1]);
    
    foreach ($images as $img_url) {
        $img_url = trim($img_url, '"\'');
        
        // We only care about local images
        if (strpos($img_url, '/wp-content/') !== false) {
            // Convert URL to local path
            // Remove domain if present
            $rel_path = str_replace($base_url, '', $img_url);
            
            // If it starts with /wp-content, map to local path
            if (strpos($rel_path, '/wp-content/') === 0) {
                $local_path = ABSPATH . ltrim($rel_path, '/');
                
                // DEBUG: Print check for specific images
                if (strpos($img_url, 'about-us-header.jpg') !== false) {
                     echo "DEBUG CHECK: $img_url\n";
                     echo "  Mapped to: $local_path\n";
                     echo "  Exists: " . (file_exists($local_path) ? "YES" : "NO") . "\n";
                     echo "  Size: " . (file_exists($local_path) ? filesize($local_path) : "N/A") . "\n";
                }

                if (!file_exists($local_path)) {
                    echo "MISSING: $img_url (Page: {$page->post_title})\n";
                    echo "  Expected at: $local_path\n";
                } elseif (filesize($local_path) == 0) {
                    echo "EMPTY: $img_url (Page: {$page->post_title})\n";
                } else {
                    // echo "OK: $img_url\n";
                }
            }
        }
    }
}
?>
