<?php
/**
 * WP Customizer integration for Pino Villa.
 *
 * Exposes the most-changed site settings (contact, social, hours, booking)
 * in the familiar Customizer UI. All values share the same pino_settings
 * option that Pino_Settings (the full admin page) also reads/writes, so the
 * two UIs stay in sync automatically.
 */
if (! defined('ABSPATH')) exit;

class Pino_Customizer
{
    public function __construct()
    {
        add_action('customize_register', [$this, 'register']);
    }

    public function register(\WP_Customize_Manager $wp)
    {
        /* ── Panel ── */
        $wp->add_panel('pino_villa', [
            'title'           => 'Pino Villa',
            'priority'        => 30,
            'active_callback' => '__return_true',
        ]);

        $this->section_contact($wp);
        $this->section_social($wp);
        $this->section_hours($wp);
        $this->section_booking($wp);
    }

    /* ════════════════════════════════════════════
       CONTACT
       ════════════════════════════════════════════ */
    private function section_contact(\WP_Customize_Manager $wp)
    {
        $wp->add_section('pino_contact', [
            'panel'           => 'pino_villa',
            'title'           => 'Контакт',
            'priority'        => 10,
            'active_callback' => '__return_true',
        ]);

        $fields = [
            'phone'         => ['label' => 'Телефон (за tel:)',          'type' => 'text'],
            'phone_display' => ['label' => 'Телефон (за показване)',     'type' => 'text'],
            'email'         => ['label' => 'Имейл',                      'type' => 'text', 'sanitize' => 'sanitize_email'],
            'address_bg'    => ['label' => 'Адрес (BG)',                 'type' => 'text'],
            'address_en'    => ['label' => 'Address (EN)',               'type' => 'text'],
            'address_ro'    => ['label' => 'Adresă (RO)',                'type' => 'text'],
            'map_url'       => ['label' => 'Google Maps URL',            'type' => 'url'],
        ];

        $priority = 10;
        foreach ($fields as $key => $cfg) {
            $id = "pino_settings[contact][$key]";
            $sanitize = $cfg['sanitize'] ?? ($cfg['type'] === 'url' ? 'esc_url_raw' : 'sanitize_text_field');

            $wp->add_setting($id, [
                'type'              => 'option',
                'capability'        => 'manage_options',
                'default'           => Pino_Content::setting("contact.$key"),
                'sanitize_callback' => $sanitize,
                'transport'         => 'refresh',
            ]);

            $wp->add_control($id, [
                'section'  => 'pino_contact',
                'label'    => $cfg['label'],
                'type'     => $cfg['type'] === 'url' ? 'url' : 'text',
                'priority' => $priority,
            ]);

            $priority += 10;
        }
    }

    /* ════════════════════════════════════════════
       SOCIAL MEDIA
       ════════════════════════════════════════════ */
    private function section_social(\WP_Customize_Manager $wp)
    {
        $wp->add_section('pino_social', [
            'panel'           => 'pino_villa',
            'title'           => 'Социални мрежи',
            'priority'        => 20,
            'active_callback' => '__return_true',
        ]);

        $fields = [
            'facebook'  => 'Facebook',
            'instagram' => 'Instagram',
            'youtube'   => 'YouTube',
            'tiktok'    => 'TikTok',
        ];

        $priority = 10;
        foreach ($fields as $key => $label) {
            $id = "pino_settings[social][$key]";
            $wp->add_setting($id, [
                'type'              => 'option',
                'capability'        => 'manage_options',
                'default'           => Pino_Content::setting("social.$key"),
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            ]);
            $wp->add_control($id, [
                'section'  => 'pino_social',
                'label'    => $label . ' URL',
                'type'     => 'url',
                'priority' => $priority,
            ]);
            $priority += 10;
        }
    }

    /* ════════════════════════════════════════════
       WORKING HOURS
       ════════════════════════════════════════════ */
    private function section_hours(\WP_Customize_Manager $wp)
    {
        $wp->add_section('pino_hours', [
            'panel'           => 'pino_villa',
            'title'           => 'Работно време',
            'priority'        => 30,
            'active_callback' => '__return_true',
        ]);

        $fields = [
            'general_bg'    => 'Общо (BG)',
            'general_en'    => 'General (EN)',
            'general_ro'    => 'General (RO)',
            'restaurant_bg' => 'Ресторант (BG)',
            'restaurant_en' => 'Restaurant (EN)',
            'restaurant_ro' => 'Restaurant (RO)',
        ];

        $priority = 10;
        foreach ($fields as $key => $label) {
            $id = "pino_settings[hours][$key]";
            $wp->add_setting($id, [
                'type'              => 'option',
                'capability'        => 'manage_options',
                'default'           => Pino_Content::setting("hours.$key"),
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
            ]);
            $wp->add_control($id, [
                'section'  => 'pino_hours',
                'label'    => $label,
                'type'     => 'text',
                'priority' => $priority,
            ]);
            $priority += 10;
        }
    }

    /* ════════════════════════════════════════════
       BOOKING
       ════════════════════════════════════════════ */
    private function section_booking(\WP_Customize_Manager $wp)
    {
        $wp->add_section('pino_booking', [
            'panel'           => 'pino_villa',
            'title'           => 'Резервации',
            'priority'        => 40,
            'active_callback' => '__return_true',
        ]);

        /* Notification e-mail */
        $wp->add_setting('pino_settings[booking][notify_email]', [
            'type'              => 'option',
            'capability'        => 'manage_options',
            'default'           => Pino_Content::setting('booking.notify_email'),
            'sanitize_callback' => 'sanitize_email',
            'transport'         => 'refresh',
        ]);
        $wp->add_control('pino_settings[booking][notify_email]', [
            'section'  => 'pino_booking',
            'label'    => 'Email за известия',
            'type'     => 'email',
            'priority' => 10,
        ]);

        /* Min nights */
        $wp->add_setting('pino_settings[booking][min_nights]', [
            'type'              => 'option',
            'capability'        => 'manage_options',
            'default'           => Pino_Content::setting('booking.min_nights', 1),
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]);
        $wp->add_control('pino_settings[booking][min_nights]', [
            'section'     => 'pino_booking',
            'label'       => 'Минимум нощи',
            'type'        => 'number',
            'input_attrs' => ['min' => 1, 'max' => 30],
            'priority'    => 20,
        ]);

        /* Auto-confirm */
        $wp->add_setting('pino_settings[booking][auto_confirm]', [
            'type'              => 'option',
            'capability'        => 'manage_options',
            'default'           => Pino_Content::setting('booking.auto_confirm', 0),
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]);
        $wp->add_control('pino_settings[booking][auto_confirm]', [
            'section'  => 'pino_booking',
            'label'    => 'Автоматично потвърждение',
            'type'     => 'checkbox',
            'priority' => 30,
        ]);
    }
}

new Pino_Customizer();
