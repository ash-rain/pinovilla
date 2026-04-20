<?php

/**
 * Plugin Name: Pino Reservations
 * Plugin URI:  https://pinovilla.com
 * Description: Custom reservation system for Pino Villa — rooms, apartments & villa booking, plus site content, global settings and i18n translations editable from the admin.
 * Version:     1.3.0
 * Author:      CodeMode
 * Text Domain: pino-reservations
 */

if (! defined('ABSPATH')) exit;

define('PINO_RES_VERSION', '1.3.0');
define('PINO_RES_DIR', plugin_dir_path(__FILE__));
define('PINO_RES_URL', plugin_dir_url(__FILE__));

/* ──────────────────────────────────────────────
   ACTIVATION + UPGRADE — create / evolve DB tables
   ────────────────────────────────────────────── */
register_activation_hook(__FILE__, 'pino_res_activate');

function pino_res_activate()
{
    pino_res_install_or_upgrade();

    global $wpdb;
    $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}pino_room_types");
    if ($count === 0) {
        pino_res_seed_defaults();
    }

    update_option('pino_res_db_version', PINO_RES_VERSION);
}

/**
 * Runs on every request (cheap — just a version compare).
 * If the stored DB version doesn't match the plugin version, run dbDelta
 * so admins don't need to deactivate/reactivate after a code update.
 */
add_action('plugins_loaded', function () {
    if (get_option('pino_res_db_version') !== PINO_RES_VERSION) {
        pino_res_install_or_upgrade();
        global $wpdb;
        if ((int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}pino_menu_items") === 0) {
            pino_res_seed_menu_items();
        }
        update_option('pino_res_db_version', PINO_RES_VERSION);
    }
});

