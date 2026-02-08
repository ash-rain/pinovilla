<?php

/**
 * Seed the RoomAvalability page into WordPress.
 *
 * Run with:  php seed_room_availability.php
 */

require_once __DIR__ . '/wp-load.php';

$slug  = 'roomavalability';
$title = 'Room Availability';

// Check if page already exists
$existing = get_page_by_path($slug);
if ($existing) {
    echo "✅ Page '{$title}' already exists (ID: {$existing->ID}, slug: {$slug})\n";
    echo "   URL: " . get_permalink($existing->ID) . "\n";
} else {
    $page_id = wp_insert_post(array(
        'post_title'   => $title,
        'post_name'    => $slug,
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '', // Content is rendered by the page template
        'post_author'  => 1,
    ));

    if (is_wp_error($page_id)) {
        echo "❌ Error creating page: " . $page_id->get_error_message() . "\n";
        exit(1);
    }

    echo "✅ Page '{$title}' created (ID: {$page_id}, slug: {$slug})\n";
}

// Flush rewrite rules so /RoomAvalability works immediately
flush_rewrite_rules();
echo "✅ Rewrite rules flushed.\n";

echo "\n🔗 The page should be accessible at:\n";
echo "   " . home_url('/RoomAvalability') . "\n";
echo "   " . home_url('/roomavalability') . "\n";
echo "\nDone!\n";
