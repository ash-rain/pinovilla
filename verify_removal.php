<?php
require_once('wp-load.php');

$rooms = get_page_by_path('rooms');
if ($rooms) {
    if (strpos($rooms->post_content, 'pricing-amount') !== false) {
        echo "FAIL: 'pricing-amount' found in Rooms page.\n";
    } else {
        echo "PASS: 'pricing-amount' NOT found in Rooms page.\n";
    }
} else {
    echo "FAIL: Rooms page not found.\n";
}

$restaurant = get_page_by_path('restaurant');
if ($restaurant) {
    // Check for menu items or pricing section
    // The seeder removed sections with class 'pricing-section'
    if (strpos($restaurant->post_content, 'pricing-section') !== false) {
        echo "WARNING: 'pricing-section' found in Restaurant page. Checking if it contains menu items...\n";
        // This might be false positive if there are other pricing sections.
        // Let's check for a known menu item like "MAIN DISHES" or "ОСНОВНИ"
        if (strpos($restaurant->post_content, 'ОСНОВНИ') !== false) {
             echo "FAIL: Menu item 'ОСНОВНИ' found in Restaurant page.\n";
        } else {
             echo "PASS: Menu items seem to be removed.\n";
        }
    } else {
        echo "PASS: 'pricing-section' NOT found in Restaurant page.\n";
    }
} else {
    echo "FAIL: Restaurant page not found.\n";
}
?>
