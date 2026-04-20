<?php

/**
 * Pino Reservations – Site Content / Global Settings / i18n editors.
 *
 * Adds three submenu pages under the main Pino Reservations menu:
 *   • Settings       (Pino_Content::OPT_SETTINGS)
 *   • Site content   (Pino_Content::OPT_CONTENT)  — homepage sections, multilingual
 *   • Translations   (JSON files in the theme's assets/Website/i18n/)
 */
if (! defined('ABSPATH')) exit;

class Pino_Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_menus'], 20);
        add_action('admin_init', [$this, 'handle_actions']);
        add_action('wp_ajax_pino_save_i18n', [$this, 'ajax_save_i18n']);
    }

    public function register_menus()
    {
        add_submenu_page(
            Pino_Admin::MENU_SLUG,
            __('Начална страница', 'pino-reservations'),
            __('Съдържание', 'pino-reservations'),
            'manage_options',
            'pino-content',
            [$this, 'page_content']
        );

        add_submenu_page(
            Pino_Admin::MENU_SLUG,
            __('Преводи', 'pino-reservations'),
            __('Преводи (i18n)', 'pino-reservations'),
            'manage_options',
            'pino-i18n',
            [$this, 'page_i18n']
        );

        add_submenu_page(
            Pino_Admin::MENU_SLUG,
            __('Общи настройки', 'pino-reservations'),
            __('Настройки', 'pino-reservations'),
            'manage_options',
            'pino-settings',
            [$this, 'page_settings']
        );
    }

    /* ══════════════════════════════════════════════════════════════════
       FORM HANDLERS
       ══════════════════════════════════════════════════════════════════ */
    public function handle_actions()
    {
        if (! current_user_can('manage_options')) return;
        if (empty($_POST['pino_settings_action'])) return;
        check_admin_referer('pino_settings_action');

        $action = sanitize_text_field(wp_unslash($_POST['pino_settings_action']));

        switch ($action) {
            case 'save_settings':
                $this->save_settings_post();
                wp_safe_redirect(admin_url('admin.php?page=pino-settings&msg=saved'));
                exit;

            case 'save_content':
                $this->save_content_post();
                wp_safe_redirect(admin_url('admin.php?page=pino-content&msg=saved'));
                exit;

            case 'save_i18n_bulk':
                $this->save_i18n_bulk_post();
                wp_safe_redirect(admin_url('admin.php?page=pino-i18n&msg=saved'));
                exit;
        }
    }

    private function save_settings_post()
    {
        $raw    = isset($_POST['pino']) && is_array($_POST['pino']) ? wp_unslash($_POST['pino']) : [];
        $clean  = [];
        $fields = Pino_Content::default_settings();

        foreach ($fields as $group => $pairs) {
            $clean[$group] = [];
            foreach ($pairs as $k => $default) {
                $val = $raw[$group][$k] ?? '';
                if (is_string($default) && filter_var($default, FILTER_VALIDATE_URL)) {
                    $clean[$group][$k] = esc_url_raw($val);
                } elseif (is_int($default)) {
                    $clean[$group][$k] = (int) $val;
                } else {
                    $clean[$group][$k] = sanitize_text_field($val);
                }
            }
        }

        update_option(Pino_Content::OPT_SETTINGS, $clean);
    }

    private function save_content_post()
    {
        $raw    = isset($_POST['pino']) && is_array($_POST['pino']) ? wp_unslash($_POST['pino']) : [];
        $schema = Pino_Content::content_schema();
        $clean  = [];

        foreach ($schema as $section => $conf) {
            $clean[$section] = [];
            foreach ($conf['fields'] as $fname => $fconf) {
                $ml   = ! empty($fconf['ml']);
                $type = $fconf['type'];

                if ($ml) {
                    $clean[$section][$fname] = [];
                    foreach (['bg', 'en', 'ro'] as $lang) {
                        $val = $raw[$section][$fname][$lang] ?? '';
                        $clean[$section][$fname][$lang] = $this->clean_by_type($val, $type);
                    }
                } else {
                    $val = $raw[$section][$fname] ?? '';
                    $clean[$section][$fname] = $this->clean_by_type($val, $type);
                }
            }
        }

        update_option(Pino_Content::OPT_CONTENT, $clean);
    }

    private function clean_by_type($val, $type)
    {
        switch ($type) {
            case 'image':    return esc_url_raw($val);
            case 'number':   return (float) $val;
            case 'textarea': return wp_kses_post($val);
            default:         return sanitize_text_field($val);
        }
    }

    private function save_i18n_bulk_post()
    {
        $payload = $_POST['i18n'] ?? [];
        if (! is_array($payload)) return;

        foreach (['bg', 'en', 'ro'] as $lang) {
            if (! isset($payload[$lang]) || ! is_array($payload[$lang])) continue;
            $data = [];
            foreach ($payload[$lang] as $k => $v) {
                $key = sanitize_text_field(wp_unslash($k));
                if ($key === '') continue;
                $data[$key] = wp_kses_post(wp_unslash($v));
            }
            Pino_Content::save_i18n($lang, $data);
        }
    }

    public function ajax_save_i18n()
    {
        if (! current_user_can('manage_options')) wp_send_json_error('forbidden', 403);
        check_ajax_referer('pino_i18n_ajax');

        $lang = sanitize_key($_POST['lang'] ?? '');
        $key  = sanitize_text_field(wp_unslash($_POST['key'] ?? ''));
        $val  = wp_kses_post(wp_unslash($_POST['value'] ?? ''));

        if (! in_array($lang, ['bg', 'en', 'ro'], true) || $key === '') {
            wp_send_json_error('invalid');
        }

        $data       = Pino_Content::load_i18n($lang);
        $data[$key] = $val;
        $ok         = Pino_Content::save_i18n($lang, $data);

        $ok ? wp_send_json_success() : wp_send_json_error('write-failed');
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: GLOBAL SETTINGS
       ══════════════════════════════════════════════════════════════════ */
    public function page_settings()
    {
        $s = Pino_Content::all_settings();
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Общи настройки</h1>
    <?php $this->render_notices(); ?>

    <form method="post" class="pino-form">
        <?php wp_nonce_field('pino_settings_action'); ?>
        <input type="hidden" name="pino_settings_action" value="save_settings">

        <div class="pino-grid-2">
            <div class="pino-card">
                <h2><span class="dashicons dashicons-phone"></span> Контакт</h2>
                <table class="form-table" role="presentation">
                    <tr><th><label>Телефон (за tel:)</label></th>
                        <td><input type="text" name="pino[contact][phone]" class="regular-text" value="<?php echo esc_attr($s['contact']['phone']); ?>" placeholder="+359885185008"></td></tr>
                    <tr><th><label>Телефон (за показване)</label></th>
                        <td><input type="text" name="pino[contact][phone_display]" class="regular-text" value="<?php echo esc_attr($s['contact']['phone_display']); ?>"></td></tr>
                    <tr><th><label>Email</label></th>
                        <td><input type="email" name="pino[contact][email]" class="regular-text" value="<?php echo esc_attr($s['contact']['email']); ?>"></td></tr>
                    <tr><th><label>Адрес (BG)</label></th>
                        <td><input type="text" name="pino[contact][address_bg]" class="regular-text" value="<?php echo esc_attr($s['contact']['address_bg']); ?>"></td></tr>
                    <tr><th><label>Address (EN)</label></th>
                        <td><input type="text" name="pino[contact][address_en]" class="regular-text" value="<?php echo esc_attr($s['contact']['address_en']); ?>"></td></tr>
                    <tr><th><label>Adresă (RO)</label></th>
                        <td><input type="text" name="pino[contact][address_ro]" class="regular-text" value="<?php echo esc_attr($s['contact']['address_ro']); ?>"></td></tr>
                    <tr><th><label>Google Maps URL</label></th>
                        <td><input type="url" name="pino[contact][map_url]" class="large-text code" value="<?php echo esc_attr($s['contact']['map_url']); ?>"></td></tr>
                </table>
            </div>

            <div class="pino-card">
                <h2><span class="dashicons dashicons-clock"></span> Работно време</h2>
                <table class="form-table" role="presentation">
                    <tr><th><label>Общо (BG)</label></th>
                        <td><input type="text" name="pino[hours][general_bg]" class="regular-text" value="<?php echo esc_attr($s['hours']['general_bg']); ?>"></td></tr>
                    <tr><th><label>General (EN)</label></th>
                        <td><input type="text" name="pino[hours][general_en]" class="regular-text" value="<?php echo esc_attr($s['hours']['general_en']); ?>"></td></tr>
                    <tr><th><label>General (RO)</label></th>
                        <td><input type="text" name="pino[hours][general_ro]" class="regular-text" value="<?php echo esc_attr($s['hours']['general_ro']); ?>"></td></tr>
                    <tr><th><label>Ресторант (BG)</label></th>
                        <td><input type="text" name="pino[hours][restaurant_bg]" class="regular-text" value="<?php echo esc_attr($s['hours']['restaurant_bg']); ?>"></td></tr>
                    <tr><th><label>Restaurant (EN)</label></th>
                        <td><input type="text" name="pino[hours][restaurant_en]" class="regular-text" value="<?php echo esc_attr($s['hours']['restaurant_en']); ?>"></td></tr>
                    <tr><th><label>Restaurant (RO)</label></th>
                        <td><input type="text" name="pino[hours][restaurant_ro]" class="regular-text" value="<?php echo esc_attr($s['hours']['restaurant_ro']); ?>"></td></tr>
                </table>
            </div>

            <div class="pino-card">
                <h2><span class="dashicons dashicons-share"></span> Социални мрежи</h2>
                <table class="form-table" role="presentation">
                    <tr><th><label>Facebook</label></th>
                        <td><input type="url" name="pino[social][facebook]" class="large-text code" value="<?php echo esc_attr($s['social']['facebook']); ?>"></td></tr>
                    <tr><th><label>Instagram</label></th>
                        <td><input type="url" name="pino[social][instagram]" class="large-text code" value="<?php echo esc_attr($s['social']['instagram']); ?>"></td></tr>
                    <tr><th><label>YouTube</label></th>
                        <td><input type="url" name="pino[social][youtube]" class="large-text code" value="<?php echo esc_attr($s['social']['youtube']); ?>"></td></tr>
                    <tr><th><label>TikTok</label></th>
                        <td><input type="url" name="pino[social][tiktok]" class="large-text code" value="<?php echo esc_attr($s['social']['tiktok']); ?>"></td></tr>
                </table>
            </div>

            <div class="pino-card">
                <h2><span class="dashicons dashicons-calendar-alt"></span> Резервации</h2>
                <table class="form-table" role="presentation">
                    <tr><th><label>Email за известия</label></th>
                        <td><input type="email" name="pino[booking][notify_email]" class="regular-text" value="<?php echo esc_attr($s['booking']['notify_email']); ?>"></td></tr>
                    <tr><th><label>Автоматично потвърждение</label></th>
                        <td><label><input type="checkbox" name="pino[booking][auto_confirm]" value="1" <?php checked($s['booking']['auto_confirm'], 1); ?>> Новите резервации се маркират като потвърдени веднага</label></td></tr>
                    <tr><th><label>Минимум нощи</label></th>
                        <td><input type="number" min="1" name="pino[booking][min_nights]" value="<?php echo (int) $s['booking']['min_nights']; ?>" class="small-text"></td></tr>
                    <tr><th><label>Символ на валутата</label></th>
                        <td><input type="text" name="pino[booking][currency_symbol]" value="<?php echo esc_attr($s['booking']['currency_symbol']); ?>" class="small-text"></td></tr>
                </table>
            </div>

            <div class="pino-card pino-card-wide">
                <h2><span class="dashicons dashicons-search"></span> SEO</h2>
                <table class="form-table" role="presentation">
                    <tr><th><label>Заглавие на сайта</label></th>
                        <td><input type="text" name="pino[seo][title]" class="large-text" value="<?php echo esc_attr($s['seo']['title']); ?>"></td></tr>
                    <tr><th><label>Meta описание</label></th>
                        <td><textarea name="pino[seo][description]" rows="2" class="large-text"><?php echo esc_textarea($s['seo']['description']); ?></textarea></td></tr>
                </table>
            </div>
        </div>

        <p class="submit pino-submit">
            <button type="submit" class="button button-primary button-large">Запази настройките</button>
        </p>
    </form>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: HOMEPAGE CONTENT
       ══════════════════════════════════════════════════════════════════ */
    public function page_content()
    {
        $schema = Pino_Content::content_schema();
        $stored = Pino_Content::all_content();
        $active = sanitize_key($_GET['section'] ?? array_key_first($schema));
        if (! isset($schema[$active])) $active = array_key_first($schema);
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Съдържание на сайта</h1>
    <p class="description">Всички текстове и изображения, заредени в началната страница и footer, се управляват оттук. Оставете поле празно, за да използвате стойността по подразбиране от темата.</p>
    <?php $this->render_notices(); ?>

    <div class="pino-content-layout">
        <nav class="pino-side-nav">
            <?php foreach ($schema as $slug => $conf) : ?>
                <a href="?page=pino-content&section=<?php echo esc_attr($slug); ?>" class="<?php echo $slug === $active ? 'is-active' : ''; ?>">
                    <?php echo esc_html($conf['label']); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <form method="post" class="pino-form pino-content-main">
            <?php wp_nonce_field('pino_settings_action'); ?>
            <input type="hidden" name="pino_settings_action" value="save_content">

            <?php foreach ($schema as $slug => $conf) : if ($slug !== $active) continue; ?>
                <div class="pino-card">
                    <h2><?php echo esc_html($conf['label']); ?></h2>

                    <?php foreach ($conf['fields'] as $fname => $fconf) :
                        $ml    = ! empty($fconf['ml']);
                        $type  = $fconf['type'];
                        $stored_val = $stored[$slug][$fname] ?? ($ml ? [] : '');
                    ?>
                        <div class="pino-field-group">
                            <label class="pino-field-label"><?php echo esc_html($fconf['label']); ?></label>

                            <?php if ($type === 'image') : ?>
                                <?php Pino_Admin::render_image_picker("pino[$slug][$fname]", is_string($stored_val) ? $stored_val : ''); ?>
                            <?php elseif ($ml) : ?>
                                <div class="pino-lang-grid">
                                    <?php foreach (['bg' => 'BG', 'en' => 'EN', 'ro' => 'RO'] as $l => $label) :
                                        $v = is_array($stored_val) ? ($stored_val[$l] ?? '') : ''; ?>
                                        <div class="pino-lang-field">
                                            <span class="pino-lang-badge"><?php echo $label; ?></span>
                                            <?php if ($type === 'textarea') : ?>
                                                <textarea name="pino[<?php echo esc_attr($slug); ?>][<?php echo esc_attr($fname); ?>][<?php echo $l; ?>]" rows="3" class="large-text"><?php echo esc_textarea($v); ?></textarea>
                                            <?php else : ?>
                                                <input type="text" name="pino[<?php echo esc_attr($slug); ?>][<?php echo esc_attr($fname); ?>][<?php echo $l; ?>]" value="<?php echo esc_attr($v); ?>" class="large-text">
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($type === 'textarea') : ?>
                                <textarea name="pino[<?php echo esc_attr($slug); ?>][<?php echo esc_attr($fname); ?>]" rows="3" class="large-text"><?php echo esc_textarea($stored_val); ?></textarea>
                            <?php else : ?>
                                <input type="text" name="pino[<?php echo esc_attr($slug); ?>][<?php echo esc_attr($fname); ?>]" value="<?php echo esc_attr($stored_val); ?>" class="large-text">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <p class="submit pino-submit">
                <button type="submit" class="button button-primary button-large">Запази съдържанието</button>
            </p>
        </form>
    </div>
</div>
<?php
    }

    /* ══════════════════════════════════════════════════════════════════
       PAGE: i18n JSON EDITOR
       ══════════════════════════════════════════════════════════════════ */
    public function page_i18n()
    {
        $keys = Pino_Content::all_i18n_keys();
        $bg   = Pino_Content::load_i18n('bg');
        $en   = Pino_Content::load_i18n('en');
        $ro   = Pino_Content::load_i18n('ro');
        $dir  = Pino_Content::i18n_dir();
        $writable = is_writable($dir);

        $search = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
?>
<div class="wrap pino-admin">
    <h1 class="wp-heading-inline">Преводи (i18n)</h1>
    <p class="description">Ключовете отговарят на <code>data-i18n="…"</code> атрибутите в темата. Промените се записват директно в JSON файловете.</p>
    <?php $this->render_notices(); ?>

    <?php if (! $writable) : ?>
        <div class="notice notice-error"><p>JSON директорията <code><?php echo esc_html($dir); ?></code> не е записваема. Промените няма да могат да бъдат запазени, докато правата не бъдат коригирани.</p></div>
    <?php endif; ?>

    <form method="get" class="pino-i18n-search">
        <input type="hidden" name="page" value="pino-i18n">
        <input type="search" name="q" value="<?php echo esc_attr($search); ?>" placeholder="Търсене по ключ или стойност…" class="regular-text">
        <button type="submit" class="button">Търси</button>
        <?php if ($search !== '') : ?><a href="?page=pino-i18n" class="button">Изчисти</a><?php endif; ?>
    </form>

    <form method="post" class="pino-form">
        <?php wp_nonce_field('pino_settings_action'); ?>
        <input type="hidden" name="pino_settings_action" value="save_i18n_bulk">

        <table class="wp-list-table widefat striped pino-table pino-i18n-table">
            <thead>
                <tr>
                    <th class="col-i18n-key">Ключ</th>
                    <th>BG</th>
                    <th>EN</th>
                    <th>RO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($keys as $k) :
                    if ($search !== '') {
                        $hay = strtolower($k . ' ' . ($bg[$k] ?? '') . ' ' . ($en[$k] ?? '') . ' ' . ($ro[$k] ?? ''));
                        if (strpos($hay, strtolower($search)) === false) continue;
                    }
                ?>
                    <tr>
                        <td class="col-i18n-key"><code><?php echo esc_html($k); ?></code></td>
                        <td><textarea name="i18n[bg][<?php echo esc_attr($k); ?>]" rows="2"><?php echo esc_textarea($bg[$k] ?? ''); ?></textarea></td>
                        <td><textarea name="i18n[en][<?php echo esc_attr($k); ?>]" rows="2"><?php echo esc_textarea($en[$k] ?? ''); ?></textarea></td>
                        <td><textarea name="i18n[ro][<?php echo esc_attr($k); ?>]" rows="2"><?php echo esc_textarea($ro[$k] ?? ''); ?></textarea></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="submit pino-submit">
            <button type="submit" class="button button-primary button-large" <?php echo $writable ? '' : 'disabled'; ?>>Запази преводите</button>
            <span class="description" style="margin-left:12px;"><?php echo count($keys); ?> ключа общо</span>
        </p>
    </form>
</div>
<?php
    }

    private function render_notices()
    {
        if (empty($_GET['msg'])) return;
        $msgs = [
            'saved' => ['success', 'Промените са запазени.'],
            'error' => ['error',   'Възникна грешка при запис.'],
        ];
        $code = sanitize_key($_GET['msg']);
        if (!isset($msgs[$code])) return;
        [$type, $text] = $msgs[$code];
        printf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr($type), esc_html($text));
    }
}

new Pino_Settings();
