<?php

/**
 * Pino Reservations – Admin Panel
 *
 * Reservations · Room Types · Rooms · Meals
 *
 * All settings / content / i18n editors live in class-pino-settings.php,
 * which registers its submenu under the same top-level Pino slug.
 */
if (! defined('ABSPATH')) exit;

class Pino_Admin
{
    const MENU_SLUG = 'pino-reservations';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menus'], 10);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /* ------------------------------------------------------------------
       ENQUEUE — styles + scripts for every Pino admin screen
       ------------------------------------------------------------------ */
    public function enqueue_assets($hook)
    {
        if (strpos($hook, 'pino-') === false) return;

        wp_enqueue_style(
            'pino-admin',
            PINO_RES_URL . 'admin/css/admin.css',
            [],
            PINO_RES_VERSION
        );

        wp_enqueue_media();

        wp_enqueue_script(
            'pino-admin',
            PINO_RES_URL . 'admin/js/admin.js',
            ['jquery'],
            PINO_RES_VERSION,
            true
        );

        wp_localize_script('pino-admin', 'pinoAdmin', [
            'mediaTitle'  => __('Select image', 'pino-reservations'),
            'mediaButton' => __('Use this image', 'pino-reservations'),
            'removeText'  => __('Remove', 'pino-reservations'),
            'confirmDel'  => __('Delete this item? This cannot be undone.', 'pino-reservations'),
        ]);
    }

    /* ------------------------------------------------------------------
       MENU — top-level + four submenus, badge on the reservations label
       ------------------------------------------------------------------ */
    public function register_menus()
    {
        $pending = Pino_DB::count_reservations(0);
        $badge   = $pending > 0
            ? ' <span class="awaiting-mod update-plugins count-' . esc_attr($pending) . '"><span class="pending-count">' . number_format_i18n($pending) . '</span></span>'
            : '';

        add_menu_page(
            __('Pino Reservations', 'pino-reservations'),
            __('Резервации', 'pino-reservations') . $badge,
            'manage_options',
            self::MENU_SLUG,
            [$this, 'page_reservations'],
            'dashicons-calendar-alt',
            26
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Всички резервации', 'pino-reservations'),
            __('Всички резервации', 'pino-reservations') . $badge,
            'manage_options',
            self::MENU_SLUG,
            [$this, 'page_reservations']
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Типове стаи', 'pino-reservations'),
            __('Типове стаи', 'pino-reservations'),
            'manage_options',
            'pino-room-types',
            [$this, 'page_room_types']
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Стаи', 'pino-reservations'),
            __('Стаи', 'pino-reservations'),
            'manage_options',
            'pino-rooms',
            [$this, 'page_rooms']
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Хранене', 'pino-reservations'),
            __('Хранене', 'pino-reservations'),
            'manage_options',
            'pino-meals',
            [$this, 'page_meals']
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Меню ресторант', 'pino-reservations'),
            __('Меню', 'pino-reservations'),
            'manage_options',
            'pino-menu-items',
            [$this, 'page_menu_items']
        );
    }

