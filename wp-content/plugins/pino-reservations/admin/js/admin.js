/**
 * Pino Reservations — Admin UI behaviours
 *  • language tabs
 *  • WP media-library image picker
 *  • delete-confirm on forms marked .pino-confirm-delete
 */
(function ($) {
    'use strict';

    /* ── Language tabs ──────────────────────────────────────── */
    $(document).on('click', '.pino-tab-btn', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrap = $btn.closest('.pino-tabs');
        var pane = $btn.data('tab');

        $wrap.find('.pino-tab-btn').removeClass('is-active');
        $btn.addClass('is-active');

        $wrap.find('.pino-tab-pane').removeClass('is-active');
        $wrap.find('.pino-tab-pane[data-pane="' + pane + '"]').addClass('is-active');
    });

    /* ── Media-library image picker ─────────────────────────── */
    var mediaFrames = {};

    $(document).on('click', '.pino-image-select', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $wrap = $btn.closest('[data-pino-image]');
        var frameKey = $wrap.find('.pino-image-input').attr('name') || Math.random();

        if (!mediaFrames[frameKey]) {
            mediaFrames[frameKey] = wp.media({
                title: (window.pinoAdmin && pinoAdmin.mediaTitle) || 'Select image',
                button: { text: (window.pinoAdmin && pinoAdmin.mediaButton) || 'Use' },
                library: { type: 'image' },
                multiple: false,
            });

            mediaFrames[frameKey].on('select', function () {
                var attach = mediaFrames[frameKey].state().get('selection').first().toJSON();
                var url = attach.sizes && attach.sizes.large ? attach.sizes.large.url : attach.url;

                $wrap.find('.pino-image-input').val(url);
                $wrap.find('.pino-image-preview')
                     .removeClass('is-empty')
                     .html('<img src="' + url + '" alt="">');
                $wrap.find('.pino-image-clear').show();
                $btn.text('Смени');
            });
        }
        mediaFrames[frameKey].open();
    });

    $(document).on('click', '.pino-image-clear', function (e) {
        e.preventDefault();
        var $wrap = $(this).closest('[data-pino-image]');
        $wrap.find('.pino-image-input').val('');
        $wrap.find('.pino-image-preview')
             .addClass('is-empty')
             .html('<span class="dashicons dashicons-format-image"></span>');
        $wrap.find('.pino-image-select').text('Избери от медиите');
        $(this).hide();
    });

    /* ── Delete confirmation ────────────────────────────────── */
    $(document).on('submit', '.pino-confirm-delete', function (e) {
        var msg = (window.pinoAdmin && pinoAdmin.confirmDel) || 'Delete this item?';
        if (!window.confirm(msg)) {
            e.preventDefault();
            return false;
        }
    });

})(jQuery);
