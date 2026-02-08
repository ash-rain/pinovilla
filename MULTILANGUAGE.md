# Multilanguage (Polylang)

This repo uses **Polylang** to serve Bulgarian (`bg`), English (`en`), and Romanian (`ro`) versions of the site.

## One-time setup

1) Make sure WordPress is installed and `wp-config.php` points to your DB.

2) (Optional) Seed the pages (creates `home`, `rooms`, `villa`, `restaurant`, `halls`, `about`, `contact`, `policy`, `terms`):

```bash
cd /Users/boyan/Documents/_DEV/pinovilla
php seeder.php
```

3) Configure Polylang + create/link the translated pages:

```bash
cd /Users/boyan/Documents/_DEV/pinovilla
php setup_multilanguage_polylang.php
```

## Translating content

`setup_multilanguage_polylang.php` duplicates the Bulgarian page HTML into English/Romanian and links translations in Polylang.

To provide real translations, edit the EN/RO pages in **WP Admin → Pages** (or in Polylang’s language columns).

## Notes

- The theme language switcher is Polylang-powered (links to `/bg/`, `/en/`, `/ro/` depending on your Polylang settings).
- Internal theme links were normalized to the seeded lowercase slugs (`/rooms`, `/about`, etc.).
