<?php
// download_content_images.php

$base_url = 'https://pinovilla.com';
$theme_dir = 'wp-content/themes/pinovilla';
$pages = [
    '/',
    '/Rooms',
    '/Villa',
    '/Restaurant',
    '/Halls',
    '/About',
    '/Contact',
    '/Policy',
    '/Terms'
];

function fetch_html($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

foreach ($pages as $path) {
    echo "Scanning $path...\n";
    $html = fetch_html($base_url . $path);
    
    if (!$html) continue;
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $images = $xpath->query('//img/@src');
    
    foreach ($images as $img) {
        $src = $img->nodeValue;
        
        // Check if it's a local asset (starts with /assets/)
        if (strpos($src, '/assets/') === 0) {
            // It's a target asset
            $local_path = $theme_dir . $src;
            $remote_url = $base_url . $src;
            
            // Create dir
            $dir = dirname($local_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Download if missing
            if (!file_exists($local_path)) {
                echo "Downloading $remote_url to $local_path\n";
                $file_content = file_get_contents($remote_url);
                if ($file_content) {
                    file_put_contents($local_path, $file_content);
                } else {
                    echo "Failed to download $remote_url\n";
                }
            }
        }
    }
}

echo "Done downloading content images.\n";
?>
