<?php
// seeder.php
// Run with: wp eval-file seeder.php

require_once('wp-load.php');

$base_url = 'https://pinovilla.com';
$theme_dir = 'wp-content/themes/pinovilla';
$pages_to_clone = [
    '/' => 'Home',
    '/Rooms' => 'Rooms',
    '/Villa' => 'Villa',
    '/Restaurant' => 'Restaurant',
    '/Halls' => 'Halls',
    '/About' => 'About',
    '/Contact' => 'Contact',
    '/Policy' => 'Policy',
    '/Terms' => 'Terms'
];

function fetch_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function download_image($url) {
    global $theme_dir, $base_url;
    
    // Normalize URL
    if (strpos($url, 'http') !== 0) {
        $url = $base_url . $url;
    }
    
    // Check if it's a local asset
    if (strpos($url, $base_url . '/assets/') === 0) {
        $rel_path = str_replace($base_url, '', $url);
        $local_path = $theme_dir . $rel_path;
        
        $dir = dirname($local_path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!file_exists($local_path)) {
            echo "Downloading $url to $local_path\n";
            $file_content = file_get_contents($url);
            if ($file_content) {
                file_put_contents($local_path, $file_content);
            }
        }
    }
}

function extract_inner_html($node) {
    $innerHTML = '';
    $children = $node->childNodes;
    foreach ($children as $child) {
        $innerHTML .= $node->ownerDocument->saveHTML($child);
    }
    return $innerHTML;
}

function process_html_dom($html, $page_title) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    // Hack to handle UTF-8 correctly
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);

    // 1. Extract Main Content
    $main = $xpath->query('//main[@role="main"]')->item(0);
    $content = '';
    if ($main) {
        $content .= extract_inner_html($main);
    }

    // 1.1 Check for Contact Section (often outside main)
    $contactSection = $xpath->query('//section[contains(@class, "contact-section")]')->item(0);
    if ($contactSection) {
        // Check if it's already inside main (to avoid duplication if logic changes)
        // Simple check: if main contains it. But main is already processed.
        // Let's just check if it was part of the main content string.
        // Actually, simpler: just append it if it's not a child of main.
        if (!$main || !$main->contains($contactSection)) {
             // Just save the section itself
             $content .= $dom->saveHTML($contactSection);
        }
    }

    if (empty($content)) return '';

    // Re-load content into DOM to process removals/images on the full set
    $dom2 = new DOMDocument();
    libxml_use_internal_errors(true);
    @$dom2->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();
    $xpath2 = new DOMXPath($dom2);
    $root = $dom2->documentElement; // This will be html/body wrapper added by loadHTML

    // 2. Remove Elements based on page
    if ($page_title === 'Halls') {
        // Remove prices
        $prices = $xpath2->query('.//*[@class="pricing-amount"]');
        foreach ($prices as $price) {
            $price->parentNode->removeChild($price);
        }
    }
    
    if ($page_title === 'Restaurant') {
        // Remove menu sections
        $sections = $xpath2->query('.//section[contains(@class, "pricing-section")]');
        foreach ($sections as $section) {
             $section->parentNode->removeChild($section);
        }
    }

    // 3. Download Images (img tags and inline styles)
    
    // Img tags
    $imgs = $xpath2->query('.//img');
    foreach ($imgs as $img) {
        $src = $img->getAttribute('src');
        if ($src) download_image($src);
    }
    
    // Inline styles (background-image)
    $elements = $xpath2->query('.//*[@style]');
    foreach ($elements as $element) {
        $style = $element->getAttribute('style');
        if (preg_match('/url\([\'"]?([^)]+)[\'"]?\)/', $style, $matches)) {
            $url = $matches[1];
            $url = trim($url, '"\'');
            download_image($url);
        }
    }

    // Return processed content (body content of dom2)
    // loadHTML adds html/body. We want inner of body.
    $body = $dom2->getElementsByTagName('body')->item(0);
    if ($body) {
        return extract_inner_html($body);
    } else {
        // Fallback: if no body (e.g. content was parsed into head due to malformed HTML), return everything
        return $dom2->saveHTML();
    }
}

function process_content_string($content) {
    // Replace asset paths
    // Normalize relative paths first
    $content = str_replace('./assets/', '/assets/', $content);
    // Then replace absolute /assets/ with theme path
    $content = str_replace('/assets/', '/wp-content/themes/pinovilla/assets/', $content);
    
    // Regex replacements for links
    $content = preg_replace('/href="\/Rooms([^"]*)"/i', 'href="/rooms$1"', $content);
    $content = preg_replace('/href="\/Villa([^"]*)"/i', 'href="/villa$1"', $content);
    $content = preg_replace('/href="\/Restaurant([^"]*)"/i', 'href="/restaurant$1"', $content);
    $content = preg_replace('/href="\/Halls([^"]*)"/i', 'href="/halls$1"', $content);
    $content = preg_replace('/href="\/About([^"]*)"/i', 'href="/about$1"', $content);
    $content = preg_replace('/href="\/Contact([^"]*)"/i', 'href="/contact$1"', $content);
    $content = preg_replace('/href="\/Policy([^"]*)"/i', 'href="/policy$1"', $content);
    $content = preg_replace('/href="\/Terms([^"]*)"/i', 'href="/terms$1"', $content);
    
    // Replace Contact Form with Shortcode
    // ID 47 is the Contact Form 7 post we created
    $shortcode = '[contact-form-7 id="47" title="Contact Form Main"]';
    
    // Regex for Contact page form
    $content = preg_replace('/<form[^>]*id="contact_form"[^>]*>.*?<\/form>/is', $shortcode, $content);
    
    // Regex for About page form (action="/Contact")
    $content = preg_replace('/<form[^>]*action="\/Contact"[^>]*>.*?<\/form>/is', $shortcode, $content);
    
    return $content;
}

foreach ($pages_to_clone as $path => $title) {
    echo "Processing $title ($path)...\n";
    
    $url = $base_url . $path;
    $html = fetch_content($url);
    
    if (empty($html)) {
        echo "Failed to fetch $url\n";
        continue;
    }
    
    $content = process_html_dom($html, $title);
    
    if (empty($content)) {
        echo "Warning: No content extracted for $title\n";
    }
    
    $content = process_content_string($content);
    
    $slug = basename($path) ?: 'home';
    $slug = strtolower($slug);
    
    // Check if page exists
    $page = get_page_by_path($slug);
    
    $post_data = [
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'post_name'     => $slug
    ];
    
    if ($title === 'Contact') {
        if (strpos($content, '<form') !== false) {
            echo "DEBUG: Form found in content for Contact page before save.\n";
        } else {
            echo "DEBUG: Form NOT found in content for Contact page before save.\n";
            echo "DEBUG: Content length: " . strlen($content) . "\n";
        }
    }
    
    if ($page) {
        $post_data['ID'] = $page->ID;
        wp_update_post($post_data);
        echo "Updated page: $title (ID: {$page->ID})\n";
        $post_id = $page->ID;
    } else {
        $post_id = wp_insert_post($post_data);
        echo "Created page: $title (ID: $post_id)\n";
    }
    
    if ($path === '/') {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $post_id);
    }
}

// Flush rewrite rules
global $wp_rewrite;
$wp_rewrite->set_permalink_structure('/%postname%/');
$wp_rewrite->flush_rules();
echo "Rewrite rules flushed.\n";

echo "Seeding complete.\n";
?>
