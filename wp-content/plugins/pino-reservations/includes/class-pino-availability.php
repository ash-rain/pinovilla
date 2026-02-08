<?php
/**
 * Availability engine – port of ReservationManager.GetPossibleTypeCombinations()
 * from the original .NET codebase.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Pino_Availability {

    /**
     * Get possible room-type combinations that satisfy `$guests` for a date range.
     *
     * Logic (matching original .NET):
     *   A) Single-type combos: 1 or 2 rooms of the same type
     *   B) Two-type combos:   1 room each of two different types
     *   Capacity must be in [ guests, guests + 2 ]
     *
     * @param  string $start  Y-m-d
     * @param  string $end    Y-m-d
     * @param  int    $guests
     * @return array          List of combinations, each = [ 'type_counts' => [ typeId => count ], 'total_capacity' => int ]
     */
    public static function get_combinations( $start, $end, $guests ) {

        $available_ids = Pino_DB::get_available_room_ids( $start, $end );
        if ( empty( $available_ids ) ) return [];

        // Get all visible rooms and filter to available
        $all_rooms = Pino_DB::get_rooms();
        $available_rooms = array_filter( $all_rooms, function( $r ) use ( $available_ids ) {
            return in_array( $r['id'], $available_ids );
        } );

        // Group by type: count available rooms per type + capacity per type
        $type_count    = []; // typeId => number of available rooms
        $type_capacity = []; // typeId => capacity per room

        foreach ( $available_rooms as $room ) {
            $tid = (int) $room['room_type_id'];
            if ( ! isset( $type_count[ $tid ] ) ) {
                $type_count[ $tid ]    = 0;
                $rt = Pino_DB::get_room_type( $tid );
                $type_capacity[ $tid ] = $rt ? (int) $rt['capacity'] : 2;
            }
            $type_count[ $tid ]++;
        }

        $sorted_types = array_keys( $type_count );
        sort( $sorted_types );

        $results = [];

        // STEP A — single-type combos (1 or 2 rooms)
        foreach ( $sorted_types as $tid ) {
            $cap_each  = $type_capacity[ $tid ];
            $available = $type_count[ $tid ];

            // 1-room combo
            if ( $available >= 1 ) {
                $cap = $cap_each;
                if ( $cap >= $guests && $cap <= $guests + 2 ) {
                    $results[] = [
                        'type_counts'    => [ $tid => 1 ],
                        'total_capacity' => $cap,
                    ];
                }
            }

            // 2-room combo
            if ( $available >= 2 ) {
                $cap = 2 * $cap_each;
                if ( $cap >= $guests && $cap <= $guests + 2 ) {
                    $results[] = [
                        'type_counts'    => [ $tid => 2 ],
                        'total_capacity' => $cap,
                    ];
                }
            }
        }

        // STEP B — two-type combos (1 room each)
        $n = count( $sorted_types );
        for ( $i = 0; $i < $n; $i++ ) {
            $t1 = $sorted_types[ $i ];
            if ( $type_count[ $t1 ] < 1 ) continue;

            for ( $j = $i + 1; $j < $n; $j++ ) {
                $t2 = $sorted_types[ $j ];
                if ( $type_count[ $t2 ] < 1 ) continue;

                $cap = $type_capacity[ $t1 ] + $type_capacity[ $t2 ];
                if ( $cap >= $guests && $cap <= $guests + 2 ) {
                    $results[] = [
                        'type_counts'    => [ $t1 => 1, $t2 => 1 ],
                        'total_capacity' => $cap,
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Compute base room price for a combination × number of nights.
     */
    public static function compute_room_price( $combo, $nights ) {
        $total = 0;
        foreach ( $combo['type_counts'] as $tid => $count ) {
            $rt = Pino_DB::get_room_type( $tid );
            if ( $rt ) {
                $total += (float) $rt['price'] * $nights * $count;
            }
        }
        return round( $total, 2 );
    }
}
