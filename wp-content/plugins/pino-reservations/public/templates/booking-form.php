<?php
/**
 * Booking form template – rendered by [pino_booking] shortcode.
 * Styled to match the existing pinovilla theme (pino-reservation.css / availability.css).
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- ═══════════ AVAILABILITY RESULTS ═══════════ -->
<div id="pino-results" style="display:none;">
    <div class="sec-title text-center" style="margin-bottom:32px;">
        <span class="sub-title" data-i18n="availability.results.subtitle">Налични опции</span>
        <h2 data-i18n="availability.results.title">Изберете комбинация от стаи</h2>
        <p style="color:#828282; margin-top:10px;" id="pino-results-summary"></p>
    </div>

    <div id="pino-combos-list" class="pino-combos-list"></div>

    <div id="pino-no-combos" class="pino-no-combos" style="display:none;">
        <div style="text-align:center; padding:48px 0;">
            <i class="fal fa-calendar-xmark" style="font-size:48px; color:#AE7D54; margin-bottom:16px; display:block;"></i>
            <h3 style="color:#13110E;" data-i18n="availability.noresults.title">Няма налични стаи</h3>
            <p style="color:#828282; margin-top:8px;" data-i18n="availability.noresults.desc">
                За съжаление, за избраните дати и брой гости няма свободна комбинация. Моля, опитайте с различни дати.
            </p>
        </div>
    </div>
</div>

<!-- ═══════════ BOOKING FORM (Step 2) ═══════════ -->
<div id="pino-book-section" style="display:none;">
    <div class="sec-title text-center" style="margin-bottom:32px;">
        <span class="sub-title" data-i18n="booking.form.subtitle">Резервация</span>
        <h2 data-i18n="booking.form.title">Попълнете вашите данни</h2>
    </div>

    <div class="reservation-form" style="flex-direction:column; align-items:stretch;">
        <!-- Selected combo summary -->
        <div id="pino-selected-summary" class="pino-selected-summary"></div>

        <!-- Meals -->
        <div class="meals-container" style="width:100%;">
            <p class="checkboxes-title" data-i18n="booking.meals.title">Хранене (на човек, на нощувка)</p>
            <div class="checkbox-container" style="width:100%; margin-top:16px;" id="pino-meals-list">
                <!-- Filled by JS -->
            </div>
        </div>

        <!-- Guest form -->
        <div class="reserve-form-container" style="margin-top:24px; width:100%;">
            <div>
                <label class="input-date-label" data-i18n="booking.field.fname">Име</label>
                <input type="text" class="reservation-input" id="pino-fname" required style="width:100%;">
            </div>
            <div>
                <label class="input-date-label" data-i18n="booking.field.lname">Фамилия</label>
                <input type="text" class="reservation-input" id="pino-lname" required style="width:100%;">
            </div>
            <div>
                <label class="input-date-label" data-i18n="booking.field.email">Имейл</label>
                <input type="email" class="reservation-input" id="pino-email" required style="width:100%;">
            </div>
            <div>
                <label class="input-date-label" data-i18n="booking.field.phone">Телефон</label>
                <input type="tel" class="reservation-input" id="pino-phone" required style="width:100%;">
            </div>
        </div>

        <!-- Price summary -->
        <div id="pino-price-summary" class="pino-price-summary" style="margin-top:24px;"></div>

        <!-- Submit -->
        <div style="text-align:center; margin-top:32px;">
            <button type="button" id="pino-submit-btn" class="theme-btn btn-style-one" style="min-width:240px;">
                <span class="btn-title" data-i18n="booking.submit">Изпрати резервация</span>
            </button>
        </div>

        <div id="pino-booking-error" style="display:none; color:#c0392b; text-align:center; margin-top:12px;"></div>
    </div>
</div>

<!-- ═══════════ SUCCESS (Step 3) ═══════════ -->
<div id="pino-success" style="display:none;">
    <div style="text-align:center; padding:64px 0;">
        <i class="fal fa-circle-check" style="font-size:64px; color:#27ae60; margin-bottom:20px; display:block;"></i>
        <h2 style="color:#13110E;" data-i18n="booking.success.title">Благодарим ви!</h2>
        <p style="color:#828282; margin-top:12px; font-size:17px;" data-i18n="booking.success.desc">
            Вашата резервация е получена. Ще се свържем с вас за потвърждение в най-кратък срок.
        </p>
        <p style="margin-top:12px; font-size:15px; color:#AE7D54;" id="pino-success-id"></p>
        <a href="<?php echo home_url('/'); ?>" class="theme-btn btn-style-one" style="margin-top:24px;">
            <span class="btn-title" data-i18n="booking.success.home">Начало</span>
        </a>
    </div>
</div>
