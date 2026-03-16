# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Pino Villa — a boutique hotel WordPress site with a custom reservation system and trilingual support (Bulgarian, English, Romanian). The active theme is `pinovilla` and the custom booking logic lives in the `pino-reservations` plugin.

## Development Workflow

This is a raw WordPress installation with no build tools. All JS/CSS assets are pre-built vendor files — edit them directly, no compilation step needed.

To run locally, serve the WordPress root via a PHP server with MySQL (e.g. Local by Flywheel, MAMP, or `wp-cli`). There are no `npm`, `composer`, or build commands.

Utility/setup scripts live at the repo root (e.g. `seeder.php`, `setup_multilanguage_polylang.php`). Run them via browser or `wp eval-file <script.php>` after placing them in the root.

## Architecture

### Theme (`wp-content/themes/pinovilla/`)

- `functions.php` — enqueues assets, registers menus, defines `pinovilla_language_switcher()`, and adds a rewrite rule for `/RoomAvalability` (case-insensitive).
- `front-page.php` — single-file homepage (~1100 lines) with all sections (hero, rooms, villa, restaurant, halls, testimonials, pricing, contact). All text uses `data-i18n` attributes for client-side translation.
- `page-roomavalability.php` — booking search results page; receives check-in/check-out/guests via URL params, renders availability results via AJAX.
- `header.php` / `footer.php` — navigation and footer with language switcher and `data-i18n` attributes throughout.

Assets live under `assets/Website/{css,js,i18n,images,fonts}/`. Libraries are vendored statically (Bootstrap, GSAP, Slick, Swiper, jQuery, etc.).

### Pino Reservations Plugin (`wp-content/plugins/pino-reservations/`)

Custom booking engine with 5 custom DB tables (prefixed `wp_pino_`):

| Table | Purpose |
|---|---|
| `wp_pino_room_types` | Room categories (7 types; names in bg/en/ro) |
| `wp_pino_rooms` | Physical rooms (15 rooms + 1 villa) |
| `wp_pino_meals` | Meal options with trilingual names/descriptions |
| `wp_pino_reservations` | Booking records (status 0=pending, 1=confirmed, 2=cancelled) |
| `wp_pino_reservation_details` | Room allocations per reservation |
| `wp_pino_reservation_meals` | Meals per reservation |

Key classes:
- `Pino_DB` (`includes/class-pino-db.php`) — static DB abstraction; `get_available_room_ids($start, $end)` returns rooms not booked in range.
- `Pino_Availability` (`includes/class-pino-availability.php`) — ported from original .NET logic; `get_combinations($start, $end, $guests)` returns valid room-type combos where capacity is within `[guests, guests+2]`. Generates single-type (1–2 rooms) and two-type (1 room each) combinations.
- `Pino_Public` — registers AJAX endpoints `pino_check_availability` and `pino_submit_booking`, and the `[pino_booking]` shortcode.
- `Pino_Admin` — admin menu pages for reservations, room types, rooms, and meals.

AJAX booking flow: homepage/availability form → `pino_check_availability` → user selects combo + meals → `pino_submit_booking` (nonce-validated) → reservation created with `status=0`.

### Multilanguage

**Dual strategy:**
1. **Polylang (server-side)** — when active, creates `/bg/`, `/en/`, `/ro/` URL variants of each page. `pll_current_language()`, `pll_get_post()`, and `pll_the_languages()` are used in theme files.
2. **`translate.js` (client-side fallback)** — detects language from HTML `lang` attribute → `pll_language` cookie → `localStorage` → defaults to `bg`. Translates all `[data-i18n]` elements using JSON files at `assets/Website/i18n/{bg,en,ro}.json`. Uses a `MutationObserver` to handle dynamically added elements. Language preference is persisted in both a cookie (365 days) and `localStorage`.

When adding translatable UI text: add a key to all three JSON files **and** use `data-i18n="your.key"` in the HTML.

### Admin-facing language

Room types and meals store names and descriptions in separate columns: `name_bg`, `name_en`, `name_ro` (and `desc_bg`, `desc_en`, `desc_ro` for meals). Always handle all three when adding/modifying these records.
