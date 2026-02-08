/**
 * Pino Reservations – front-end booking logic.
 *
 * Handles:
 *   1. "Провери наличност" form  → AJAX pino_check_availability
 *   2. Combo selection           → show booking form
 *   3. "Изпрати резервация"      → AJAX pino_submit_booking
 */
(function ($) {
    'use strict';

    /* ── State ── */
    var combos = [];
    var meals = [];
    var nights = 0;
    var selectedIdx = -1;
    var startDate = '';
    var endDate = '';
    var guests = 0;

    /* ── DOM ready ── */
    $(function () {
        bindCheckForm();
        bindSubmit();
        bindRoomTableButtons();
    });

    /* ═══════════════════════════════════════════
       1. Check-availability form
       ═══════════════════════════════════════════ */
    function bindCheckForm() {
        var $form = $('form[action*="RoomAvalability"]');
        if (!$form.length) $form = $('form[action*="roomavalability"]');

        $form.on('submit', function (e) {
            e.preventDefault();

            startDate = $form.find('[name="StartDate"]').val();
            endDate = $form.find('[name="EndDate"]').val();
            guests = parseInt($form.find('[name="Guests"]').val(), 10) || 1;

            if (!startDate || !endDate || startDate >= endDate) {
                alert('Моля, посочете валидни дати.');
                return;
            }
            if (guests < 1) { guests = 1; $form.find('[name="Guests"]').val(1); }

            checkAvailability();
        });
    }

    function checkAvailability() {
        var $results = $('#pino-results');
        var $combos = $('#pino-combos-list');
        var $none = $('#pino-no-combos');
        var $bookSec = $('#pino-book-section');
        var $success = $('#pino-success');

        $bookSec.hide();
        $success.hide();
        $results.show();
        $combos.html('<div style="text-align:center;padding:32px;"><i class="fal fa-spinner-third fa-spin" style="font-size:32px;color:#AE7D54;"></i></div>');
        $none.hide();

        // Smooth scroll
        $('html,body').animate({ scrollTop: $results.offset().top - 100 }, 500);

        $.post(PinoRes.ajaxurl, {
            action: 'pino_check_availability',
            nonce: PinoRes.nonce,
            start_date: startDate,
            end_date: endDate,
            guests: guests
        }, function (res) {
            if (!res.success) {
                $combos.html('<p style="color:#c0392b;text-align:center;">' + (res.data && res.data.message ? res.data.message : 'Грешка.') + '</p>');
                return;
            }

            combos = res.data.combos;
            meals = res.data.meals;
            nights = res.data.nights;

            $('#pino-results-summary').text(
                startDate + ' → ' + endDate + '  ·  ' + nights + ' ' + (nights === 1 ? 'нощувка' : 'нощувки') + '  ·  ' + guests + ' ' + (guests === 1 ? 'гост' : 'гости')
            );

            if (combos.length === 0) {
                $combos.empty();
                $none.show();
                return;
            }

            $none.hide();
            renderCombos($combos);
        }).fail(function () {
            $combos.html('<p style="color:#c0392b;text-align:center;">Възникна грешка. Моля, опитайте отново.</p>');
        });
    }

    /* ═══════════════════════════════════════════
       2. Render combo cards
       ═══════════════════════════════════════════ */
    function renderCombos($container) {
        var html = '';
        $.each(combos, function (i, combo) {
            var typesStr = [];
            $.each(combo.types, function (_, t) {
                var label = t.name_bg;
                if (t.count > 1) label += ' × ' + t.count;
                typesStr.push(label);
            });

            html += '<div class="pino-combo-card" data-idx="' + combo.index + '">';
            html += '  <div class="pino-combo-card__header">';
            html += '    <span class="pino-combo-card__label">' + typesStr.join(' + ') + '</span>';
            html += '    <span class="pino-combo-card__cap"><i class="fal fa-users me-1"></i> ' + combo.total_capacity + ' ' + '</span>';
            html += '  </div>';
            html += '  <div class="pino-combo-card__body">';
            $.each(combo.types, function (_, t) {
                html += '    <div class="pino-combo-card__type">';
                html += '      <strong>' + t.name_bg + '</strong>';
                if (t.count > 1) html += ' <span class="pino-combo-card__badge">× ' + t.count + '</span>';
                html += '      <span class="pino-combo-card__type-price">' + t.price.toFixed(0) + ' лв./нощ</span>';
                html += '    </div>';
            });
            html += '  </div>';
            html += '  <div class="pino-combo-card__footer">';
            html += '    <span class="pino-combo-card__total">' + combo.total_price.toFixed(2) + ' лв. <small>/ ' + nights + (nights === 1 ? ' нощ' : ' нощи') + '</small></span>';
            html += '    <button type="button" class="theme-btn btn-style-one pino-select-combo" data-idx="' + combo.index + '">';
            html += '      <span class="btn-title" data-i18n="availability.select">Избери</span>';
            html += '    </button>';
            html += '  </div>';
            html += '</div>';
        });

        $container.html(html);

        // Bind select
        $container.off('click', '.pino-select-combo').on('click', '.pino-select-combo', function () {
            selectedIdx = parseInt($(this).data('idx'), 10);
            showBookingForm();
        });
    }

    /* ═══════════════════════════════════════════
       3. Booking form (Step 2)
       ═══════════════════════════════════════════ */
    function showBookingForm() {
        var combo = combos[selectedIdx];
        if (!combo) return;

        // Selected summary
        var typesStr = [];
        $.each(combo.types, function (_, t) {
            var s = t.name_bg;
            if (t.count > 1) s += ' × ' + t.count;
            typesStr.push(s);
        });

        $('#pino-selected-summary').html(
            '<div class="pino-selected-info">' +
            '  <p><strong>' + typesStr.join(' + ') + '</strong></p>' +
            '  <p>' + startDate + ' → ' + endDate + '  ·  ' + nights + (nights === 1 ? ' нощувка' : ' нощувки') + '  ·  ' + guests + (guests === 1 ? ' гост' : ' гости') + '</p>' +
            '  <p class="pino-room-price">Стаи: <strong>' + combo.total_price.toFixed(2) + ' лв.</strong></p>' +
            '</div>'
        );

        // Meals
        var $mealsList = $('#pino-meals-list').empty();
        if (meals && meals.length) {
            $.each(meals, function (_, m) {
                $mealsList.append(
                    '<label class="custom-radio">' +
                    '  <input type="checkbox" name="pino_meal" value="' + m.id + '">' +
                    '  <span class="radio-box"></span>' +
                    '  <span>' + m.name_bg + '</span>' +
                    '  <span class="price" style="margin-left:8px;">' + parseFloat(m.price).toFixed(0) + ' лв./чов./нощ</span>' +
                    '</label>'
                );
            });
        } else {
            $mealsList.closest('.meals-container').hide();
        }

        // Bind meal checkbox changes to update price
        $mealsList.off('change').on('change', 'input', updatePriceSummary);

        updatePriceSummary();

        var $sec = $('#pino-book-section');
        $sec.show();
        $('html,body').animate({ scrollTop: $sec.offset().top - 100 }, 500);
    }

    function updatePriceSummary() {
        if (selectedIdx < 0 || !combos[selectedIdx]) return;

        var combo = combos[selectedIdx];
        var roomTotal = combo.total_price;
        var mealTotal = 0;

        $('#pino-meals-list input:checked').each(function () {
            var mid = parseInt($(this).val(), 10);
            $.each(meals, function (_, m) {
                if (parseInt(m.id) === mid) {
                    mealTotal += parseFloat(m.price) * nights * guests;
                }
            });
        });

        var grand = roomTotal + mealTotal;

        $('#pino-price-summary').html(
            '<div class="pino-price-box">' +
            '  <div class="pino-price-row"><span>Стаи (' + nights + ' нощ' + (nights === 1 ? '' : 'и') + ')</span><span>' + roomTotal.toFixed(2) + ' лв.</span></div>' +
            (mealTotal > 0 ? '  <div class="pino-price-row"><span>Хранене</span><span>' + mealTotal.toFixed(2) + ' лв.</span></div>' : '') +
            '  <div class="pino-price-row pino-price-total"><span>Обща цена</span><span>' + grand.toFixed(2) + ' лв.</span></div>' +
            '</div>'
        );
    }

    /* ═══════════════════════════════════════════
       4. Submit booking
       ═══════════════════════════════════════════ */
    function bindSubmit() {
        $(document).on('click', '#pino-submit-btn', function () {
            var $btn = $(this);
            var fname = $.trim($('#pino-fname').val());
            var lname = $.trim($('#pino-lname').val());
            var email = $.trim($('#pino-email').val());
            var phone = $.trim($('#pino-phone').val());
            var $error = $('#pino-booking-error');

            $error.hide();

            if (!fname || !lname || !email || !phone) {
                $error.text('Моля попълнете всички полета.').show();
                return;
            }

            var mealIds = [];
            $('#pino-meals-list input:checked').each(function () {
                mealIds.push(parseInt($(this).val(), 10));
            });

            $btn.prop('disabled', true).find('.btn-title').text('Изпращане…');

            $.post(PinoRes.ajaxurl, {
                action: 'pino_submit_booking',
                nonce: PinoRes.nonce,
                first_name: fname,
                last_name: lname,
                email: email,
                phone: phone,
                start_date: startDate,
                end_date: endDate,
                guests: guests,
                combo_index: selectedIdx,
                'meal_ids[]': mealIds
            }, function (res) {
                $btn.prop('disabled', false).find('.btn-title').text('Изпрати резервация');

                if (!res.success) {
                    $error.text(res.data && res.data.message ? res.data.message : 'Грешка.').show();
                    return;
                }

                // Show success
                $('#pino-results, #pino-book-section').hide();
                var $suc = $('#pino-success');
                $suc.show();
                $('#pino-success-id').text('Резервация #' + res.data.reservation_id + '  ·  ' + res.data.total_price + ' лв.');
                $('html,body').animate({ scrollTop: $suc.offset().top - 100 }, 500);
            }).fail(function () {
                $btn.prop('disabled', false).find('.btn-title').text('Изпрати резервация');
                $error.text('Възникна грешка. Моля, опитайте отново.').show();
            });
        });
    }

    /* ═══════════════════════════════════════════
       5. Room table "Резервирай" buttons
          → pre-fill dates from the form and trigger check
       ═══════════════════════════════════════════ */
    function bindRoomTableButtons() {
        $(document).on('click', '.availability-table__book-btn', function (e) {
            e.preventDefault();

            var $form = $('form[action*="RoomAvalability"], form[action*="roomavalability"]');
            startDate = $form.find('[name="StartDate"]').val();
            endDate = $form.find('[name="EndDate"]').val();
            guests = parseInt($form.find('[name="Guests"]').val(), 10) || 2;

            if (!startDate || !endDate || startDate >= endDate) {
                // Scroll to form
                $('html,body').animate({ scrollTop: $form.offset().top - 100 }, 500);
                $form.find('[name="Guests"]').focus();
                return;
            }

            checkAvailability();
        });
    }

})(jQuery);
