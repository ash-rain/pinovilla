<?php
require_once('wp-load.php');

// Get About page
$page = get_page_by_path('about');

echo "=== DEBUG: About Page Content ===\n";
echo "Page ID: " . $page->ID . "\n";
echo "Content Length: " . strlen($page->post_content) . "\n";
echo "Has contact-section: " . (strpos($page->post_content, 'contact-section') !== false ? 'YES' : 'NO') . "\n";

// Try to process content through WordPress filters
echo "\n=== Testing the_content filter ===\n";
$content = apply_filters('the_content', $page->post_content);
echo "Filtered Content Length: " . strlen($content) . "\n";
echo "Has contact-section after filter: " . (strpos($content, 'contact-section') !== false ? 'YES' : 'NO') . "\n";

// Check last 1000 chars
echo "\n=== Last 1000 chars of filtered content ===\n";
echo substr($content, -1000);
?>
