/* /wwwroot/assets/Website/js/translator.js
   Lightweight runtime i18n + DB text switching + live DOM observer
   ------------------------------------------------------------------ */
(() => {
    /* ----------  constants ---------- */
    const COOKIE_NAME  = "pll_language";
    const STORAGE_KEY  = "pino.lang";
    const DEFAULT_LANG = "bg";
    const SUPPORTED    = ["bg", "en", "ro"];
    const COOKIE_DAYS  = 365;

    /* ----------  cookie helpers ---------- */
    function getCookie(name) {
        const match = document.cookie.match(new RegExp("(?:^|; )" + name + "=([^;]*)"));
        return match ? decodeURIComponent(match[1]) : null;
    }

    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + days * 86400000);
        document.cookie = name + "=" + encodeURIComponent(value)
            + ";expires=" + d.toUTCString()
            + ";path=/;SameSite=Lax";
    }

    /* ----------  detect language ---------- */
    /* Priority: HTML lang attr (set by Polylang) > cookie > localStorage > default */
    const htmlLang = (document.documentElement.getAttribute("lang") || "").split("-")[0].toLowerCase();
    const cookieLang = getCookie(COOKIE_NAME);

    let current = DEFAULT_LANG;
    if (htmlLang && SUPPORTED.includes(htmlLang)) {
        current = htmlLang;
    } else if (cookieLang && SUPPORTED.includes(cookieLang)) {
        current = cookieLang;
    } else {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored && SUPPORTED.includes(stored)) {
            current = stored;
        }
    }

    let dict = {};

    /* ----------  helpers ---------- */
    const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
    const getTxt = (key) => (key && dict[key]) || null;

    /* ----------  persist language choice ---------- */
    function persistLang(lang) {
        setCookie(COOKIE_NAME, lang, COOKIE_DAYS);
        localStorage.setItem(STORAGE_KEY, lang);
    }

    /* ----------  JSON loader ---------- */
    async function load(lang) {
        if (lang === current && Object.keys(dict).length > 0) return;
        try {
            const base = (typeof pinoI18n !== "undefined" && pinoI18n.basePath)
                ? pinoI18n.basePath
                : "/assets/Website/i18n";
            const res = await fetch(`${base}/${lang}.json`, { cache: "no-store" });
            if (!res.ok) throw new Error(res.statusText);
            dict    = await res.json();
            current = lang;
            persistLang(lang);
            translate();
            updateLangBadges();
        } catch (err) {
            console.error("Translation load failed:", err);
        }
    }

    /* ----------  translate static text (data-i18n) ---------- */
    function translateStatic(root = document) {
        qsa("[data-i18n]", root).forEach(el => {
            const txt = getTxt(el.dataset.i18n);
            if (!txt) return;
            if (["INPUT","TEXTAREA"].includes(el.tagName)) {
                el.placeholder = txt;
            } else {
                el.innerHTML = txt;
            }
        });
    }

    /* ----------  translate placeholders (data-i18n-placeholder) ---------- */
    function translatePlaceholders(root = document) {
        qsa("[data-i18n-placeholder]", root).forEach(el => {
            const key = el.dataset.i18nPlaceholder;
            const txt = getTxt(key);
            if (!txt) return;
            el.placeholder = txt;
        });
    }

    /* ----------  translate data-t-* (DB-provided text fields) ---------- */
    function translateDbText(root) {
        qsa("[data-t-bg],[data-t-en],[data-t-ro]", root).forEach(el => {
            const txt = el.getAttribute(`data-t-${current}`);
            if (!txt) return;

            if (el.tagName === "OPTION") {
                el.textContent = txt;
                return;
            }

            if (["INPUT","TEXTAREA"].includes(el.tagName)) {
                if (!el.value) el.value = "";
                el.placeholder = txt;
            } else {
                el.textContent = txt;
            }
        });
    }

    /* ----------  language switch buttons ---------- */
    function initSwitcher() {
        /* .lang-switch buttons (custom class) */
        qsa(".lang-switch").forEach(btn =>
            btn.addEventListener("click", e => {
                e.preventDefault();
                load(btn.dataset.lang);
            })
        );

        /* Polylang language switcher links in .lang-switch-container */
        qsa(".lang-switch-container a[data-lang]").forEach(link =>
            link.addEventListener("click", () => {
                /* Let the navigation happen (Polylang URL), but set the cookie first */
                persistLang(link.dataset.lang);
            })
        );
    }

    /* ----------  navbar badge(s) ---------- */
    function updateLangBadges() {
        qsa(".selected-lang").forEach(badge => (badge.textContent = current.toUpperCase()));
        /* Highlight the active language link */
        qsa(".lang-switch-container a[data-lang]").forEach(link => {
            link.style.fontWeight = (link.dataset.lang === current) ? "bold" : "normal";
            link.style.textDecoration = (link.dataset.lang === current) ? "underline" : "none";
        });
    }

    /* ----------  observe late DOM inserts ---------- */
    function initMutationObserver() {
        const observer = new MutationObserver(records => {
            for (const rec of records) {
                rec.addedNodes.forEach(node => {
                    if (node.nodeType === 1) translate(node);
                });
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    /* ----------  orchestrator ---------- */
    function translate(root = document) {
        translateStatic(root);
        translatePlaceholders(root);
        translateDbText(root);
    }

    /* ----------  boot ---------- */
    document.addEventListener("DOMContentLoaded", () => {
        initSwitcher();
        initMutationObserver();
        updateLangBadges();

        /* Always load the dictionary for the current language */
        load(current);
    });

    /* ----------  expose ---------- */
    window.PinoTranslate = { load, translate, getCurrent: () => current };
})();
