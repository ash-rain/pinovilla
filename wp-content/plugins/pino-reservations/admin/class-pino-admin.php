<?php

/**
 * Pino Reservations – Admin Panel
 */
if (! defined('ABSPATH')) exit;

class Pino_Admin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menus']);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets($hook)
    {
        if (strpos($hook, 'pino-') === false) return;
        wp_enqueue_style('pino-admin', PINO_RES_URL . 'admin/css/admin.css', [], PINO_RES_VERSION);
    }

    public function register_menus()
    {
        add_menu_page(
            'Pino Reservations',
            'Резервации',
            'manage_options',
            'pino-reservations',
            [$this, 'page_reservations'],
            'dashicons-calendar-alt',
            26
        );

        add_submenu_page('pino-reservations', 'Всички резервации', 'Всички резервации', 'manage_options', 'pino-reservations',      [$this, 'page_reservations']);
        add_submenu_page('pino-reservations', 'Типове стаи',       'Типове стаи',        'manage_options', 'pino-room-types',        [$this, 'page_room_types']);
        add_submenu_page('pino-reservations', 'Стаи',              'Стаи',               'manage_options', 'pino-rooms',             [$this, 'page_rooms']);
        add_submenu_page('pino-reservations', 'Хранене',           'Хранене',            'manage_options', 'pino-meals',             [$this, 'page_meals']);
    }

    /* ──────────────────────────────────────────
       ACTION HANDLER (POST forms)
       ────────────────────────────────────────── */
    public function handle_actions()
    {
        if (! current_user_can('manage_options')) return;
        if (empty($_POST['pino_action'])) return;
        check_admin_referer('pino_admin_action');

        $action = sanitize_text_field($_POST['pino_action']);

        switch ($action) {
            /* ── Room Types ── */
            case 'save_room_type':
                $data = [
                    'name_bg'    => sanitize_text_field($_POST['name_bg'] ?? ''),
                    'name_en'    => sanitize_text_field($_POST['name_en'] ?? ''),
                    'name_ro'    => sanitize_text_field($_POST['name_ro'] ?? ''),
                    'capacity'   => absint($_POST['capacity'] ?? 2),
                    'price'      => floatval($_POST['price'] ?? 0),
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

                /* ── Rooms ── */
            case 'save_room':
                $data = [
                    'name'         => sanitize_text_field($_POST['name'] ?? ''),
                    'room_type_id' => absint($_POST['room_type_id'] ?? 0),
                    'floor'        => intval($_POST['floor'] ?? 1),
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

                /* ── Meals ── */
            case 'save_meal':
                $data = [
                    'name_bg'  => sanitize_text_field($_POST['name_bg'] ?? ''),
                    'name_en'  => sanitize_text_field($_POST['name_en'] ?? ''),
                    'name_ro'  => sanitize_text_field($_POST['name_ro'] ?? ''),
                    'desc_bg'  => sanitize_textarea_field($_POST['desc_bg'] ?? ''),
                    'desc_en'  => sanitize_textarea_field($_POST['desc_en'] ?? ''),
                    'desc_ro'  => sanitize_textarea_field($_POST['desc_ro'] ?? ''),
                    'price'    => floatval($_POST['price'] ?? 0),
                    'visible'  => isset($_POST['visible']) ? 1 : 0,
                ];
                $id = absint($_POST['item_id'] ?? 0);
                Pino_DB::save_meal($data, $id ?: null);
                wp_safe_redirect(admin_url('admin.php?page=pino-meals&msg=saved'));
                exit;

            case 'delete_meal':
                Pino_DB::delete_meal(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-meals&msg=deleted'));
                exit;

                /* ── Reservation status ── */
            case 'update_reservation_status':
                $id     = absint($_POST['item_id'] ?? 0);
                $status = intval($_POST['new_status'] ?? 0);
                Pino_DB::update_reservation_status($id, $status);
                wp_safe_redirect(admin_url('admin.php?page=pino-reservations&msg=updated'));
                exit;

            case 'delete_reservation':
                Pino_DB::delete_reservation(absint($_POST['item_id']));
                wp_safe_redirect(admin_url('admin.php?page=pino-reservations&msg=deleted'));
                exit;
        }
    }

    /* ══════════════════════════════════════════════
       PAGE: RESERVATIONS
       ══════════════════════════════════════════════ */
    public function page_reservations()
    {
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
        $args = ['limit' => 200];
        if ($status_filter !== '') $args['status'] = (int) $status_filter;

        $reservations = Pino_DB::get_reservations($args);

        // Detail view?
        if (isset($_GET['view'])) {
            $res = Pino_DB::get_reservation(absint($_GET['view']));
            if ($res) {
                $this->render_reservation_detail($res);
                return;
            }
        }

        $status_labels = [0 => 'Нова', 1 => 'Потвърдена', 2 => 'Отказана'];
        $status_colors = [0 => '#f0ad4e', 1 => '#5cb85c', 2 => '#d9534f'];

        $count_all     = Pino_DB::count_reservations();
        $count_pending = Pino_DB::count_reservations(0);
        $count_conf    = Pino_DB::count_reservations(1);
?>
        <div class="wrap pino-admin">
            <h1>Резервации</h1>
            <?php $this->render_notices(); ?>

            <div class="pino-stats">
                <a href="?page=pino-reservations" class="pino-stat-box">
                    <span class="pino-stat-num"><?php echo $count_all; ?></span>
                    <span>Общо</span>
                </a>
                <a href="?page=pino-reservations&status=0" class="pino-stat-box" style="border-left-color:#f0ad4e;">
                    <span class="pino-stat-num"><?php echo $count_pending; ?></span>
                    <span>Нови</span>
                </a>
                <a href="?page=pino-reservations&status=1" class="pino-stat-box" style="border-left-color:#5cb85c;">
                    <span class="pino-stat-num"><?php echo $count_conf; ?></span>
                    <span>Потвърдени</span>
                </a>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:50px;">ID</th>
                        <th>Име</th>
                        <th>Email / Телефон</th>
                        <th>Период</th>
                        <th>Гости</th>
                        <th>Цена (лв.)</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reservations)) : ?>
                        <tr>
                            <td colspan="8">Няма резервации.</td>
                        </tr>
                        <?php else : foreach ($reservations as $r) : ?>
                            <tr>
                                <td><?php echo $r['id']; ?></td>
                                <td><strong><?php echo esc_html($r['first_name'] . ' ' . $r['last_name']); ?></strong></td>
                                <td><?php echo esc_html($r['email']); ?><br><small><?php echo esc_html($r['phone']); ?></small></td>
                                <td><?php echo esc_html($r['start_date'] . ' → ' . $r['end_date']); ?></td>
                                <td><?php echo $r['guests']; ?></td>
                                <td><?php echo number_format($r['price'], 2); ?></td>
                                <td>
                                    <span class="pino-badge" style="background:<?php echo $status_colors[$r['status']] ?? '#999'; ?>">
                                        <?php echo $status_labels[$r['status']] ?? $r['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=pino-reservations&view=<?php echo $r['id']; ?>" class="button button-small">Преглед</a>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    private function render_reservation_detail($res)
    {
        $status_labels = [0 => 'Нова', 1 => 'Потвърдена', 2 => 'Отказана'];
    ?>
        <div class="wrap pino-admin">
            <h1>Резервация #<?php echo $res['id']; ?></h1>
            <a href="?page=pino-reservations" class="button">&laquo; Назад</a>

            <div class="pino-detail-grid">
                <div class="pino-detail-card">
                    <h3>Клиент</h3>
                    <p><strong>Име:</strong> <?php echo esc_html($res['first_name'] . ' ' . $res['last_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo esc_html($res['email']); ?></p>
                    <p><strong>Телефон:</strong> <?php echo esc_html($res['phone']); ?></p>
                </div>
                <div class="pino-detail-card">
                    <h3>Престой</h3>
                    <p><strong>Период:</strong> <?php echo esc_html($res['start_date'] . ' → ' . $res['end_date']); ?></p>
                    <p><strong>Гости:</strong> <?php echo $res['guests']; ?></p>
                    <p><strong>Обща цена:</strong> <?php echo number_format($res['price'], 2); ?> лв.</p>
                    <p><strong>Статус:</strong> <?php echo $status_labels[$res['status']] ?? $res['status']; ?></p>
                    <p><strong>Дата на създаване:</strong> <?php echo esc_html($res['created_at']); ?></p>
                </div>
            </div>

            <?php if (! empty($res['details'])) : ?>
                <h3>Стаи</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Тип стая</th>
                            <th>Нощувки</th>
                            <th>Стая №</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($res['details'] as $d) : ?>
                            <tr>
                                <td><?php echo esc_html($d['type_name'] ?? 'Тип #' . $d['room_type_id']); ?></td>
                                <td><?php echo $d['nights']; ?></td>
                                <td><?php echo $d['room_id'] ? $d['room_id'] : '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if (! empty($res['meals'])) : ?>
                <h3>Хранене</h3>
                <ul>
                    <?php foreach ($res['meals'] as $m) : ?>
                        <li><?php echo esc_html($m['meal_name'] ?? 'Meal #' . $m['meal_id']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h3>Промяна на статус</h3>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <?php foreach ([0 => 'Нова', 1 => 'Потвърди', 2 => 'Откажи'] as $s => $label) : ?>
                    <form method="post">
                        <?php wp_nonce_field('pino_admin_action'); ?>
                        <input type="hidden" name="pino_action" value="update_reservation_status">
                        <input type="hidden" name="item_id" value="<?php echo $res['id']; ?>">
                        <input type="hidden" name="new_status" value="<?php echo $s; ?>">
                        <button type="submit" class="button <?php echo $s == 1 ? 'button-primary' : ''; ?>"><?php echo $label; ?></button>
                    </form>
                <?php endforeach; ?>

                <form method="post" onsubmit="return confirm('Сигурни ли сте?');">
                    <?php wp_nonce_field('pino_admin_action'); ?>
                    <input type="hidden" name="pino_action" value="delete_reservation">
                    <input type="hidden" name="item_id" value="<?php echo $res['id']; ?>">
                    <button type="submit" class="button" style="color:red;">Изтрий</button>
                </form>
            </div>

            <?php if (! empty($res['notes'])) : ?>
                <h3>Бележки</h3>
                <p><?php echo nl2br(esc_html($res['notes'])); ?></p>
            <?php endif; ?>
        </div>
    <?php
    }

    /* ══════════════════════════════════════════════
       PAGE: ROOM TYPES
       ══════════════════════════════════════════════ */
    public function page_room_types()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_room_type(absint($_GET['edit']));
        }
        $types = Pino_DB::get_room_types();
    ?>
        <div class="wrap pino-admin">
            <h1>Типове стаи</h1>
            <?php $this->render_notices(); ?>

            <div class="pino-two-col">
                <div class="pino-col-form">
                    <h2><?php echo $editing ? 'Редакция' : 'Нов тип стая'; ?></h2>
                    <form method="post">
                        <?php wp_nonce_field('pino_admin_action'); ?>
                        <input type="hidden" name="pino_action" value="save_room_type">
                        <input type="hidden" name="item_id" value="<?php echo $editing['id'] ?? 0; ?>">

                        <table class="form-table">
                            <tr>
                                <th>Име (BG)</th>
                                <td><input type="text" name="name_bg" class="regular-text" value="<?php echo esc_attr($editing['name_bg'] ?? ''); ?>" required></td>
                            </tr>
                            <tr>
                                <th>Име (EN)</th>
                                <td><input type="text" name="name_en" class="regular-text" value="<?php echo esc_attr($editing['name_en'] ?? ''); ?>"></td>
                            </tr>
                            <tr>
                                <th>Име (RO)</th>
                                <td><input type="text" name="name_ro" class="regular-text" value="<?php echo esc_attr($editing['name_ro'] ?? ''); ?>"></td>
                            </tr>
                            <tr>
                                <th>Капацитет</th>
                                <td><input type="number" name="capacity" min="1" value="<?php echo $editing['capacity'] ?? 2; ?>"></td>
                            </tr>
                            <tr>
                                <th>Цена / нощ (лв.)</th>
                                <td><input type="number" name="price" step="0.01" min="0" value="<?php echo $editing['price'] ?? 0; ?>"></td>
                            </tr>
                            <tr>
                                <th>Подредба</th>
                                <td><input type="number" name="sort_order" min="0" value="<?php echo $editing['sort_order'] ?? 0; ?>"></td>
                            </tr>
                            <tr>
                                <th>Видим</th>
                                <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Да</label></td>
                            </tr>
                        </table>

                        <p class="submit"><button type="submit" class="button button-primary">Запази</button>
                            <?php if ($editing) : ?><a href="?page=pino-room-types" class="button">Отказ</a><?php endif; ?></p>
                    </form>
                </div>

                <div class="pino-col-table">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Име (BG)</th>
                                <th>Капацитет</th>
                                <th>Цена</th>
                                <th>Ред</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $t) : ?>
                                <tr>
                                    <td><?php echo $t['id']; ?></td>
                                    <td><?php echo esc_html($t['name_bg']); ?> <?php if (! $t['visible']) echo '<em>(скрит)</em>'; ?></td>
                                    <td><?php echo $t['capacity']; ?></td>
                                    <td><?php echo number_format($t['price'], 2); ?></td>
                                    <td><?php echo $t['sort_order']; ?></td>
                                    <td>
                                        <a href="?page=pino-room-types&edit=<?php echo $t['id']; ?>" class="button button-small">Редакция</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Изтриване?');">
                                            <?php wp_nonce_field('pino_admin_action'); ?>
                                            <input type="hidden" name="pino_action" value="delete_room_type">
                                            <input type="hidden" name="item_id" value="<?php echo $t['id']; ?>">
                                            <button type="submit" class="button button-small" style="color:red;">Изтрий</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }

    /* ══════════════════════════════════════════════
       PAGE: ROOMS
       ══════════════════════════════════════════════ */
    public function page_rooms()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_room(absint($_GET['edit']));
        }
        $rooms = Pino_DB::get_rooms();
        $types = Pino_DB::get_room_types();
    ?>
        <div class="wrap pino-admin">
            <h1>Стаи</h1>
            <?php $this->render_notices(); ?>

            <div class="pino-two-col">
                <div class="pino-col-form">
                    <h2><?php echo $editing ? 'Редакция' : 'Нова стая'; ?></h2>
                    <form method="post">
                        <?php wp_nonce_field('pino_admin_action'); ?>
                        <input type="hidden" name="pino_action" value="save_room">
                        <input type="hidden" name="item_id" value="<?php echo $editing['id'] ?? 0; ?>">

                        <table class="form-table">
                            <tr>
                                <th>Наименование</th>
                                <td><input type="text" name="name" class="regular-text" value="<?php echo esc_attr($editing['name'] ?? ''); ?>" required></td>
                            </tr>
                            <tr>
                                <th>Тип стая</th>
                                <td>
                                    <select name="room_type_id" required>
                                        <?php foreach ($types as $t) : ?>
                                            <option value="<?php echo $t['id']; ?>" <?php selected($editing['room_type_id'] ?? 0, $t['id']); ?>><?php echo esc_html($t['name_bg']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Етаж</th>
                                <td><input type="number" name="floor" value="<?php echo $editing['floor'] ?? 1; ?>"></td>
                            </tr>
                            <tr>
                                <th>Видима</th>
                                <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Да</label></td>
                            </tr>
                        </table>

                        <p class="submit"><button type="submit" class="button button-primary">Запази</button>
                            <?php if ($editing) : ?><a href="?page=pino-rooms" class="button">Отказ</a><?php endif; ?></p>
                    </form>
                </div>

                <div class="pino-col-table">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Наименование</th>
                                <th>Тип</th>
                                <th>Етаж</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rooms as $r) : ?>
                                <tr>
                                    <td><?php echo $r['id']; ?></td>
                                    <td><?php echo esc_html($r['name']); ?> <?php if (! $r['visible']) echo '<em>(скрита)</em>'; ?></td>
                                    <td><?php echo esc_html($r['type_name'] ?? ''); ?></td>
                                    <td><?php echo $r['floor']; ?></td>
                                    <td>
                                        <a href="?page=pino-rooms&edit=<?php echo $r['id']; ?>" class="button button-small">Редакция</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Изтриване?');">
                                            <?php wp_nonce_field('pino_admin_action'); ?>
                                            <input type="hidden" name="pino_action" value="delete_room">
                                            <input type="hidden" name="item_id" value="<?php echo $r['id']; ?>">
                                            <button type="submit" class="button button-small" style="color:red;">Изтрий</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }

    /* ══════════════════════════════════════════════
       PAGE: MEALS
       ══════════════════════════════════════════════ */
    public function page_meals()
    {
        $editing = null;
        if (isset($_GET['edit'])) {
            $editing = Pino_DB::get_meal(absint($_GET['edit']));
        }
        $meals = Pino_DB::get_meals();
    ?>
        <div class="wrap pino-admin">
            <h1>Хранене</h1>
            <?php $this->render_notices(); ?>

            <div class="pino-two-col">
                <div class="pino-col-form">
                    <h2><?php echo $editing ? 'Редакция' : 'Ново хранене'; ?></h2>
                    <form method="post">
                        <?php wp_nonce_field('pino_admin_action'); ?>
                        <input type="hidden" name="pino_action" value="save_meal">
                        <input type="hidden" name="item_id" value="<?php echo $editing['id'] ?? 0; ?>">

                        <table class="form-table">
                            <tr>
                                <th>Име (BG)</th>
                                <td><input type="text" name="name_bg" class="regular-text" value="<?php echo esc_attr($editing['name_bg'] ?? ''); ?>" required></td>
                            </tr>
                            <tr>
                                <th>Име (EN)</th>
                                <td><input type="text" name="name_en" class="regular-text" value="<?php echo esc_attr($editing['name_en'] ?? ''); ?>"></td>
                            </tr>
                            <tr>
                                <th>Име (RO)</th>
                                <td><input type="text" name="name_ro" class="regular-text" value="<?php echo esc_attr($editing['name_ro'] ?? ''); ?>"></td>
                            </tr>
                            <tr>
                                <th>Описание (BG)</th>
                                <td><textarea name="desc_bg" rows="2" class="large-text"><?php echo esc_textarea($editing['desc_bg'] ?? ''); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Описание (EN)</th>
                                <td><textarea name="desc_en" rows="2" class="large-text"><?php echo esc_textarea($editing['desc_en'] ?? ''); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Описание (RO)</th>
                                <td><textarea name="desc_ro" rows="2" class="large-text"><?php echo esc_textarea($editing['desc_ro'] ?? ''); ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Цена (лв./ден/човек)</th>
                                <td><input type="number" name="price" step="0.01" min="0" value="<?php echo $editing['price'] ?? 0; ?>"></td>
                            </tr>
                            <tr>
                                <th>Видимо</th>
                                <td><label><input type="checkbox" name="visible" <?php checked($editing['visible'] ?? 1, 1); ?>> Да</label></td>
                            </tr>
                        </table>

                        <p class="submit"><button type="submit" class="button button-primary">Запази</button>
                            <?php if ($editing) : ?><a href="?page=pino-meals" class="button">Отказ</a><?php endif; ?></p>
                    </form>
                </div>

                <div class="pino-col-table">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Име (BG)</th>
                                <th>Цена</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($meals as $m) : ?>
                                <tr>
                                    <td><?php echo $m['id']; ?></td>
                                    <td><?php echo esc_html($m['name_bg']); ?> <?php if (! $m['visible']) echo '<em>(скрито)</em>'; ?></td>
                                    <td><?php echo number_format($m['price'], 2); ?> лв.</td>
                                    <td>
                                        <a href="?page=pino-meals&edit=<?php echo $m['id']; ?>" class="button button-small">Редакция</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Изтриване?');">
                                            <?php wp_nonce_field('pino_admin_action'); ?>
                                            <input type="hidden" name="pino_action" value="delete_meal">
                                            <input type="hidden" name="item_id" value="<?php echo $m['id']; ?>">
                                            <button type="submit" class="button button-small" style="color:red;">Изтрий</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php
    }

    private function render_notices()
    {
        if (! empty($_GET['msg'])) {
            $msgs = [
                'saved'   => 'Записът е запазен успешно.',
                'deleted' => 'Записът е изтрит.',
                'updated' => 'Статусът е обновен.',
            ];
            $txt = $msgs[$_GET['msg']] ?? '';
            if ($txt) {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($txt) . '</p></div>';
            }
        }
    }
}

new Pino_Admin();
