<?php
/**
 * setup_multilanguage_polylang.php
 *
 * Configures Polylang for Bulgarian, English, Romanian and creates/links page translations.
 *
 * Run (from repo root):
 *   php setup_multilanguage_polylang.php
 *
 * Notes:
 * - This script does NOT do machine translation of HTML content.
 *   It duplicates the source page content into EN/RO so you can edit the translated content in WP Admin.
 */

require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

function pv_exit_with_error(string $message, int $code = 1): void {
    fwrite(STDERR, $message . "\n");
    exit($code);
}

$plugin = 'polylang/polylang.php';
if ( ! is_plugin_active($plugin) ) {
    $result = activate_plugin($plugin);
    if ( is_wp_error($result) ) {
        pv_exit_with_error('Error activating Polylang: ' . $result->get_error_message());
    }
}

echo "Polylang active.\n";

// Ensure languages exist (works even if Polylang functions aren't loaded yet).
$languages = [
    [
        'slug' => 'bg',
        'locale' => 'bg_BG',
        'name' => 'Български',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'bg',
    ],
    [
        'slug' => 'en',
        'locale' => 'en_US',
        'name' => 'English',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'us',
    ],
    [
        'slug' => 'ro',
        'locale' => 'ro_RO',
        'name' => 'Română',
        'rtl' => 0,
        'term_group' => 0,
        'flag_code' => 'ro',
    ],
];

foreach ($languages as $lang) {
    $term = term_exists($lang['slug'], 'language');
    if ( ! $term ) {
        $insert = wp_insert_term($lang['name'], 'language', [
            'slug' => $lang['slug'],
            'description' => serialize($lang),
        ]);
        if ( is_wp_error($insert) ) {
            pv_exit_with_error('Error creating language ' . $lang['slug'] . ': ' . $insert->get_error_message());
        }
        echo "Added language: {$lang['name']} ({$lang['slug']})\n";
    } else {
        echo "Language exists: {$lang['name']} ({$lang['slug']})\n";
    }
}

$options = get_option('polylang');
if ( ! is_array($options) ) {
    $options = [];
}

update_option('polylang', array_merge($options, [
    'default_lang' => 'bg',
    'hide_default' => 0,
    'rewrite' => 1,
    'media_support' => 1,
]));

echo "Polylang configured (default=bg).\n";

if ( ! function_exists('pll_set_post_language') || ! function_exists('pll_save_post_translations') ) {
    pv_exit_with_error('Polylang API functions not available. Ensure Polylang is fully loaded (try visiting WP admin once) and run again.');
}

$page_specs = [
    // slug => [bg, en, ro]
    'home' => ['Начало', 'Home', 'Acasă'],
    'rooms' => ['Стаи', 'Rooms', 'Camere'],
    'villa' => ['Вила', 'Villa', 'Vila'],
    'restaurant' => ['Ресторант', 'Restaurant', 'Restaurant'],
    'halls' => ['Зали', 'Halls', 'Săli'],
    'about' => ['За нас', 'About', 'Despre'],
    'contact' => ['Контакти', 'Contact', 'Contact'],
    'policy' => ['Политика за поверителност', 'Privacy Policy', 'Politica de confidențialitate'],
    'terms' => ['Общи условия', 'Terms & Conditions', 'Termeni și condiții'],
];

function pv_get_or_create_page_by_slug(string $slug, string $title): int {
    $page = get_page_by_path($slug);
    if ( $page ) {
        return (int) $page->ID;
    }

    $id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => '',
    ], true);

    if ( is_wp_error($id) ) {
        pv_exit_with_error('Error creating page ' . $slug . ': ' . $id->get_error_message());
    }

    return (int) $id;
}

function pv_create_or_update_translation(int $source_post_id, string $lang, string $title): int {
    $translated_id = pll_get_post($source_post_id, $lang);
    $source = get_post($source_post_id);
    if ( ! $source ) {
        pv_exit_with_error('Source post not found: ' . $source_post_id);
    }

    $post_data = [
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_content' => $source->post_content,
        'post_excerpt' => $source->post_excerpt,
        'post_parent' => (int) $source->post_parent,
        'menu_order' => (int) $source->menu_order,
    ];

    if ( $translated_id ) {
        $post_data['ID'] = (int) $translated_id;
        $updated = wp_update_post($post_data, true);
        if ( is_wp_error($updated) ) {
            pv_exit_with_error('Error updating translation (' . $lang . ') for post ' . $source_post_id . ': ' . $updated->get_error_message());
        }
        pll_set_post_language((int) $translated_id, $lang);
        return (int) $translated_id;
    }

    $created = wp_insert_post($post_data, true);
    if ( is_wp_error($created) ) {
        pv_exit_with_error('Error creating translation (' . $lang . ') for post ' . $source_post_id . ': ' . $created->get_error_message());
    }

    pll_set_post_language((int) $created, $lang);
    return (int) $created;
}

// Ensure the front page exists and is treated as the Bulgarian home.
$front_page_id = (int) get_option('page_on_front');
if ( $front_page_id <= 0 ) {
    $front_page_id = pv_get_or_create_page_by_slug('home', $page_specs['home'][0]);
    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_page_id);
    echo "Set front page to /home (ID: {$front_page_id}).\n";
}

// Assign language to source pages (bg), then create/link translations.
foreach ($page_specs as $slug => $titles) {
    $bg_title = $titles[0];
    $en_title = $titles[1];
    $ro_title = $titles[2];

    $source_id = ($slug === 'home') ? $front_page_id : pv_get_or_create_page_by_slug($slug, $bg_title);

    pll_set_post_language($source_id, 'bg');

    // Ensure BG title is correct (optional, but keeps labels consistent).
    wp_update_post([
        'ID' => $source_id,
        'post_title' => $bg_title,
    ]);

    $en_id = pv_create_or_update_translation($source_id, 'en', $en_title);
    $ro_id = pv_create_or_update_translation($source_id, 'ro', $ro_title);

    pll_save_post_translations([
        'bg' => $source_id,
        'en' => $en_id,
        'ro' => $ro_id,
    ]);

    echo "Linked translations for {$slug}: bg={$source_id}, en={$en_id}, ro={$ro_id}\n";
}

// Flush rewrite rules to ensure /bg/, /en/, /ro/ routes are registered.
global $wp_rewrite;
$wp_rewrite->flush_rules();

echo "Done.\n";
