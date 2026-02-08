<?php
/**
 * Database helper – thin wrapper around $wpdb for Pino Reservations.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Pino_DB {

    /* ── Table names ── */
    public static function t( $name ) {
        global $wpdb;
        return $wpdb->prefix . 'pino_' . $name;
    }

    /* ════════════════════════════════════════════
       ROOM TYPES
       ════════════════════════════════════════════ */
    public static function get_room_types( $visible_only = false ) {
        global $wpdb;
        $where = $visible_only ? 'WHERE visible = 1' : '';
        return $wpdb->get_results( "SELECT * FROM " . self::t('room_types') . " $where ORDER BY sort_order ASC", ARRAY_A );
    }

    public static function get_room_type( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::t('room_types') . " WHERE id = %d", $id ), ARRAY_A );
    }

    public static function save_room_type( $data, $id = null ) {
        global $wpdb;
        $table = self::t('room_types');
        if ( $id ) {
            $wpdb->update( $table, $data, [ 'id' => $id ] );
            return $id;
        }
        $wpdb->insert( $table, $data );
        return $wpdb->insert_id;
    }

    public static function delete_room_type( $id ) {
        global $wpdb;
        $wpdb->delete( self::t('room_types'), [ 'id' => $id ] );
    }

    /* ════════════════════════════════════════════
       ROOMS (physical)
       ════════════════════════════════════════════ */
    public static function get_rooms( $type_id = null ) {
        global $wpdb;
        $sql = "SELECT r.*, rt.name_bg AS type_name FROM " . self::t('rooms') . " r LEFT JOIN " . self::t('room_types') . " rt ON r.room_type_id = rt.id";
        if ( $type_id ) {
            $sql .= $wpdb->prepare( " WHERE r.room_type_id = %d", $type_id );
        }
        $sql .= " ORDER BY r.room_type_id, r.name ASC";
        return $wpdb->get_results( $sql, ARRAY_A );
    }

    public static function get_room( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::t('rooms') . " WHERE id = %d", $id ), ARRAY_A );
    }

    public static function save_room( $data, $id = null ) {
        global $wpdb;
        $table = self::t('rooms');
        if ( $id ) {
            $wpdb->update( $table, $data, [ 'id' => $id ] );
            return $id;
        }
        $wpdb->insert( $table, $data );
        return $wpdb->insert_id;
    }

    public static function delete_room( $id ) {
        global $wpdb;
        $wpdb->delete( self::t('rooms'), [ 'id' => $id ] );
    }

    public static function count_rooms_by_type( $type_id ) {
        global $wpdb;
        return (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM " . self::t('rooms') . " WHERE room_type_id = %d AND visible = 1",
            $type_id
        ) );
    }

    /* ════════════════════════════════════════════
       MEALS
       ════════════════════════════════════════════ */
    public static function get_meals( $visible_only = false ) {
        global $wpdb;
        $where = $visible_only ? 'WHERE visible = 1' : '';
        return $wpdb->get_results( "SELECT * FROM " . self::t('meals') . " $where ORDER BY id ASC", ARRAY_A );
    }

    public static function get_meal( $id ) {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::t('meals') . " WHERE id = %d", $id ), ARRAY_A );
    }

    public static function save_meal( $data, $id = null ) {
        global $wpdb;
        $table = self::t('meals');
        if ( $id ) {
            $wpdb->update( $table, $data, [ 'id' => $id ] );
            return $id;
        }
        $wpdb->insert( $table, $data );
        return $wpdb->insert_id;
    }

    public static function delete_meal( $id ) {
        global $wpdb;
        $wpdb->delete( self::t('meals'), [ 'id' => $id ] );
    }

    /* ════════════════════════════════════════════
       RESERVATIONS
       ════════════════════════════════════════════ */
    public static function get_reservations( $args = [] ) {
        global $wpdb;

        $defaults = [
            'status'   => null,   // null = all, 0=pending, 1=confirmed, 2=cancelled
            'upcoming' => false,
            'orderby'  => 'start_date',
            'order'    => 'DESC',
            'limit'    => 100,
        ];
        $a = wp_parse_args( $args, $defaults );

        $sql   = "SELECT * FROM " . self::t('reservations') . " WHERE 1=1";
        $binds = [];

        if ( $a['status'] !== null && $a['status'] !== '' ) {
            $sql .= " AND status = %d";
            $binds[] = (int) $a['status'];
        }
        if ( $a['upcoming'] ) {
            $sql .= " AND start_date >= %s";
            $binds[] = current_time( 'Y-m-d' );
        }

        $allowed_order = [ 'ASC', 'DESC' ];
        $order = in_array( strtoupper( $a['order'] ), $allowed_order ) ? strtoupper( $a['order'] ) : 'DESC';
        $sql .= " ORDER BY {$a['orderby']} $order LIMIT %d";
        $binds[] = (int) $a['limit'];

        if ( ! empty( $binds ) ) {
            $sql = $wpdb->prepare( $sql, ...$binds );
        }

        return $wpdb->get_results( $sql, ARRAY_A );
    }

    public static function get_reservation( $id ) {
        global $wpdb;
        $res = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . self::t('reservations') . " WHERE id = %d", $id ), ARRAY_A );
        if ( ! $res ) return null;

        $res['details'] = $wpdb->get_results( $wpdb->prepare(
            "SELECT rd.*, rt.name_bg AS type_name FROM " . self::t('reservation_details') . " rd LEFT JOIN " . self::t('room_types') . " rt ON rd.room_type_id = rt.id WHERE rd.reservation_id = %d",
            $id
        ), ARRAY_A );

        $res['meals'] = $wpdb->get_results( $wpdb->prepare(
            "SELECT rm.*, m.name_bg AS meal_name FROM " . self::t('reservation_meals') . " rm LEFT JOIN " . self::t('meals') . " m ON rm.meal_id = m.id WHERE rm.reservation_id = %d",
            $id
        ), ARRAY_A );

        return $res;
    }

    public static function create_reservation( $data, $details = [], $meal_ids = [] ) {
        global $wpdb;

        $wpdb->insert( self::t('reservations'), $data );
        $res_id = $wpdb->insert_id;
        if ( ! $res_id ) return false;

        foreach ( $details as $d ) {
            $d['reservation_id'] = $res_id;
            $wpdb->insert( self::t('reservation_details'), $d );
        }

        foreach ( $meal_ids as $mid ) {
            $wpdb->insert( self::t('reservation_meals'), [
                'reservation_id' => $res_id,
                'meal_id'        => (int) $mid,
            ] );
        }

        return $res_id;
    }

    public static function update_reservation_status( $id, $status ) {
        global $wpdb;
        $wpdb->update( self::t('reservations'), [ 'status' => (int) $status ], [ 'id' => $id ] );
    }

    public static function delete_reservation( $id ) {
        global $wpdb;
        $wpdb->delete( self::t('reservation_details'), [ 'reservation_id' => $id ] );
        $wpdb->delete( self::t('reservation_meals'),   [ 'reservation_id' => $id ] );
        $wpdb->delete( self::t('reservations'),         [ 'id' => $id ] );
    }

    /* ════════════════════════════════════════════
       AVAILABLE ROOMS (IDs not booked in date range)
       ════════════════════════════════════════════ */
    public static function get_available_room_ids( $start, $end ) {
        global $wpdb;

        return $wpdb->get_col( $wpdb->prepare(
            "SELECT r.id
             FROM " . self::t('rooms') . " r
             WHERE r.visible = 1
               AND NOT EXISTS (
                   SELECT 1
                   FROM " . self::t('reservation_details') . " rd
                   JOIN " . self::t('reservations') . " res ON rd.reservation_id = res.id
                   WHERE rd.room_id IS NOT NULL
                     AND rd.room_id = r.id
                     AND res.start_date < %s
                     AND res.end_date   > %s
                     AND res.status = 1
               )",
            $end, $start
        ) );
    }

    /* ── Stats for admin dashboard ── */
    public static function count_reservations( $status = null ) {
        global $wpdb;
        if ( $status !== null ) {
            return (int) $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM " . self::t('reservations') . " WHERE status = %d", $status
            ) );
        }
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM " . self::t('reservations') );
    }
}
