<?php
function pinovilla_scripts() {
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
    wp_enqueue_script('wow', get_template_directory_uri() . '/assets/Website/js/wow.js', array(), null, true);
    
    // Main script
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/Website/js/script.js', array('jquery'), null, true);
    wp_enqueue_script('events-carousel-js', get_template_directory_uri() . '/assets/Website/js/events-carousel.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'pinovilla_scripts');

function pinovilla_setup() {
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
function pinovilla_room_availability_rewrite() {
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
function pinovilla_room_availability_template( $template ) {
    if ( is_page('roomavalability') || is_page('RoomAvalability') ) {
        $new_template = locate_template('page-roomavalability.php');
        if ( $new_template ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'pinovilla_room_availability_template');
?>
