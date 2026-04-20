<?php

/**
 * Pino_Content – helpers that surface admin-managed content to the theme.
 *
 * Three buckets:
 *   1. Global settings (single wp_option, assoc array)  → pino_setting($key)
 *   2. Homepage sections (single wp_option, nested)     → pino_content($section, $key [, $lang])
 *   3. i18n translation JSON files                      → pino_i18n($lang)
 *
 * Each helper falls back gracefully so the front-end never explodes when
 * an option is missing — you get an empty string and the existing hard-coded
 * theme text continues to render as a backup.
 */
if (! defined('ABSPATH')) exit;

class Pino_Content
{
    const OPT_SETTINGS = 'pino_settings';
    const OPT_CONTENT  = 'pino_content';

    /** Detect current language, falling back to bg. */
    public static function current_lang()
    {
        if (function_exists('pll_current_language')) {
            $l = pll_current_language('slug');
            if ($l) return $l;
        }
        if (!empty($_COOKIE['pll_language'])) {
            $c = sanitize_text_field($_COOKIE['pll_language']);
            if (in_array($c, ['bg', 'en', 'ro'], true)) return $c;
        }
        return 'bg';
    }

    /* ──────────────────────────────
       GLOBAL SETTINGS
       ────────────────────────────── */
    public static function all_settings()
    {
        $defaults = self::default_settings();
        $stored   = get_option(self::OPT_SETTINGS, []);
        if (!is_array($stored)) $stored = [];
        return array_replace_recursive($defaults, $stored);
    }

    public static function setting($key, $default = '')
    {
        $all = self::all_settings();
        if (strpos($key, '.') === false) {
            return isset($all[$key]) && $all[$key] !== '' ? $all[$key] : $default;
        }
        $parts = explode('.', $key);
        $cur = $all;
        foreach ($parts as $p) {
            if (!is_array($cur) || !isset($cur[$p])) return $default;
            $cur = $cur[$p];
        }
        return ($cur === '' || $cur === null) ? $default : $cur;
    }

    public static function default_settings()
    {
        return [
            'contact' => [
                'phone'         => '+359 885 185 008',
                'phone_display' => '+359 88 51 85 008',
                'email'         => 'info@pinovilla.com',
                'address_bg'    => 'Албена, България',
                'address_en'    => 'Albena, Bulgaria',
                'address_ro'    => 'Albena, Bulgaria',
                'map_url'       => 'https://www.google.com/maps/place/Pino+-+Villa,+Casa,+Cucina+e+Terrazza/@43.3696602,28.0640392,17z',
            ],
            'hours' => [
                'general_bg'    => 'Всеки ден 10:00 - 24:00',
                'general_en'    => 'Every day 10:00 - 24:00',
                'general_ro'    => 'Zilnic 10:00 - 24:00',
                'restaurant_bg' => 'Всеки ден 11:30 - 22:00',
                'restaurant_en' => 'Every day 11:30 - 22:00',
                'restaurant_ro' => 'Zilnic 11:30 - 22:00',
            ],
            'social' => [
                'facebook'  => 'https://www.facebook.com/profile.php?id=61558690053409',
                'instagram' => 'https://www.instagram.com/pinovillaecucina/',
                'youtube'   => '',
                'tiktok'    => '',
            ],
            'booking' => [
                'notify_email'   => 'info@pinovilla.com',
                'auto_confirm'   => 0,
                'min_nights'     => 1,
                'currency_symbol' => '€',
            ],
            'seo' => [
                'title'       => 'Pino - Villa, Casa, Cucina e Terrazza',
                'description' => 'Boutique villa and restaurant near Albena, Bulgaria.',
            ],
        ];
    }

    /* ──────────────────────────────
       HOMEPAGE / PAGE CONTENT
       ────────────────────────────── */
    public static function all_content()
    {
        $stored = get_option(self::OPT_CONTENT, []);
        return is_array($stored) ? $stored : [];
    }

    /**
     * pino_content('hero', 'title', 'en') – returns the stored value or ''.
     * Language fallback: requested → bg → '' (theme hard-coded copy kicks in).
     */
    public static function content($section, $key, $lang = null)
    {
        $lang = $lang ?: self::current_lang();
        $all  = self::all_content();

        if (!isset($all[$section][$key])) return '';

        $val = $all[$section][$key];
        if (is_array($val)) {
            if (!empty($val[$lang])) return $val[$lang];
            if (!empty($val['bg'])) return $val['bg'];
            return '';
        }
        return $val;
    }

