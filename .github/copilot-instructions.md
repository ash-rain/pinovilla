# Copilot Instructions

## Project Overview

Pino Villa — a boutique hotel WordPress site with a custom reservation engine and trilingual support (Bulgarian, English, Romanian). The active theme is `pinovilla`; the booking engine lives in the `pino-reservations` plugin.

## No Build Step

No `npm`, `composer`, or build commands exist. All JS/CSS assets under `wp-content/themes/pinovilla/assets/Website/{css,js}/` are pre-built vendor files — edit them directly.

Run locally via Local by Flywheel, MAMP, or any PHP+MySQL stack. Utility/setup scripts at the repo root (e.g. `seeder.php`, `setup_multilanguage_polylang.php`) run via browser or:

```bash
wp eval-file <script.php>
```

## Architecture

### Theme (`wp-content/themes/pinovilla/`)

- **`front-page.php`** — ~1100-line single-file homepage; all sections (hero, rooms, villa, restaurant, halls, testimonials, pricing, contact) are here. All visible text uses `data-i18n` attributes for client-side translation.
- **`page-roomavalability.php`** — booking results page (note: intentional typo in slug/filename). Receives `check_in`, `check_out`, `guests` via URL params; renders combos via AJAX.
- **`functions.php`** — enqueues all assets, registers the `primary` nav menu, defines `pinovilla_language_switcher()`, and adds a case-insensitive rewrite rule for `/RoomAvalability`.
- **`header.php` / `footer.php`** — contain `data-i18n` attributes throughout; the language switcher lives in the footer.

### Plugin (`wp-content/plugins/pino-reservations/`)

**DB tables** (all prefixed `wp_pino_`):

| Table | Purpose |
|---|---|
| `room_types` | 7 room categories; trilingual names/descriptions + capacity + price |
| `rooms` | 15 physical rooms + 1 villa, linked to a `room_type_id` |
| `meals` | Meal options; trilingual |
| `reservations` | Booking records; `status` 0=pending, 1=confirmed, 2=cancelled |
| `reservation_details` | Room allocations per reservation |
| `reservation_meals` | Meals per reservation |
| `menu_items` | Restaurant menu; trilingual with categories |

**Key classes:**

- `Pino_DB` (`includes/class-pino-db.php`) — static DB abstraction. Use `Pino_DB::t('room_types')` to get a prefixed table name. `get_available_room_ids($start, $end)` is the core availability query.
- `Pino_Availability` (`includes/class-pino-availability.php`) — ported from original .NET logic. `get_combinations($start, $end, $guests)` returns valid room-type combos where total capacity is in `[guests, guests+2]`. Generates single-type (1–2 rooms) and two-type (1 room each) combinations.
- `Pino_Content` (`includes/class-pino-content.php`) — surfaces admin-editable content to the theme via `pino_setting($key)` and `pino_content($section, $key, $lang)`. Falls back gracefully; the front-end never breaks on a missing option.
- `Pino_Public` — registers AJAX endpoints `pino_check_availability` and `pino_submit_booking` (nonce-validated), and the `[pino_booking]` shortcode.
- `Pino_Admin` + `Pino_Settings` — admin menus for reservations, room types, rooms, meals, site content, and the i18n JSON editor.

**AJAX booking flow:**
`pino_check_availability` → user picks combo + meals → `pino_submit_booking` → reservation inserted with `status=0`.

**Plugin versioning:** DB schema upgrades run automatically on `plugins_loaded` by comparing `pino_res_db_version` option to `PINO_RES_VERSION`. Use `dbDelta()` for all schema changes.

## Multilanguage

**Dual strategy — both must stay in sync:**

1. **Polylang (server-side)** — creates `/bg/`, `/en/`, `/ro/` URL variants. Use `pll_current_language()`, `pll_get_post()`, `pll_the_languages()` in theme PHP. Falls back gracefully when Polylang isn't active.
2. **`translate.js` (client-side fallback)** — reads `data-i18n` attributes, loads JSON from `assets/Website/i18n/{bg,en,ro}.json`, uses a `MutationObserver` for dynamic content. Language priority: HTML `lang` attr → `pll_language` cookie → `localStorage` → defaults to `bg`. Persists choice in cookie (365 days) + `localStorage`.

**When adding any translatable UI text:**
- Add the key to **all three** JSON files (`bg.json`, `en.json`, `ro.json`).
- Use `data-i18n="your.key"` on the HTML element.

**DB content (room types, meals, menu items)** stores trilingual text in separate columns: `name_bg` / `name_en` / `name_ro` and `desc_bg` / `desc_en` / `desc_ro`. Always populate all three when inserting or updating these records.

## Key Conventions

- The booking page slug/filename uses a consistent typo: `roomavalability` (single `i` in "Availability"). Don't "fix" it — the rewrite rule and template loader depend on it.
- All AJAX handlers validate with `check_ajax_referer()` / nonces — never skip this for `pino_submit_booking`.
- `Pino_DB` uses `ARRAY_A` for all query returns; expect associative arrays, not objects.
- Admin assets only load on pages whose `$hook` contains `pino-` — checked in `Pino_Admin::enqueue_assets()`.
- The `wp_pino_reservation_details.room_id` column is nullable — it records which specific physical room was assigned, but assignment can happen later.
