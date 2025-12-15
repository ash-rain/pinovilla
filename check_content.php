<?php
require_once('wp-load.php');

$pages = get_pages();
foreach ($pages as $page) {
    echo "ID: " . $page->ID . " | Title: " . $page->post_title . " | Slug: " . $page->post_name . "\n";
    echo "Content Snippet: " . substr(strip_tags($page->post_content), 0, 100) . "...\n";
    echo "---------------------------------------------------\n";
}
?>
