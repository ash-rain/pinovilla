<?php
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Activate Polylang
$plugin = 'polylang/polylang.php';
$result = activate_plugin($plugin);

if (is_wp_error($result)) {
    echo "Error activating Polylang: " . $result->get_error_message() . "\n";
    exit(1);
}

echo "Polylang activated successfully.\n";

// Configure Polylang with Bulgarian (default), English, and Romanian
$options = get_option('polylang');
if (!$options) {
    $options = array();
}

// Add languages
$languages = array(
    array(
        'slug' => 'bg',
        'locale' => 'bg_BG',
        'name' => 'Български',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'bg'
    ),
    array(
        'slug' => 'en',
        'locale' => 'en_US',
        'name' => 'English',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'us'
    ),
    array(
        'slug' => 'ro',
        'locale' => 'ro_RO',
        'name' => 'Română',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'ro'
    )
);

// Create language terms
foreach ($languages as $lang) {
    $term = term_exists($lang['slug'], 'language');
    if (!$term) {
        $term = wp_insert_term($lang['name'], 'language', array(
            'slug' => $lang['slug'],
            'description' => serialize($lang)
        ));
        if (!is_wp_error($term)) {
            echo "Added language: {$lang['name']} ({$lang['slug']})\n";
        }
    } else {
        echo "Language already exists: {$lang['name']} ({$lang['slug']})\n";
    }
}

// Set default language to Bulgarian
update_option('polylang', array_merge($options, array(
    'default_lang' => 'bg',
    'hide_default' => 0,
    'force_lang' => 0,
    'rewrite' => 1,
    'browser' => 0,
    'media_support' => 1,
    'uninstall' => 0
)));

echo "Polylang configured with Bulgarian (default), English, and Romanian.\n";
?>
