<?php

/**
 * Pino Reservations – Public-facing functionality.
 *
 * Registers AJAX endpoints and the [pino_booking] shortcode (used in page-roomavalability.php).
 */
if (! defined('ABSPATH')) exit;

class Pino_Public
{

    public function __construct()
    {
        /* AJAX endpoints (logged-in & guest) */
        add_action('wp_ajax_pino_check_availability',        [$this, 'ajax_check_availability']);
        add_action('wp_ajax_nopriv_pino_check_availability', [$this, 'ajax_check_availability']);

        add_action('wp_ajax_pino_submit_booking',            [$this, 'ajax_submit_booking']);
        add_action('wp_ajax_nopriv_pino_submit_booking',     [$this, 'ajax_submit_booking']);

        /* Shortcode */
        add_shortcode('pino_booking', [$this, 'render_shortcode']);

        /* Enqueue front-end JS only on the availability page */
        add_action('wp_enqueue_scripts', [$this, 'maybe_enqueue']);
    }

    /* ── Enqueue JS/CSS ── */
    public function maybe_enqueue()
    {
        if (! is_page('roomavalability') && ! is_page('RoomAvalability')) return;

        wp_enqueue_style('pino-booking-css', PINO_RES_URL . 'public/css/booking.css', [], PINO_RES_VERSION);

        wp_enqueue_script('pino-booking', PINO_RES_URL . 'public/js/booking.js', ['jquery'], PINO_RES_VERSION, true);
        wp_localize_script('pino-booking', 'PinoRes', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('pino_booking_nonce'),
        ]);
    }

    /* ══════════════════════════════════════════════
       AJAX: Check Availability
       ══════════════════════════════════════════════ */
    public function ajax_check_availability()
    {
        check_ajax_referer('pino_booking_nonce', 'nonce');

        $start  = sanitize_text_field($_POST['start_date'] ?? '');
        $end    = sanitize_text_field($_POST['end_date'] ?? '');
        $guests = absint($_POST['guests'] ?? 1);

        if (! $start || ! $end || $start >= $end) {
            wp_send_json_error(['message' => 'Моля посочете валидни дати.']);
        }
        if ($start < current_time('Y-m-d')) {
            wp_send_json_error(['message' => 'Датата на настаняване не може да бъде в миналото.']);
        }
        if ($guests < 1) $guests = 1;

        $combos = Pino_Availability::get_combinations($start, $end, $guests);

        $nights = max(1, (strtotime($end) - strtotime($start)) / 86400);

        // Enrich combos with type details
        $result = [];
        foreach ($combos as $i => $combo) {
            $types_detail = [];
            $price_per_night = 0;

            foreach ($combo['type_counts'] as $tid => $count) {
                $rt = Pino_DB::get_room_type($tid);
                if (! $rt) continue;
                $types_detail[] = [
                    'id'       => $tid,
                    'name_bg'  => $rt['name_bg'],
                    'name_en'  => $rt['name_en'],
                    'name_ro'  => $rt['name_ro'],
                    'capacity' => (int) $rt['capacity'],
                    'price'    => (float) $rt['price'],
                    'count'    => $count,
                ];
                $price_per_night += (float) $rt['price'] * $count;
            }

            $result[] = [
                'index'          => $i,
                'types'          => $types_detail,
                'total_capacity' => $combo['total_capacity'],
                'price_per_night' => round($price_per_night, 2),
                'total_price'    => round($price_per_night * $nights, 2),
            ];
        }

        // Also return meals list
        $meals = Pino_DB::get_meals(true);

        wp_send_json_success([
            'combos' => $result,
            'nights' => (int) $nights,
            'meals'  => $meals,
        ]);
    }

    /* ══════════════════════════════════════════════
       AJAX: Submit Booking
       ══════════════════════════════════════════════ */
    public function ajax_submit_booking()
    {
        check_ajax_referer('pino_booking_nonce', 'nonce');

        $first_name = sanitize_text_field($_POST['first_name'] ?? '');
        $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
        $email      = sanitize_email($_POST['email'] ?? '');
        $phone      = sanitize_text_field($_POST['phone'] ?? '');
        $start      = sanitize_text_field($_POST['start_date'] ?? '');
        $end        = sanitize_text_field($_POST['end_date'] ?? '');
        $guests     = absint($_POST['guests'] ?? 1);
        $combo_idx  = intval($_POST['combo_index'] ?? -1);
        $meal_ids   = array_map('absint', (array) ($_POST['meal_ids'] ?? []));

        // Validate
        if (! $first_name || ! $last_name || ! $email || ! $phone) {
            wp_send_json_error(['message' => 'Моля попълнете всички полета.']);
        }
        if (! $start || ! $end || $start >= $end) {
            wp_send_json_error(['message' => 'Невалидни дати.']);
        }

        // Re-check availability
        $combos = Pino_Availability::get_combinations($start, $end, $guests);
        if ($combo_idx < 0 || $combo_idx >= count($combos)) {
            wp_send_json_error(['message' => 'Избраната комбинация вече не е налична.']);
        }

        $combo  = $combos[$combo_idx];
        $nights = max(1, (strtotime($end) - strtotime($start)) / 86400);

        // Compute price
        $room_total = Pino_Availability::compute_room_price($combo, $nights);
        $meal_total = 0;
        $all_meals  = Pino_DB::get_meals(true);
        $meal_map   = [];
        foreach ($all_meals as $m) $meal_map[$m['id']] = (float) $m['price'];

        foreach ($meal_ids as $mid) {
            if (isset($meal_map[$mid])) {
                $meal_total += $meal_map[$mid] * $nights * $guests;
            }
        }

        $grand_total = round($room_total + $meal_total, 2);

        // Build reservation
        $res_data = [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'phone'      => $phone,
            'start_date' => $start,
            'end_date'   => $end,
            'guests'     => $guests,
            'price'      => $grand_total,
            'status'     => 0, // pending
        ];

        // Details
        $details = [];
        foreach ($combo['type_counts'] as $tid => $count) {
            $details[] = [
                'room_type_id' => $tid,
                'nights'       => (int) $nights,
                'room_id'      => null, // Assigned manually by admin
            ];
        }

        $res_id = Pino_DB::create_reservation($res_data, $details, $meal_ids);

        if (! $res_id) {
            wp_send_json_error(['message' => 'Грешка при записване на резервацията.']);
        }

        // Send email notification to hotel
        $this->send_notification_email($res_id, $res_data, $combo, $meal_ids);

        wp_send_json_success([
            'message'        => 'Вашата резервация е получена успешно!',
            'reservation_id' => $res_id,
            'total_price'    => $grand_total,
        ]);
    }

    private function send_notification_email($res_id, $data, $combo, $meal_ids)
    {
        $to      = get_option('admin_email');
        $subject = 'Нова резервация #' . $res_id . ' – Pino Villa';

        $types_str = [];
        foreach ($combo['type_counts'] as $tid => $cnt) {
            $rt = Pino_DB::get_room_type($tid);
            $name = $rt ? $rt['name_bg'] : "Тип #$tid";
            $types_str[] = $cnt > 1 ? "$name × $cnt" : $name;
        }

        $body  = "Нова резервация\n\n";
        $body .= "ID: $res_id\n";
        $body .= "Име: {$data['first_name']} {$data['last_name']}\n";
        $body .= "Email: {$data['email']}\n";
        $body .= "Телефон: {$data['phone']}\n";
        $body .= "Период: {$data['start_date']} → {$data['end_date']}\n";
        $body .= "Гости: {$data['guests']}\n";
        $body .= "Стаи: " . implode(', ', $types_str) . "\n";
        $body .= "Обща цена: {$data['price']} лв.\n";

        wp_mail($to, $subject, $body);
    }

    /* ══════════════════════════════════════════════
       SHORTCODE: [pino_booking]
       Used in page-roomavalability.php
       ══════════════════════════════════════════════ */
    public function render_shortcode($atts = [])
    {
        ob_start();
        include PINO_RES_DIR . 'public/templates/booking-form.php';
        return ob_get_clean();
    }
}

new Pino_Public();
