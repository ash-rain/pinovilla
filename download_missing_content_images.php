<?php
require_once('wp-load.php');

$base_url = 'https://pinovilla.com';
$theme_dir = 'wp-content/themes/pinovilla';

function download_image($url) {
    global $theme_dir, $base_url;
    
    // Normalize URL
    if (strpos($url, 'http') !== 0) {
        // Handle root-relative paths
        if (strpos($url, '/') === 0) {
            $url = $base_url . $url;
        } else {
            // Handle relative paths (tricky without context, assume root for now or skip)
             $url = $base_url . '/' . $url;
        }
    }
    
    // Check if it's a local asset (from our perspective)
    // We are looking for URLs that map to our assets folder
    if (strpos($url, $base_url . '/assets/') === 0) {
        $rel_path = str_replace($base_url, '', $url);
        // Fix for potential double slash
        $rel_path = str_replace('//', '/', $rel_path);
        
        $local_path = $theme_dir . $rel_path;
        
        // Remove query strings
        $local_path = strtok($local_path, '?');
        $url = strtok($url, '?');

        $dir = dirname($local_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!file_exists($local_path)) {
            echo "Downloading $url to $local_path\n";
            $file_content = @file_get_contents($url);
            if ($file_content) {
                file_put_contents($local_path, $file_content);
            } else {
                echo "Failed to download $url\n";
            }
        }
    }
}

$pages = get_pages();
foreach ($pages as $page) {
    $content = $page->post_content;
    
    // Find all img src
    preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
    foreach ($matches[1] as $src) {
        // If src points to our theme assets but is missing, we need to reconstruct the source URL
        // The content in DB already has /wp-content/themes/pinovilla/assets/...
        // We need to map this back to https://pinovilla.com/assets/...
        
        if (strpos($src, '/wp-content/themes/pinovilla/assets/') !== false) {
            $source_url = str_replace('/wp-content/themes/pinovilla/assets/', $base_url . '/assets/', $src);
            download_image($source_url);
        }
    }
    
    // Find all background images
    preg_match_all('/url\([\'"]?([^)]+)[\'"]?\)/', $content, $matches);
    foreach ($matches[1] as $url) {
        $url = trim($url, '"\'');
        if (strpos($url, '/wp-content/themes/pinovilla/assets/') !== false) {
            $source_url = str_replace('/wp-content/themes/pinovilla/assets/', $base_url . '/assets/', $url);
            download_image($source_url);
        }
    }
}
echo "Image check complete.\n";
?>