    /**
     * Declarative schema for the homepage editor.
     * Each section = group of fields. Each field has a type: 'text' | 'textarea' | 'image' | 'number'.
     * ml = true means multilingual (stored as ['bg'=>..,'en'=>..,'ro'=>..]).
     */
    public static function content_schema()
    {
        return [
            'hero' => [
                'label'  => 'Hero / Banner',
                'fields' => [
                    'image'   => ['label' => 'Hero image', 'type' => 'image'],
                    'heading' => ['label' => 'Heading',    'type' => 'text',     'ml' => true],
                ],
            ],
            'services' => [
                'label'  => 'Services block',
                'fields' => [
                    'subtitle'        => ['label' => 'Subtitle',        'type' => 'text', 'ml' => true],
                    'title'           => ['label' => 'Title',           'type' => 'text', 'ml' => true],
                    'hotel_image'     => ['label' => 'Hotel image',     'type' => 'image'],
                    'restaurant_image' => ['label' => 'Restaurant image', 'type' => 'image'],
                    'villa_image'     => ['label' => 'Villa image',     'type' => 'image'],
                ],
            ],
            'about' => [
                'label'  => 'About section',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text',     'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text',     'ml' => true],
                    'text'     => ['label' => 'Body',     'type' => 'textarea', 'ml' => true],
                    'image_1'  => ['label' => 'Image 1',  'type' => 'image'],
                    'image_2'  => ['label' => 'Image 2',  'type' => 'image'],
                ],
            ],
            'rooms' => [
                'label'  => 'Rooms intro',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text', 'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text', 'ml' => true],
                ],
            ],
            'villa' => [
                'label'  => 'Villa block',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text',     'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text',     'ml' => true],
                    'text'     => ['label' => 'Body',     'type' => 'textarea', 'ml' => true],
                    'image'    => ['label' => 'Image',    'type' => 'image'],
                ],
            ],
            'restaurant' => [
                'label'  => 'Restaurant block',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text',     'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text',     'ml' => true],
                    'text'     => ['label' => 'Body',     'type' => 'textarea', 'ml' => true],
                    'image'    => ['label' => 'Image',    'type' => 'image'],
                ],
            ],
            'halls' => [
                'label'  => 'Halls block',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text',     'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text',     'ml' => true],
                    'text'     => ['label' => 'Body',     'type' => 'textarea', 'ml' => true],
                ],
            ],
            'contact' => [
                'label'  => 'Contact block',
                'fields' => [
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text', 'ml' => true],
                    'title'    => ['label' => 'Title',    'type' => 'text', 'ml' => true],
                ],
            ],
            'footer' => [
                'label'  => 'Footer',
                'fields' => [
                    'notice_kids'        => ['label' => 'Kids notice',         'type' => 'textarea', 'ml' => true],
                    'newsletter_prompt'  => ['label' => 'Newsletter prompt',   'type' => 'textarea', 'ml' => true],
                    'copyright'          => ['label' => 'Copyright line',      'type' => 'text',     'ml' => true],
                ],
            ],
        ];
    }

    /* ──────────────────────────────
       i18n JSON FILES
       ────────────────────────────── */
    public static function i18n_dir()
    {
        $stylesheet = get_stylesheet_directory();
        return trailingslashit($stylesheet) . 'assets/Website/i18n';
    }

    public static function load_i18n($lang)
    {
        $path = self::i18n_dir() . '/' . $lang . '.json';
        if (!file_exists($path)) return [];
        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    public static function save_i18n($lang, $data)
    {
        if (!in_array($lang, ['bg', 'en', 'ro'], true)) return false;
        $path = self::i18n_dir() . '/' . $lang . '.json';
        if (!is_writable(dirname($path))) return false;
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return (bool) file_put_contents($path, $json);
    }

    /** All unique keys across the three languages, sorted. */
    public static function all_i18n_keys()
    {
        $keys = [];
        foreach (['bg', 'en', 'ro'] as $l) {
            $keys = array_merge($keys, array_keys(self::load_i18n($l)));
        }
        $keys = array_values(array_unique($keys));
        sort($keys, SORT_NATURAL | SORT_FLAG_CASE);
        return $keys;
    }
}

/* Convenient global helpers used throughout the theme. */
function pino_setting($key, $default = '')
{
    return Pino_Content::setting($key, $default);
}

function pino_content($section, $key, $lang = null)
{
    return Pino_Content::content($section, $key, $lang);
}

/**
 * Echo a homepage-content value if present, otherwise fall back to the
 * hard-coded string passed in. Keeps the theme safe even when nothing
 * has been saved yet.
 */
function pino_content_or($section, $key, $fallback, $lang = null)
{
    $v = Pino_Content::content($section, $key, $lang);
    echo $v !== '' ? wp_kses_post($v) : $fallback;
}
