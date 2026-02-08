<?php

/**
 * Plugin Name: Pino Reservations
 * Plugin URI:  https://pinovilla.com
 * Description: Custom reservation system for Pino Villa – rooms, apartments & villa booking with admin panel.
 * Version:     1.0.0
 * Author:      CodeMode
 * Text Domain: pino-reservations
 */

if (! defined('ABSPATH')) exit;

define('PINO_RES_VERSION', '1.0.0');
define('PINO_RES_DIR', plugin_dir_path(__FILE__));
define('PINO_RES_URL', plugin_dir_url(__FILE__));

/* ──────────────────────────────────────────────
   ACTIVATION — create DB tables & seed defaults
   ────────────────────────────────────────────── */
register_activation_hook(__FILE__, 'pino_res_activate');

function pino_res_activate()
{
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    /* 1. Room types */
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_room_types (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name_bg     VARCHAR(255) NOT NULL DEFAULT '',
        name_en     VARCHAR(255) NOT NULL DEFAULT '',
        name_ro     VARCHAR(255) NOT NULL DEFAULT '',
        capacity    INT UNSIGNED NOT NULL DEFAULT 2,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        photo       VARCHAR(500) DEFAULT NULL,
        sort_order  INT UNSIGNED NOT NULL DEFAULT 0,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset;";
    dbDelta($sql);

    /* 2. Rooms (individual physical rooms) */
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_rooms (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name        VARCHAR(255) NOT NULL DEFAULT '',
        room_type_id INT UNSIGNED NOT NULL,
        floor       INT NOT NULL DEFAULT 1,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id),
        KEY idx_type (room_type_id)
    ) $charset;";
    dbDelta($sql);

    /* 3. Meals */
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_meals (
        id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name_bg     VARCHAR(255) NOT NULL DEFAULT '',
        name_en     VARCHAR(255) NOT NULL DEFAULT '',
        name_ro     VARCHAR(255) NOT NULL DEFAULT '',
        desc_bg     TEXT,
        desc_en     TEXT,
        desc_ro     TEXT,
        price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        visible     TINYINT(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset;";
    dbDelta($sql);

    /* 4. Reservations */
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_reservations (
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
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_reservation_details (
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
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}pino_reservation_meals (
        id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
        reservation_id  INT UNSIGNED NOT NULL,
        meal_id         INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        KEY idx_res (reservation_id)
    ) $charset;";
    dbDelta($sql);

    /* Seed default room types if table is empty */
    $count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}pino_room_types");
    if ($count === 0) {
        pino_res_seed_defaults();
    }

    update_option('pino_res_db_version', PINO_RES_VERSION);
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

    /* Physical rooms - based on 15 rooms + 1 villa */
    $rooms = [
        // Economic rooms (type 1) – 3 rooms
        ['name' => 'EC-101', 'room_type_id' => $type_ids[0], 'floor' => 1],
        ['name' => 'EC-102', 'room_type_id' => $type_ids[0], 'floor' => 1],
        ['name' => 'EC-201', 'room_type_id' => $type_ids[0], 'floor' => 2],
        // Double rooms (type 2) – 3 rooms
        ['name' => 'DB-103', 'room_type_id' => $type_ids[1], 'floor' => 1],
        ['name' => 'DB-104', 'room_type_id' => $type_ids[1], 'floor' => 1],
        ['name' => 'DB-202', 'room_type_id' => $type_ids[1], 'floor' => 2],
        // Superior rooms (type 3) – 3 rooms
        ['name' => 'SP-203', 'room_type_id' => $type_ids[2], 'floor' => 2],
        ['name' => 'SP-204', 'room_type_id' => $type_ids[2], 'floor' => 2],
        ['name' => 'SP-205', 'room_type_id' => $type_ids[2], 'floor' => 2],
        // Boutique rooms (type 4) – 3 rooms
        ['name' => 'BT-301', 'room_type_id' => $type_ids[3], 'floor' => 3],
        ['name' => 'BT-302', 'room_type_id' => $type_ids[3], 'floor' => 3],
        ['name' => 'BT-303', 'room_type_id' => $type_ids[3], 'floor' => 3],
        // Apartment 1 (type 5)
        ['name' => 'AP1-401', 'room_type_id' => $type_ids[4], 'floor' => 4],
        // Apartment 2 (type 6)
        ['name' => 'AP2-402', 'room_type_id' => $type_ids[5], 'floor' => 4],
        // Villa (type 7)
        ['name' => 'VILLA-1', 'room_type_id' => $type_ids[6], 'floor' => 1],
    ];

    foreach ($rooms as $r) {
        $wpdb->insert($tbl_rooms, $r);
    }

    /* Meals */
    $meals = [
        ['name_bg' => 'Закуска',       'name_en' => 'Breakfast',          'name_ro' => 'Mic dejun',       'price' => 25.00],
        ['name_bg' => 'Обяд',          'name_en' => 'Lunch',              'name_ro' => 'Prânz',           'price' => 35.00],
        ['name_bg' => 'Вечеря',        'name_en' => 'Dinner',             'name_ro' => 'Cină',            'price' => 40.00],
        ['name_bg' => 'Пълен пансион', 'name_en' => 'Full Board',         'name_ro' => 'Pensiune completă', 'price' => 85.00],
    ];

    foreach ($meals as $m) {
        $wpdb->insert($tbl_meals, $m);
    }
}

/* ──────────────────────────────────────────────
   INCLUDES
   ────────────────────────────────────────────── */
require_once PINO_RES_DIR . 'includes/class-pino-db.php';
require_once PINO_RES_DIR . 'includes/class-pino-availability.php';

if (is_admin()) {
    require_once PINO_RES_DIR . 'admin/class-pino-admin.php';
}

require_once PINO_RES_DIR . 'public/class-pino-public.php';
