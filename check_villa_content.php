<?php
require_once('wp-load.php');

$page = get_page_by_path('villa');
if ($page) {
    echo "ID: " . $page->ID . " | Title: " . $page->post_title . "\n";
    echo $page->post_content . "\n";
} else {
    echo "Villa page not found.\n";
}
?>