function pino_res_install_or_upgrade()
{
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    /* 1. Room types — extended with per-language long descriptions, gallery, amenities */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_room_types (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name_bg     VARCHAR(255) NOT NULL DEFAULT '',
        name_en     VARCHAR(255) NOT NULL DEFAULT '',
        name_ro     VARCHAR(255) NOT NULL DEFAULT '',
        desc_bg     TEXT,
        desc_en     TEXT,
        desc_ro     TEXT,
        capacity    INT UNSIGNED NOT NULL DEFAULT 2,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        photo       VARCHAR(500) DEFAULT NULL,
        gallery     TEXT,
        amenities   TEXT,
        sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset;";
    dbDelta($sql);

    /* 2. Physical rooms — with notes */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_rooms (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name        VARCHAR(255) NOT NULL DEFAULT '',
        room_type_id INT UNSIGNED NOT NULL,
        floor       INT NOT NULL DEFAULT 1,
        notes       TEXT,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        KEY idx_type (room_type_id)
    ) $charset;";
    dbDelta($sql);

    /* 3. Meals */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_meals (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name_bg     VARCHAR(255) NOT NULL DEFAULT '',
        name_en     VARCHAR(255) NOT NULL DEFAULT '',
        name_ro     VARCHAR(255) NOT NULL DEFAULT '',
        desc_bg     TEXT,
        desc_en     TEXT,
        desc_ro     TEXT,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset;";
    dbDelta($sql);

    /* 4. Reservations */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_reservations (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        first_name  VARCHAR(255) NOT NULL DEFAULT '',
        last_name   VARCHAR(255) NOT NULL DEFAULT '',
        email       VARCHAR(255) NOT NULL DEFAULT '',
        phone       VARCHAR(100) NOT NULL DEFAULT '',
        start_date  DATE NOT NULL,
        end_date    DATE NOT NULL,
        guests      INT UNSIGNED NOT NULL DEFAULT 1,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        status      TINYINT NOT NULL DEFAULT 0,
        notes       TEXT,
        created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_dates (start_date, end_date),
        KEY idx_status (status)
    ) $charset;";
    dbDelta($sql);

    /* 5. Reservation details (room allocations) */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_reservation_details (
        id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
        reservation_id  INT UNSIGNED NOT NULL,
        room_type_id    INT UNSIGNED NOT NULL,
        room_id         INT UNSIGNED DEFAULT NULL,
        nights          INT UNSIGNED NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        KEY idx_res (reservation_id)
    ) $charset;";
    dbDelta($sql);

    /* 6. Reservation meals */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_reservation_meals (
        id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
        reservation_id  INT UNSIGNED NOT NULL,
        meal_id         INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        KEY idx_res (reservation_id)
    ) $charset;";
    dbDelta($sql);

    /* 7. Restaurant menu items */
    $sql = "CREATE TABLE {$wpdb->prefix}pino_menu_items (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        category_bg VARCHAR(100) NOT NULL DEFAULT '',
        category_en VARCHAR(100) NOT NULL DEFAULT '',
        category_ro VARCHAR(100) NOT NULL DEFAULT '',
        name_bg     VARCHAR(255) NOT NULL DEFAULT '',
        name_en     VARCHAR(255) NOT NULL DEFAULT '',
        name_ro     VARCHAR(255) NOT NULL DEFAULT '',
        desc_bg     TEXT,
        desc_en     TEXT,
        desc_ro     TEXT,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset;";
    dbDelta($sql);
}

function pino_res_seed_defaults()
{
    global $wpdb;
    $tbl_types = $wpdb->prefix . 'pino_room_types';
    $tbl_rooms = $wpdb->prefix . 'pino_rooms';
    $tbl_meals = $wpdb->prefix . 'pino_meals';

    /* Room Types */
    $types = [
        ['name_bg' => 'Икономична стая',   'name_en' => 'Economic Room',   'name_ro' => 'Cameră Economică',    'capacity' => 2, 'price' => 194.00, 'sort_order' => 1],
        ['name_bg' => 'Двойна стая',        'name_en' => 'Double Room',     'name_ro' => 'Cameră Dublă',        'capacity' => 2, 'price' => 207.00, 'sort_order' => 2],
        ['name_bg' => 'Супериорна стая',    'name_en' => 'Superior Room',   'name_ro' => 'Cameră Superioară',   'capacity' => 2, 'price' => 219.00, 'sort_order' => 3],
        ['name_bg' => 'Бутикова стая',      'name_en' => 'Boutique Room',   'name_ro' => 'Cameră Boutique',     'capacity' => 2, 'price' => 238.00, 'sort_order' => 4],
        ['name_bg' => 'Апартамент 1',       'name_en' => 'Apartment 1',     'name_ro' => 'Apartament 1',        'capacity' => 4, 'price' => 257.00, 'sort_order' => 5],
        ['name_bg' => 'Апартамент 2',       'name_en' => 'Apartment 2',     'name_ro' => 'Apartament 2',        'capacity' => 4, 'price' => 257.00, 'sort_order' => 6],
        ['name_bg' => 'Самостоятелна къща', 'name_en' => 'Villa Pino Casa', 'name_ro' => 'Vila Pino Casa',      'capacity' => 6, 'price' => 700.00, 'sort_order' => 7],
    ];

    $type_ids = [];
    foreach ($types as $t) {
        $wpdb->insert($tbl_types, $t);
        $type_ids[] = $wpdb->insert_id;
    }

    $rooms = [
        ['name' => 'EC-101', 'room_type_id' => $type_ids[0], 'floor' => 1],
        ['name' => 'EC-102', 'room_type_id' => $type_ids[0], 'floor' => 1],
        ['name' => 'EC-201', 'room_type_id' => $type_ids[0], 'floor' => 2],
        ['name' => 'DB-103', 'room_type_id' => $type_ids[1], 'floor' => 1],
        ['name' => 'DB-104', 'room_type_id' => $type_ids[1], 'floor' => 1],
        ['name' => 'DB-202', 'room_type_id' => $type_ids[1], 'floor' => 2],
        ['name' => 'SP-203', 'room_type_id' => $type_ids[2], 'floor' => 2],
        ['name' => 'SP-204', 'room_type_id' => $type_ids[2], 'floor' => 2],
        ['name' => 'SP-205', 'room_type_id' => $type_ids[2], 'floor' => 2],
        ['name' => 'BT-301', 'room_type_id' => $type_ids[3], 'floor' => 3],
        ['name' => 'BT-302', 'room_type_id' => $type_ids[3], 'floor' => 3],
        ['name' => 'BT-303', 'room_type_id' => $type_ids[3], 'floor' => 3],
        ['name' => 'AP1-401', 'room_type_id' => $type_ids[4], 'floor' => 4],
        ['name' => 'AP2-402', 'room_type_id' => $type_ids[5], 'floor' => 4],
        ['name' => 'VILLA-1', 'room_type_id' => $type_ids[6], 'floor' => 1],
    ];

    foreach ($rooms as $r) {
        $wpdb->insert($tbl_rooms, $r);
    }

    $meals = [
        ['name_bg' => 'Закуска',       'name_en' => 'Breakfast',          'name_ro' => 'Mic dejun',         'price' => 25.00, 'sort_order' => 1],
        ['name_bg' => 'Обяд',          'name_en' => 'Lunch',              'name_ro' => 'Prânz',             'price' => 35.00, 'sort_order' => 2],
        ['name_bg' => 'Вечеря',        'name_en' => 'Dinner',             'name_ro' => 'Cină',              'price' => 40.00, 'sort_order' => 3],
        ['name_bg' => 'Пълен пансион', 'name_en' => 'Full Board',         'name_ro' => 'Pensiune completă', 'price' => 85.00, 'sort_order' => 4],
    ];

    foreach ($meals as $m) {
        $wpdb->insert($tbl_meals, $m);
    }
}

function pino_res_seed_menu_items()
{
    global $wpdb;
    $tbl = $wpdb->prefix . 'pino_menu_items';

    $items = [
        [
            'category_bg' => 'СТАРТЕРИ', 'category_en' => 'STARTERS', 'category_ro' => 'STARTERS',
            'name_bg' => 'Салата „Бурата с трюфел"', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'изкусителна комбинация от сирене „Бурата", обогатено с аромат на трюфел, поднесено с рукола, зелен лук, бели и/или чери домати, сфери от балсамов оцет и лиофилизирани домати и прах от маслини, прибавящ дълбочина на вкуса',
            'desc_en' => '', 'desc_ro' => '', 'price' => 10.17, 'sort_order' => 1,
        ],
        [
            'category_bg' => 'ТОПЛИ ПРЕДЯСТИЯ', 'category_en' => 'WARM STARTERS', 'category_ro' => '',
            'name_bg' => 'Гъши дроб със зелена ябълка, фламбирани с коняк „Калвадос"', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'нежен и деликатен гъши дроб, съчетан със свежестта на зелена ябълка и фламбиран с изискан „Калвадос"',
            'desc_en' => '', 'desc_ro' => '', 'price' => 14.27, 'sort_order' => 2,
        ],
        [
            'category_bg' => 'ТОПЛИ ПРЕДЯСТИЯ', 'category_en' => 'WARM STARTERS', 'category_ro' => '',
            'name_bg' => 'Гьози със свинско месо, поднесени с гъши дроб и сос с трюфели', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'традиционни гьози, пълнени със свинско месо, поднесени в съвършенство с хрупкава текстура и обогатени със специалния вкус на гъши дроб, сфери от соев сос и ароматен сос с трюфели',
            'desc_en' => '', 'desc_ro' => '', 'price' => 13.24, 'sort_order' => 3,
        ],
        [
            'category_bg' => 'ПАСТА', 'category_en' => 'PASTA', 'category_ro' => 'PASTA',
            'name_bg' => 'Талиатели „Пармиджана"', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'приготвени в пармезанова пита, гарнирани с гуанчале и пипер меланж',
            'desc_en' => '', 'desc_ro' => '', 'price' => 9.15, 'sort_order' => 4,
        ],
        [
            'category_bg' => 'ОСНОВНИ', 'category_en' => 'MAIN DISHES', 'category_ro' => 'MAIN DISHES',
            'name_bg' => 'Виенски шницел с билково масло и чипс', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'класически виенски шницел от начукано телешко месо с хрупкава златиста коричка, поднесен с ароматно билково масло, придаващо свежи и изискани нотки, и хрупкав чипс',
            'desc_en' => '', 'desc_ro' => '', 'price' => 16.36, 'sort_order' => 5,
        ],
        [
            'category_bg' => 'ОСНОВНИ', 'category_en' => 'MAIN DISHES', 'category_ro' => '',
            'name_bg' => 'Филе от лаврак с лимоново ризото и пармезанов чипс', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'филе от лаврак, съчетано с ароматно лимоново ризото, хрупкав пармезанов чипс, лимонови сфери и ароматно билково олио',
            'desc_en' => '', 'desc_ro' => '', 'price' => 15.29, 'sort_order' => 6,
        ],
        [
            'category_bg' => 'ОСНОВНИ', 'category_en' => 'MAIN DISHES', 'category_ro' => '',
            'name_bg' => 'Патешко магре с пюре от карфиол и портокалов гастрик', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'нежно патешко магре с хрупкава кожичка, поднесено върху фино пюре с карфиол и допълнено от портокаловия гастрик, осигуряващ леко кисела и сладка нотка',
            'desc_en' => '', 'desc_ro' => '', 'price' => 15.29, 'sort_order' => 7,
        ],
        [
            'category_bg' => 'ДЕСЕРТИ', 'category_en' => 'DESSERTS', 'category_ro' => 'DESSERTS',
            'name_bg' => 'Златно „Матча Тирамису"', 'name_en' => '', 'name_ro' => '',
            'desc_bg' => 'традиционният италиански десерт „Тирамису", получаващ вълнуващ азиатски обрат с добавянето на матча и юзу',
            'desc_en' => '', 'desc_ro' => '', 'price' => 7.11, 'sort_order' => 8,
        ],
    ];

    foreach ($items as $item) {
        $wpdb->insert($tbl, $item);
    }
}

/* ──────────────────────────────────────────────
   INCLUDES
   ────────────────────────────────────────────── */
require_once PINO_RES_DIR . 'includes/class-pino-db.php';
require_once PINO_RES_DIR . 'includes/class-pino-availability.php';
require_once PINO_RES_DIR . 'includes/class-pino-content.php';

if (is_admin()) {
    require_once PINO_RES_DIR . 'admin/class-pino-admin.php';
    require_once PINO_RES_DIR . 'admin/class-pino-settings.php';
}

require_once PINO_RES_DIR . 'admin/class-pino-customizer.php';

require_once PINO_RES_DIR . 'public/class-pino-public.php';
