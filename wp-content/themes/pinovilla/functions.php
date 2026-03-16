<?php
function pinovilla_scripts()
{
    // CSS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/Website/css/bootstrap.min.css');
    wp_enqueue_style('flatpickr', get_template_directory_uri() . '/assets/Website/css/flatpickr.min.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/assets/Website/css/style.css');
    wp_enqueue_style('pino', get_template_directory_uri() . '/assets/Website/css/pino.css');
    wp_enqueue_style('slick-theme', get_template_directory_uri() . '/assets/Website/css/slick-theme.css');
    wp_enqueue_style('slick', get_template_directory_uri() . '/assets/Website/css/slick.css');
    wp_enqueue_style('pino-reservation', get_template_directory_uri() . '/assets/Website/css/pino-reservation.css');
    wp_enqueue_style('availability', get_template_directory_uri() . '/assets/Website/css/availability.css');

    wp_enqueue_style('events-carousel', get_template_directory_uri() . '/assets/Website/css/events-carousel.css');

    // JS
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/Website/js/jquery.js', array(), null, true);

    wp_enqueue_script('popper', get_template_directory_uri() . '/assets/Website/js/popper.min.js', array('jquery'), null, true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/Website/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('appear', get_template_directory_uri() . '/assets/Website/js/appear.js', array('jquery'), null, true);
    wp_enqueue_script('gsap', get_template_directory_uri() . '/assets/Website/js/gsap.min.js', array(), null, true);
    wp_enqueue_script('jquery-fancybox', get_template_directory_uri() . '/assets/Website/js/jquery.fancybox.js', array('jquery'), null, true);
    wp_enqueue_script('mixitup', get_template_directory_uri() . '/assets/Website/js/mixitup.js', array('jquery'), null, true);
    wp_enqueue_script('script-gsap', get_template_directory_uri() . '/assets/Website/js/script-gsap.js', array('gsap'), null, true);
    wp_enqueue_script('scrolltrigger', get_template_directory_uri() . '/assets/Website/js/ScrollTrigger.min.js', array('gsap'), null, true);
    wp_enqueue_script('slick-animation', get_template_directory_uri() . '/assets/Website/js/slick-animation.min.js', array('jquery'), null, true);
    wp_enqueue_script('slick', get_template_directory_uri() . '/assets/Website/js/slick.min.js', array('jquery'), null, true);
    wp_enqueue_script('splittext', get_template_directory_uri() . '/assets/Website/js/SplitText.min.js', array(), null, true);
    wp_enqueue_script('splittype', get_template_directory_uri() . '/assets/Website/js/splitType.js', array(), null, true);
    wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/Website/js/swiper.min.js', array(), null, true);
    wp_enqueue_script('translate', get_template_directory_uri() . '/assets/Website/js/translate.js', array(), null, true);
    wp_localize_script('translate', 'pinoI18n', array(
        'basePath' => get_template_directory_uri() . '/assets/Website/i18n',
    ));
    wp_enqueue_script('wow', get_template_directory_uri() . '/assets/Website/js/wow.js', array(), null, true);

    // Main script
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/Website/js/script.js', array('jquery'), null, true);
    wp_enqueue_script('events-carousel-js', get_template_directory_uri() . '/assets/Website/js/events-carousel.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'pinovilla_scripts');

function pinovilla_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'pinovilla'),
    ));
}
add_action('after_setup_theme', 'pinovilla_setup');

/**
 * Case-insensitive rewrite for /RoomAvalability → roomavalability page
 * This lets /RoomAvalability, /roomavalability, /roomAvailability all resolve
 */
function pinovilla_room_availability_rewrite()
{
    add_rewrite_rule(
        '^[Rr]oom[Aa]val[Aa]bility/?$',
        'index.php?pagename=roomavalability',
        'top'
    );
}
add_action('init', 'pinovilla_room_availability_rewrite');

/**
 * Load the page-roomavalability.php template for the roomavalability page slug
 */
function pinovilla_room_availability_template($template)
{
    if (is_page('roomavalability') || is_page('RoomAvalability')) {
        $new_template = locate_template('page-roomavalability.php');
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'pinovilla_room_availability_template');

/**
 * Render the BG | EN | RO language switcher.
 * When Polylang is active, links point to translated page URLs.
 * When Polylang is inactive, links trigger client-side JS translation.
 * All links include data-lang for the JS translator cookie integration.
 */
function pinovilla_lang_switcher()
{
    $li_style = 'padding-left: 12px; display: flex; justify-content: center; align-items: center;';
    $sep_style = 'padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center; color: white;';
    $a_style = 'font-size: 12px; color: white;';

    $out = '';

    if (function_exists('pll_the_languages')) {
        $langs = pll_the_languages(array('raw' => 1));
        $first = true;
        foreach ($langs as $lang) {
            if (!$first) {
                $out .= '<li style="' . $sep_style . '">|</li>';
            }
            $first = false;
            $slug = esc_attr($lang['slug']);
            $url = esc_url($lang['url']);
            $label = esc_html(strtoupper($lang['slug']));
            $out .= '<li style="' . $li_style . '">'
                . '<a href="' . $url . '" data-lang="' . $slug . '" style="' . $a_style . '">' . $label . '</a>'
                . '</li>';
        }
    } else {
        $fallback_langs = array(
            'bg' => 'BG',
            'en' => 'EN',
            'ro' => 'RO',
        );
        $first = true;
        foreach ($fallback_langs as $slug => $label) {
            if (!$first) {
                $out .= '<li style="' . $sep_style . '">|</li>';
            }
            $first = false;
            $out .= '<li style="' . $li_style . '">'
                . '<a href="#" class="lang-switch" data-lang="' . esc_attr($slug) . '" style="' . $a_style . '">' . esc_html($label) . '</a>'
                . '</li>';
        }
    }

    return $out;
}

/**
 * Server-side language redirect based on cookie.
 * When a visitor has a pll_language cookie and Polylang is active,
 * redirect them to the correct language version of the current page.
 */
function pinovilla_language_cookie_redirect()
{
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return;
    }

    if (!function_exists('pll_current_language') || !function_exists('pll_get_post')) {
        return;
    }

    $cookie_lang = isset($_COOKIE['pll_language']) ? sanitize_text_field($_COOKIE['pll_language']) : '';
    if (!$cookie_lang || !in_array($cookie_lang, array('bg', 'en', 'ro'), true)) {
        return;
    }

    $current_lang = pll_current_language('slug');
    if ($current_lang === $cookie_lang) {
        return;
    }

    $current_post_id = get_queried_object_id();
    if (!$current_post_id) {
        return;
    }

    $translated_id = pll_get_post($current_post_id, $cookie_lang);
    if (!$translated_id || $translated_id === $current_post_id) {
        return;
    }

    $translated_url = get_permalink($translated_id);
    if ($translated_url) {
        wp_safe_redirect($translated_url, 302);
        exit;
    }
}
add_action('template_redirect', 'pinovilla_language_cookie_redirect');
