/* /wwwroot/assets/Website/js/translator.js
   Lightweight runtime i18n + DB text switching + live DOM observer
   ------------------------------------------------------------------ */
(() => {
    /* ----------  constants ---------- */
    const STORAGE_KEY  = "pino.lang";
    const DEFAULT_LANG = "bg";
    let   current      = localStorage.getItem(STORAGE_KEY) || DEFAULT_LANG;
    let   dict         = {};

    /* ----------  helpers ---------- */
    const qsa = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
    const getTxt = (key) => (key && dict[key]) || null;

    /* ----------  JSON loader ---------- */
    async function load(lang) {
        if (lang === current && Object.keys(dict).length) return;
        try {
            const res = await fetch(`/assets/Website/i18n/${lang}.json`, { cache: "no-store" });
            if (!res.ok) throw new Error(res.statusText);
            dict    = await res.json();
            current = lang;
            localStorage.setItem(STORAGE_KEY, lang);
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
                // If someone accidentally used data-i18n on inputs, still set placeholder
                el.placeholder = txt;
            } else {
                el.innerHTML = txt;
            }
        });
    }

    /* ----------  NEW: translate placeholders (data-i18n-placeholder) ---------- */
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
                // Don’t overwrite user-typed value; set placeholder
                if (!el.value) el.value = ""; // keep empty if it was empty
                el.placeholder = txt;
            } else {
                el.textContent = txt;
            }
        });
    }

    /* ----------  language switch buttons ---------- */
    function initSwitcher() {
        qsa(".lang-switch").forEach(btn =>
            btn.addEventListener("click", e => {
                e.preventDefault();
                load(btn.dataset.lang);
            })
        );
    }

    /* ----------  navbar badge(s) ---------- */
    function updateLangBadges() {
        qsa(".selected-lang").forEach(badge => (badge.textContent = current.toUpperCase()));
    }

    /* ----------  observe late DOM inserts ---------- */
    function initMutationObserver() {
        const observer = new MutationObserver(records => {
            for (const rec of records) {
                rec.addedNodes.forEach(node => {
                    if (node.nodeType === 1) translate(node); // ELEMENT_NODE
                });
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    /* ----------  orchestrator ---------- */
    function translate(root = document) {
        translateStatic(root);
        translatePlaceholders(root); // <- makes your inputs/textarea placeholders translate
        translateDbText(root);
    }

    /* ----------  boot ---------- */
    document.addEventListener("DOMContentLoaded", () => {
        initSwitcher();
        initMutationObserver();
        updateLangBadges();

        // If saved language isn't BG, load its dictionary (will call translate on success)
        if (current !== DEFAULT_LANG) load(current);
        else translate(); // ensure BG also runs once
    });

    /* ----------  expose ---------- */
    window.PinoTranslate = { load, translate };
})();