    /* ------------------------------------------------------------------
       ACTIONS — POST handler
       ------------------------------------------------------------------ */
    public function handle_actions()
    {
        if (! current_user_can('manage_options')) return;
        if (empty($_POST['pino_action'])) return;
        check_admin_referer('pino_admin_action');

        $action = sanitize_text_field(wp_unslash($_POST['pino_action']));

        switch ($action) {
            case 'save_room_type':
                $data = [
                    'name_bg'    => sanitize_text_field(wp_unslash($_POST['name_bg'] ?? '')),
                    'name_en'    => sanitize_text_field(wp_unslash($_POST['name_en'] ?? '')),
                    'name_ro'    => sanitize_text_field(wp_unslash($_POST['name_ro'] ?? '')),
                    'desc_bg'    => wp_kses_post(wp_unslash($_POST['desc_bg'] ?? '')),
                    'desc_en'    => wp_kses_post(wp_unslash($_POST['desc_en'] ?? '')),
                    'desc_ro'    => wp_kses_post(wp_unslash($_POST['desc_ro'] ?? '')),
                    'capacity'   => absint($_POST['capacity'] ?? 2),
                    'price'      => floatval($_POST['price'] ?? 0),
                    'photo'      => esc_url_raw(wp_unslash($_POST['photo'] ?? '')),
                    'gallery'    => $this->sanitize_gallery($_POST['gallery'] ?? ''),
                    'amenities'  => $this->sanitize_amenities($_POST['amenities'] ?? ''),
                    'sort_order' => absint($_POST['sort_order'] ?? 0),
                    'visible'    => isset($_POST['visible']) ? 1 : 0,
                ];
                $id = absint($_POST['item_id'] ?? 0);
                Pino_DB::save_room_type($data, $id ?: null);
                wp_safe_redirect(admin_url('admin.php?page=pino-room-types&msg=saved'));
                exit;

            case 'delete_room_type':
                Pino_DB::delete_room_type(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-room-types&msg=deleted'));
                exit;

            case 'save_room':
                $data = [
                    'name'         => sanitize_text_field(wp_unslash($_POST['name'] ?? '')),
                    'room_type_id' => absint($_POST['room_type_id'] ?? 0),
                    'floor'        => intval($_POST['floor'] ?? 1),
                    'notes'        => sanitize_textarea_field(wp_unslash($_POST['notes'] ?? '')),
                    'visible'      => isset($_POST['visible']) ? 1 : 0,
                ];
                $id = absint($_POST['item_id'] ?? 0);
                Pino_DB::save_room($data, $id ?: null);
                wp_safe_redirect(admin_url('admin.php?page=pino-rooms&msg=saved'));
                exit;

            case 'delete_room':
                Pino_DB::delete_room(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-rooms&msg=deleted'));
                exit;

            case 'save_meal':
                $data = [
                    'name_bg'    => sanitize_text_field(wp_unslash($_POST['name_bg'] ?? '')),
                    'name_en'    => sanitize_text_field(wp_unslash($_POST['name_en'] ?? '')),
                    'name_ro'    => sanitize_text_field(wp_unslash($_POST['name_ro'] ?? '')),
                    'desc_bg'    => sanitize_textarea_field(wp_unslash($_POST['desc_bg'] ?? '')),
                    'desc_en'    => sanitize_textarea_field(wp_unslash($_POST['desc_en'] ?? '')),
                    'desc_ro'    => sanitize_textarea_field(wp_unslash($_POST['desc_ro'] ?? '')),
                    'price'      => floatval($_POST['price'] ?? 0),
                    'sort_order' => absint($_POST['sort_order'] ?? 0),
                    'visible'    => isset($_POST['visible']) ? 1 : 0,
                ];
                $id = absint($_POST['item_id'] ?? 0);
                Pino_DB::save_meal($data, $id ?: null);
                wp_safe_redirect(admin_url('admin.php?page=pino-meals&msg=saved'));
                exit;

            case 'delete_meal':
                Pino_DB::delete_meal(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-meals&msg=deleted'));
                exit;

            case 'save_menu_item':
                $data = [
                    'category_bg' => sanitize_text_field(wp_unslash($_POST['category_bg'] ?? '')),
                    'category_en' => sanitize_text_field(wp_unslash($_POST['category_en'] ?? '')),
                    'category_ro' => sanitize_text_field(wp_unslash($_POST['category_ro'] ?? '')),
                    'name_bg'     => sanitize_text_field(wp_unslash($_POST['name_bg'] ?? '')),
                    'name_en'     => sanitize_text_field(wp_unslash($_POST['name_en'] ?? '')),
                    'name_ro'     => sanitize_text_field(wp_unslash($_POST['name_ro'] ?? '')),
                    'desc_bg'     => sanitize_textarea_field(wp_unslash($_POST['desc_bg'] ?? '')),
                    'desc_en'     => sanitize_textarea_field(wp_unslash($_POST['desc_en'] ?? '')),
                    'desc_ro'     => sanitize_textarea_field(wp_unslash($_POST['desc_ro'] ?? '')),
                    'price'       => floatval($_POST['price'] ?? 0),
                    'sort_order'  => absint($_POST['sort_order'] ?? 0),
                    'visible'     => isset($_POST['visible']) ? 1 : 0,
                ];
                $id = absint($_POST['item_id'] ?? 0);
                Pino_DB::save_menu_item($data, $id ?: null);
                wp_safe_redirect(admin_url('admin.php?page=pino-menu-items&msg=saved'));
                exit;

            case 'delete_menu_item':
                Pino_DB::delete_menu_item(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-menu-items&msg=deleted'));
                exit;

            case 'update_reservation_status':
                $id     = absint($_POST['item_id'] ?? 0);
                $status = intval($_POST['new_status'] ?? 0);
                Pino_DB::update_reservation_status($id, $status);
                wp_safe_redirect(admin_url('admin.php?page=pino-reservations&view=' . $id . '&msg=updated'));
                exit;

            case 'delete_reservation':
                Pino_DB::delete_reservation(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-reservations&msg=deleted'));
                exit;
        }
    }

    private function sanitize_gallery($raw)
    {
        $urls = array_filter(array_map('trim', explode("\n", wp_unslash($raw))));
        $urls = array_map('esc_url_raw', $urls);
        return implode("\n", array_filter($urls));
    }

    private function sanitize_amenities($raw)
    {
        $lines = array_filter(array_map('trim', explode("\n", wp_unslash($raw))));
        $lines = array_map('sanitize_text_field', $lines);
        return implode("\n", array_filter($lines));
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: RESERVATIONS (list + detail)
       ══════════════════════════════════════════════════════════════════ */
    public function page_reservations()
    {
        $status_filter = isset($_GET['status']) && $_GET['status'] !== '' ? intval($_GET['status']) : null;

        if (isset($_GET['view'])) {
            $res = Pino_DB::get_reservation(absint($_GET['view']));
            if ($res) {
                $this->render_reservation_detail($res);
                return;
            }
        }

        $args = ['limit' => 200];
        if ($status_filter !== null) $args['status'] = $status_filter;
        $reservations = Pino_DB::get_reservations($args);

        $count_all     = Pino_DB::count_reservations();
        $count_pending = Pino_DB::count_reservations(0);
        $count_conf    = Pino_DB::count_reservations(1);
        $count_canc    = Pino_DB::count_reservations(2);

        $status_labels = [0 => 'Нова', 1 => 'Потвърдена', 2 => 'Отказана'];
        $status_class  = [0 => 'pino-status-pending', 1 => 'pino-status-confirmed', 2 => 'pino-status-cancelled'];
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Резервации</h1>
    <?php $this->render_notices(); ?>

    <div class="pino-stats">
        <a href="?page=pino-reservations" class="pino-stat<?php echo $status_filter === null ? ' is-active' : ''; ?>">
            <span class="pino-stat-num"><?php echo (int) $count_all; ?></span>
            <span class="pino-stat-label">Общо</span>
        </a>
        <a href="?page=pino-reservations&status=0" class="pino-stat pino-stat-pending<?php echo $status_filter === 0 ? ' is-active' : ''; ?>">
            <span class="pino-stat-num"><?php echo (int) $count_pending; ?></span>
            <span class="pino-stat-label">Нови</span>
        </a>
        <a href="?page=pino-reservations&status=1" class="pino-stat pino-stat-confirmed<?php echo $status_filter === 1 ? ' is-active' : ''; ?>">
            <span class="pino-stat-num"><?php echo (int) $count_conf; ?></span>
            <span class="pino-stat-label">Потвърдени</span>
        </a>
        <a href="?page=pino-reservations&status=2" class="pino-stat pino-stat-cancelled<?php echo $status_filter === 2 ? ' is-active' : ''; ?>">
            <span class="pino-stat-num"><?php echo (int) $count_canc; ?></span>
            <span class="pino-stat-label">Отказани</span>
        </a>
    </div>

    <table class="wp-list-table widefat striped pino-table">
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th>Гост</th>
                <th>Контакт</th>
                <th>Период</th>
                <th class="col-num">Нощи</th>
                <th class="col-num">Гости</th>
                <th class="col-num">Цена</th>
                <th>Статус</th>
                <th class="col-actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)) : ?>
                <tr><td colspan="9" class="pino-empty">Няма резервации в избрания филтър.</td></tr>
            <?php else : foreach ($reservations as $r) :
                $nights = max(1, (strtotime($r['end_date']) - strtotime($r['start_date'])) / 86400);
            ?>
                <tr>
                    <td class="col-id">#<?php echo (int) $r['id']; ?></td>
                    <td><strong><?php echo esc_html($r['first_name'] . ' ' . $r['last_name']); ?></strong></td>
                    <td>
                        <a href="mailto:<?php echo esc_attr($r['email']); ?>"><?php echo esc_html($r['email']); ?></a><br>
                        <span class="pino-muted"><?php echo esc_html($r['phone']); ?></span>
                    </td>
                    <td>
                        <?php echo esc_html(date_i18n('d M Y', strtotime($r['start_date']))); ?>
                        <span class="pino-muted">→</span>
                        <?php echo esc_html(date_i18n('d M Y', strtotime($r['end_date']))); ?>
                    </td>
                    <td class="col-num"><?php echo (int) $nights; ?></td>
                    <td class="col-num"><?php echo (int) $r['guests']; ?></td>
                    <td class="col-num"><strong><?php echo number_format($r['price'], 2); ?> €</strong></td>
                    <td>
                        <span class="pino-pill <?php echo esc_attr($status_class[$r['status']] ?? ''); ?>">
                            <?php echo esc_html($status_labels[$r['status']] ?? $r['status']); ?>
                        </span>
                    </td>
                    <td class="col-actions">
                        <a href="?page=pino-reservations&view=<?php echo (int) $r['id']; ?>" class="button button-small button-primary">Преглед</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php
    }

    private function render_reservation_detail($res)
    {
        $status_labels = [0 => 'Нова', 1 => 'Потвърдена', 2 => 'Отказана'];
        $status_class  = [0 => 'pino-status-pending', 1 => 'pino-status-confirmed', 2 => 'pino-status-cancelled'];
        $nights = max(1, (strtotime($res['end_date']) - strtotime($res['start_date'])) / 86400);
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">
        Резервация #<?php echo (int) $res['id']; ?>
        <span class="pino-pill <?php echo esc_attr($status_class[$res['status']] ?? ''); ?>">
            <?php echo esc_html($status_labels[$res['status']] ?? $res['status']); ?>
        </span>
    </h1>
    <a href="?page=pino-reservations" class="page-title-action">&larr; Назад към списъка</a>
    <?php $this->render_notices(); ?>

    <div class="pino-grid-2">
        <div class="pino-card">
            <h2>Клиент</h2>
            <dl class="pino-dl">
                <dt>Име</dt>       <dd><?php echo esc_html($res['first_name'] . ' ' . $res['last_name']); ?></dd>
                <dt>Email</dt>     <dd><a href="mailto:<?php echo esc_attr($res['email']); ?>"><?php echo esc_html($res['email']); ?></a></dd>
                <dt>Телефон</dt>   <dd><a href="tel:<?php echo esc_attr($res['phone']); ?>"><?php echo esc_html($res['phone']); ?></a></dd>
                <dt>Получена</dt>  <dd><?php echo esc_html($res['created_at']); ?></dd>
            </dl>
        </div>

        <div class="pino-card">
            <h2>Престой</h2>
            <dl class="pino-dl">
                <dt>Настаняване</dt> <dd><?php echo esc_html(date_i18n('d M Y', strtotime($res['start_date']))); ?></dd>
                <dt>Освобождаване</dt> <dd><?php echo esc_html(date_i18n('d M Y', strtotime($res['end_date']))); ?></dd>
                <dt>Нощи</dt>         <dd><?php echo (int) $nights; ?></dd>
                <dt>Гости</dt>        <dd><?php echo (int) $res['guests']; ?></dd>
                <dt>Обща цена</dt>    <dd><strong><?php echo number_format($res['price'], 2); ?> €</strong></dd>
            </dl>
        </div>
    </div>

    <?php if (! empty($res['details'])) : ?>
    <div class="pino-card">
        <h2>Стаи</h2>
        <table class="wp-list-table widefat striped">
            <thead><tr><th>Тип стая</th><th>Нощувки</th><th>Стая №</th></tr></thead>
            <tbody>
                <?php foreach ($res['details'] as $d) : ?>
                    <tr>
                        <td><?php echo esc_html($d['type_name'] ?? 'Тип #' . $d['room_type_id']); ?></td>
                        <td><?php echo (int) $d['nights']; ?></td>
                        <td><?php echo $d['room_id'] ? (int) $d['room_id'] : '—'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (! empty($res['meals'])) : ?>
    <div class="pino-card">
        <h2>Хранене</h2>
        <ul class="pino-meal-list">
            <?php foreach ($res['meals'] as $m) : ?>
                <li><?php echo esc_html($m['meal_name'] ?? 'Meal #' . $m['meal_id']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="pino-card">
        <h2>Промяна на статус</h2>
        <div class="pino-actions-row">
            <?php foreach ([0 => ['Нова', 'button'], 1 => ['Потвърди', 'button-primary'], 2 => ['Откажи', 'button']] as $s => $btn) : ?>
                <?php if ((int) $res['status'] === $s) continue; ?>
                <form method="post" class="pino-inline-form">
                    <?php wp_nonce_field('pino_admin_action'); ?>
                    <input type="hidden" name="pino_action" value="update_reservation_status">
                    <input type="hidden" name="item_id" value="<?php echo (int) $res['id']; ?>">
                    <input type="hidden" name="new_status" value="<?php echo $s; ?>">
                    <button type="submit" class="button <?php echo esc_attr($btn[1]); ?>"><?php echo esc_html($btn[0]); ?></button>
                </form>
            <?php endforeach; ?>

            <form method="post" class="pino-inline-form pino-confirm-delete">
                <?php wp_nonce_field('pino_admin_action'); ?>
                <input type="hidden" name="pino_action" value="delete_reservation">
                <input type="hidden" name="item_id" value="<?php echo (int) $res['id']; ?>">
                <button type="submit" class="button pino-button-danger">Изтрий</button>
            </form>
        </div>
    </div>

    <?php if (! empty($res['notes'])) : ?>
    <div class="pino-card">
        <h2>Бележки</h2>
        <p><?php echo nl2br(esc_html($res['notes'])); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: ROOM TYPES
       ══════════════════════════════════════════════════════════════════ */
    public function page_room_types()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_room_type(absint($_GET['edit']));
        }
        $adding = isset($_GET['new']);
        $types = Pino_DB::get_room_types();
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Типове стаи</h1>
    <?php if (! $editing && ! $adding) : ?>
        <a href="?page=pino-room-types&new=1" class="page-title-action">Добави нов</a>
    <?php endif; ?>
    <?php $this->render_notices(); ?>

    <?php if ($editing || $adding) : ?>
        <div class="pino-card pino-form-card">
            <h2><?php echo $editing ? 'Редакция на „' . esc_html($editing['name_bg']) . '“' : 'Нов тип стая'; ?></h2>
            <form method="post" class="pino-form">
                <?php wp_nonce_field('pino_admin_action'); ?>
                <input type="hidden" name="pino_action" value="save_room_type">
                <input type="hidden" name="item_id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

                <?php $this->render_lang_tabs('room-type', [
                    'bg' => [
                        ['label' => 'Име (BG)',       'name' => 'name_bg', 'type' => 'text',     'value' => $editing['name_bg'] ?? '', 'required' => true],
                        ['label' => 'Описание (BG)',  'name' => 'desc_bg', 'type' => 'textarea', 'value' => $editing['desc_bg'] ?? ''],
                    ],
                    'en' => [
                        ['label' => 'Name (EN)',        'name' => 'name_en', 'type' => 'text',     'value' => $editing['name_en'] ?? ''],
                        ['label' => 'Description (EN)', 'name' => 'desc_en', 'type' => 'textarea', 'value' => $editing['desc_en'] ?? ''],
                    ],
                    'ro' => [
                        ['label' => 'Nume (RO)',        'name' => 'name_ro', 'type' => 'text',     'value' => $editing['name_ro'] ?? ''],
                        ['label' => 'Descriere (RO)',   'name' => 'desc_ro', 'type' => 'textarea', 'value' => $editing['desc_ro'] ?? ''],
                    ],
                ]); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>Главна снимка</label></th>
                        <td>
                            <?php $this->render_image_picker('photo', $editing['photo'] ?? ''); ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-gallery">Галерия (по един URL на ред)</label></th>
                        <td><textarea id="fld-gallery" name="gallery" rows="3" class="large-text code" placeholder="https://…"><?php echo esc_textarea($editing['gallery'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-amenities">Удобства (по едно на ред)</label></th>
                        <td><textarea id="fld-amenities" name="amenities" rows="4" class="large-text" placeholder="Wi-Fi&#10;Климатик&#10;Сейф"><?php echo esc_textarea($editing['amenities'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-capacity">Капацитет</label></th>
                        <td><input id="fld-capacity" type="number" name="capacity" min="1" value="<?php echo (int) ($editing['capacity'] ?? 2); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-price">Цена / нощ (€)</label></th>
                        <td><input id="fld-price" type="number" name="price" step="0.01" min="0" value="<?php echo esc_attr($editing['price'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-sort">Подредба</label></th>
                        <td><input id="fld-sort" type="number" name="sort_order" min="0" value="<?php echo (int) ($editing['sort_order'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Видимост</th>
                        <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Видим на сайта</label></td>
                    </tr>
                </table>

                <p class="submit pino-submit">
                    <button type="submit" class="button button-primary button-large">Запази</button>
                    <a href="?page=pino-room-types" class="button button-large">Отказ</a>
                </p>
            </form>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat striped pino-table">
        <thead>
            <tr>
                <th class="col-thumb"></th>
                <th>Име (BG)</th>
                <th>Капацитет</th>
                <th>Цена</th>
                <th>Ред</th>
                <th>Видим</th>
                <th class="col-actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($types)) : ?>
                <tr><td colspan="7" class="pino-empty">Все още няма типове стаи.</td></tr>
            <?php else : foreach ($types as $t) : ?>
                <tr>
                    <td class="col-thumb">
                        <?php if (! empty($t['photo'])) : ?>
                            <img src="<?php echo esc_url($t['photo']); ?>" alt="" class="pino-thumb">
                        <?php else : ?>
                            <span class="pino-thumb pino-thumb-empty dashicons dashicons-format-image"></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><a href="?page=pino-room-types&edit=<?php echo (int) $t['id']; ?>"><?php echo esc_html($t['name_bg']); ?></a></strong>
                        <?php if (! $t['visible']) echo ' <span class="pino-pill pino-status-cancelled">скрит</span>'; ?>
                    </td>
                    <td><?php echo (int) $t['capacity']; ?></td>
                    <td><?php echo number_format($t['price'], 2); ?> €</td>
                    <td><?php echo (int) $t['sort_order']; ?></td>
                    <td><?php echo $t['visible'] ? '<span class="dashicons dashicons-yes pino-yes"></span>' : '<span class="dashicons dashicons-hidden pino-no"></span>'; ?></td>
                    <td class="col-actions">
                        <a href="?page=pino-room-types&edit=<?php echo (int) $t['id']; ?>" class="button button-small">Редакция</a>
                        <form method="post" class="pino-inline-form pino-confirm-delete">
                            <?php wp_nonce_field('pino_admin_action'); ?>
                            <input type="hidden" name="pino_action" value="delete_room_type">
                            <input type="hidden" name="item_id" value="<?php echo (int) $t['id']; ?>">
                            <button type="submit" class="button button-small pino-button-danger">Изтрий</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: ROOMS
       ══════════════════════════════════════════════════════════════════ */
    public function page_rooms()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_room(absint($_GET['edit']));
        }
        $adding = isset($_GET['new']);
        $rooms = Pino_DB::get_rooms();
        $types = Pino_DB::get_room_types();
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Стаи</h1>
    <?php if (! $editing && ! $adding) : ?>
        <a href="?page=pino-rooms&new=1" class="page-title-action">Добави нова</a>
    <?php endif; ?>
    <?php $this->render_notices(); ?>

    <?php if ($editing || $adding) : ?>
        <div class="pino-card pino-form-card">
            <h2><?php echo $editing ? 'Редакция на „' . esc_html($editing['name']) . '“' : 'Нова стая'; ?></h2>
            <form method="post" class="pino-form">
                <?php wp_nonce_field('pino_admin_action'); ?>
                <input type="hidden" name="pino_action" value="save_room">
                <input type="hidden" name="item_id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fld-rname">Наименование</label></th>
                        <td><input id="fld-rname" type="text" name="name" class="regular-text" value="<?php echo esc_attr($editing['name'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-rtype">Тип стая</label></th>
                        <td>
                            <select id="fld-rtype" name="room_type_id" required>
                                <?php foreach ($types as $t) : ?>
                                    <option value="<?php echo (int) $t['id']; ?>" <?php selected($editing['room_type_id'] ?? 0, $t['id']); ?>><?php echo esc_html($t['name_bg']); ?> (<?php echo number_format($t['price'], 2); ?> €)</option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-floor">Етаж</label></th>
                        <td><input id="fld-floor" type="number" name="floor" value="<?php echo (int) ($editing['floor'] ?? 1); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-notes">Бележки (вътрешни)</label></th>
                        <td><textarea id="fld-notes" name="notes" rows="3" class="large-text"><?php echo esc_textarea($editing['notes'] ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Видимост</th>
                        <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Достъпна за резервации</label></td>
                    </tr>
                </table>

                <p class="submit pino-submit">
                    <button type="submit" class="button button-primary button-large">Запази</button>
                    <a href="?page=pino-rooms" class="button button-large">Отказ</a>
                </p>
            </form>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat striped pino-table">
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Тип</th>
                <th>Етаж</th>
                <th>Бележки</th>
                <th>Видима</th>
                <th class="col-actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rooms)) : ?>
                <tr><td colspan="6" class="pino-empty">Няма добавени стаи.</td></tr>
            <?php else : foreach ($rooms as $r) : ?>
                <tr>
                    <td><strong><a href="?page=pino-rooms&edit=<?php echo (int) $r['id']; ?>"><?php echo esc_html($r['name']); ?></a></strong></td>
                    <td><?php echo esc_html($r['type_name'] ?? '—'); ?></td>
                    <td><?php echo (int) $r['floor']; ?></td>
                    <td class="pino-muted"><?php echo esc_html(wp_trim_words($r['notes'] ?? '', 12)); ?></td>
                    <td><?php echo $r['visible'] ? '<span class="dashicons dashicons-yes pino-yes"></span>' : '<span class="dashicons dashicons-hidden pino-no"></span>'; ?></td>
                    <td class="col-actions">
                        <a href="?page=pino-rooms&edit=<?php echo (int) $r['id']; ?>" class="button button-small">Редакция</a>
                        <form method="post" class="pino-inline-form pino-confirm-delete">
                            <?php wp_nonce_field('pino_admin_action'); ?>
                            <input type="hidden" name="pino_action" value="delete_room">
                            <input type="hidden" name="item_id" value="<?php echo (int) $r['id']; ?>">
                            <button type="submit" class="button button-small pino-button-danger">Изтрий</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: MEALS
       ══════════════════════════════════════════════════════════════════ */
    public function page_meals()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_meal(absint($_GET['edit']));
        }
        $adding = isset($_GET['new']);
        $meals = Pino_DB::get_meals();
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Хранене</h1>
    <?php if (! $editing && ! $adding) : ?>
        <a href="?page=pino-meals&new=1" class="page-title-action">Добави ново</a>
    <?php endif; ?>
    <?php $this->render_notices(); ?>

    <?php if ($editing || $adding) : ?>
        <div class="pino-card pino-form-card">
            <h2><?php echo $editing ? 'Редакция на „' . esc_html($editing['name_bg']) . '“' : 'Ново хранене'; ?></h2>
            <form method="post" class="pino-form">
                <?php wp_nonce_field('pino_admin_action'); ?>
                <input type="hidden" name="pino_action" value="save_meal">
                <input type="hidden" name="item_id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

                <?php $this->render_lang_tabs('meal', [
                    'bg' => [
                        ['label' => 'Име (BG)',       'name' => 'name_bg', 'type' => 'text',     'value' => $editing['name_bg'] ?? '', 'required' => true],
                        ['label' => 'Описание (BG)',  'name' => 'desc_bg', 'type' => 'textarea', 'value' => $editing['desc_bg'] ?? ''],
                    ],
                    'en' => [
                        ['label' => 'Name (EN)',        'name' => 'name_en', 'type' => 'text',     'value' => $editing['name_en'] ?? ''],
                        ['label' => 'Description (EN)', 'name' => 'desc_en', 'type' => 'textarea', 'value' => $editing['desc_en'] ?? ''],
                    ],
                    'ro' => [
                        ['label' => 'Nume (RO)',      'name' => 'name_ro', 'type' => 'text',     'value' => $editing['name_ro'] ?? ''],
                        ['label' => 'Descriere (RO)', 'name' => 'desc_ro', 'type' => 'textarea', 'value' => $editing['desc_ro'] ?? ''],
                    ],
                ]); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fld-mprice">Цена (€ / ден / човек)</label></th>
                        <td><input id="fld-mprice" type="number" name="price" step="0.01" min="0" value="<?php echo esc_attr($editing['price'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-msort">Подредба</label></th>
                        <td><input id="fld-msort" type="number" name="sort_order" min="0" value="<?php echo (int) ($editing['sort_order'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Видимост</th>
                        <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Показва се при резервация</label></td>
                    </tr>
                </table>

                <p class="submit pino-submit">
                    <button type="submit" class="button button-primary button-large">Запази</button>
                    <a href="?page=pino-meals" class="button button-large">Отказ</a>
                </p>
            </form>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat striped pino-table">
        <thead>
            <tr>
                <th>Име (BG)</th>
                <th>Цена</th>
                <th>Ред</th>
                <th>Видимо</th>
                <th class="col-actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($meals)) : ?>
                <tr><td colspan="5" class="pino-empty">Все още няма добавени ястия.</td></tr>
            <?php else : foreach ($meals as $m) : ?>
                <tr>
                    <td><strong><a href="?page=pino-meals&edit=<?php echo (int) $m['id']; ?>"><?php echo esc_html($m['name_bg']); ?></a></strong></td>
                    <td><?php echo number_format($m['price'], 2); ?> €</td>
                    <td><?php echo (int) ($m['sort_order'] ?? 0); ?></td>
                    <td><?php echo $m['visible'] ? '<span class="dashicons dashicons-yes pino-yes"></span>' : '<span class="dashicons dashicons-hidden pino-no"></span>'; ?></td>
                    <td class="col-actions">
                        <a href="?page=pino-meals&edit=<?php echo (int) $m['id']; ?>" class="button button-small">Редакция</a>
                        <form method="post" class="pino-inline-form pino-confirm-delete">
                            <?php wp_nonce_field('pino_admin_action'); ?>
                            <input type="hidden" name="pino_action" value="delete_meal">
                            <input type="hidden" name="item_id" value="<?php echo (int) $m['id']; ?>">
                            <button type="submit" class="button button-small pino-button-danger">Изтрий</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: RESTAURANT MENU ITEMS
       ══════════════════════════════════════════════════════════════════ */
    public function page_menu_items()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_menu_item(absint($_GET['edit']));
        }
        $adding = isset($_GET['new']);
        $items  = Pino_DB::get_menu_items();
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Меню ресторант</h1>
    <?php if (! $editing && ! $adding) : ?>
        <a href="?page=pino-menu-items&new=1" class="page-title-action">Добави ново ястие</a>
    <?php endif; ?>
    <?php $this->render_notices(); ?>

    <?php if ($editing || $adding) : ?>
        <div class="pino-card pino-form-card">
            <h2><?php echo $editing ? 'Редакция на „' . esc_html($editing['name_bg']) . '"' : 'Ново ястие'; ?></h2>
            <form method="post" class="pino-form">
                <?php wp_nonce_field('pino_admin_action'); ?>
                <input type="hidden" name="pino_action" value="save_menu_item">
                <input type="hidden" name="item_id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

                <?php $this->render_lang_tabs('menu-item', [
                    'bg' => [
                        ['label' => 'Категория (BG)',  'name' => 'category_bg', 'type' => 'text',     'value' => $editing['category_bg'] ?? '', 'required' => true],
                        ['label' => 'Название (BG)',   'name' => 'name_bg',     'type' => 'text',     'value' => $editing['name_bg']     ?? '', 'required' => true],
                        ['label' => 'Описание (BG)',   'name' => 'desc_bg',     'type' => 'textarea', 'value' => $editing['desc_bg']     ?? ''],
                    ],
                    'en' => [
                        ['label' => 'Category (EN)',   'name' => 'category_en', 'type' => 'text',     'value' => $editing['category_en'] ?? ''],
                        ['label' => 'Name (EN)',        'name' => 'name_en',     'type' => 'text',     'value' => $editing['name_en']     ?? ''],
                        ['label' => 'Description (EN)', 'name' => 'desc_en',    'type' => 'textarea', 'value' => $editing['desc_en']     ?? ''],
                    ],
                    'ro' => [
                        ['label' => 'Categorie (RO)',   'name' => 'category_ro', 'type' => 'text',     'value' => $editing['category_ro'] ?? ''],
                        ['label' => 'Denumire (RO)',    'name' => 'name_ro',     'type' => 'text',     'value' => $editing['name_ro']     ?? ''],
                        ['label' => 'Descriere (RO)',   'name' => 'desc_ro',     'type' => 'textarea', 'value' => $editing['desc_ro']     ?? ''],
                    ],
                ]); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="fld-mi-price">Цена (€)</label></th>
                        <td><input id="fld-mi-price" type="number" name="price" step="0.01" min="0" value="<?php echo esc_attr($editing['price'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="fld-mi-sort">Подредба</label></th>
                        <td><input id="fld-mi-sort" type="number" name="sort_order" min="0" value="<?php echo (int) ($editing['sort_order'] ?? 0); ?>" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row">Видимост</th>
                        <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Показва се на сайта</label></td>
                    </tr>
                </table>

                <p class="submit pino-submit">
                    <button type="submit" class="button button-primary button-large">Запази</button>
                    <a href="?page=pino-menu-items" class="button button-large">Отказ</a>
                </p>
            </form>
        </div>
    <?php endif; ?>

    <table class="wp-list-table widefat striped pino-table">
        <thead>
            <tr>
                <th>Категория</th>
                <th>Название (BG)</th>
                <th class="col-num">Цена</th>
                <th>Ред</th>
                <th>Видимо</th>
                <th class="col-actions">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($items)) : ?>
                <tr><td colspan="6" class="pino-empty">Няма добавени ястия в менюто.</td></tr>
            <?php else : foreach ($items as $m) : ?>
                <tr>
                    <td><span class="pino-pill pino-category-badge"><?php echo esc_html($m['category_bg']); ?></span></td>
                    <td><strong><a href="?page=pino-menu-items&edit=<?php echo (int) $m['id']; ?>"><?php echo esc_html($m['name_bg']); ?></a></strong>
                        <?php if (! $m['visible']) echo ' <span class="pino-pill pino-status-cancelled">скрит</span>'; ?>
                    </td>
                    <td class="col-num"><?php echo number_format($m['price'], 2); ?> €</td>
                    <td><?php echo (int) ($m['sort_order'] ?? 0); ?></td>
                    <td><?php echo $m['visible'] ? '<span class="dashicons dashicons-yes pino-yes"></span>' : '<span class="dashicons dashicons-hidden pino-no"></span>'; ?></td>
                    <td class="col-actions">
                        <a href="?page=pino-menu-items&edit=<?php echo (int) $m['id']; ?>" class="button button-small">Редакция</a>
                        <form method="post" class="pino-inline-form pino-confirm-delete">
                            <?php wp_nonce_field('pino_admin_action'); ?>
                            <input type="hidden" name="pino_action" value="delete_menu_item">
                            <input type="hidden" name="item_id" value="<?php echo (int) $m['id']; ?>">
                            <button type="submit" class="button button-small pino-button-danger">Изтрий</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       SHARED HELPERS
       ══════════════════════════════════════════════════════════════════ */
    private function render_notices()
    {
        if (empty($_GET['msg'])) return;
        $msgs = [
            'saved'   => ['success', 'Записът е запазен успешно.'],
            'deleted' => ['success', 'Записът е изтрит.'],
            'updated' => ['success', 'Статусът е обновен.'],
            'error'   => ['error',   'Възникна грешка.'],
        ];
        $code = sanitize_key($_GET['msg']);
        if (!isset($msgs[$code])) return;
        [$type, $text] = $msgs[$code];
        printf(
            '<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
            esc_attr($type),
            esc_html($text)
        );
    }

    /**
     * Renders three labelled tabs (BG/EN/RO) with shared JS toggler.
     * $groups = ['bg'=>[field,field], 'en'=>[...], 'ro'=>[...]]
     */
    private function render_lang_tabs($prefix, $groups)
    {
        static $counter = 0;
        $id = $prefix . '-' . (++$counter);
?>
<div class="pino-tabs" data-pino-tabs="<?php echo esc_attr($id); ?>">
    <nav class="pino-tab-nav" role="tablist">
        <button type="button" class="pino-tab-btn is-active" data-tab="bg" role="tab">BG</button>
        <button type="button" class="pino-tab-btn" data-tab="en" role="tab">EN</button>
        <button type="button" class="pino-tab-btn" data-tab="ro" role="tab">RO</button>
    </nav>
    <?php foreach ($groups as $lang => $fields) : ?>
        <div class="pino-tab-pane<?php echo $lang === 'bg' ? ' is-active' : ''; ?>" data-pane="<?php echo esc_attr($lang); ?>">
            <table class="form-table" role="presentation">
                <?php foreach ($fields as $f) :
                    $required = !empty($f['required']); ?>
                    <tr>
                        <th scope="row"><label for="fld-<?php echo esc_attr($f['name']); ?>"><?php echo esc_html($f['label']); ?></label></th>
                        <td>
                            <?php if ($f['type'] === 'textarea') : ?>
                                <textarea id="fld-<?php echo esc_attr($f['name']); ?>" name="<?php echo esc_attr($f['name']); ?>" rows="3" class="large-text"><?php echo esc_textarea($f['value']); ?></textarea>
                            <?php else : ?>
                                <input id="fld-<?php echo esc_attr($f['name']); ?>" type="text" name="<?php echo esc_attr($f['name']); ?>" class="regular-text" value="<?php echo esc_attr($f['value']); ?>" <?php echo $required ? 'required' : ''; ?>>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endforeach; ?>
</div>
<?php
    }

    /**
     * Media-library image picker – reusable across room types, sections, etc.
     */
    public static function render_image_picker($name, $value)
    {
        $has = ! empty($value);
?>
<div class="pino-image-picker" data-pino-image>
    <div class="pino-image-preview<?php echo $has ? '' : ' is-empty'; ?>">
        <?php if ($has) : ?>
            <img src="<?php echo esc_url($value); ?>" alt="">
        <?php else : ?>
            <span class="dashicons dashicons-format-image"></span>
        <?php endif; ?>
    </div>
    <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" class="pino-image-input">
    <p class="pino-image-actions">
        <button type="button" class="button pino-image-select"><?php echo $has ? 'Смени' : 'Избери от медиите'; ?></button>
        <button type="button" class="button-link pino-image-clear" <?php echo $has ? '' : 'style="display:none;"'; ?>>Премахни</button>
    </p>
</div>
<?php
    }
}

new Pino_Admin();
