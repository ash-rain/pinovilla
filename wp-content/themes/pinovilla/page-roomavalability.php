<?php

/**
 * Template Name: Room Availability
 * Slug: roomavalability
 */
get_header();
?>

<main role="main">

    <!-- ░░░ PAGE BANNER ░░░ -->
    <section class="page-title" style="padding: 160px 0 60px; background: #13110E;">
        <div class="auto-container">
            <div class="title-outer text-center">
                <h1 class="title" style="color:#fff;" data-i18n="availability.heading">Наличност и резервация</h1>
                <ul class="page-breadcrumb" style="margin-top:12px;">
                    <li><a href="<?php echo home_url('/'); ?>" data-i18n="navbar.home" style="color:#A97B56;">Начало</a></li>
                    <li style="color:#ccc;" data-i18n="availability.breadcrumb">Наличност</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ░░░ CHECK-IN / CHECK-OUT FORM ░░░ -->
    <div class="checkout-form-section-two" style="position:relative; z-index:2; margin-top:-40px;">
        <div class="container">
            <form method="get" action="<?php echo home_url('/RoomAvalability'); ?>">
                <div class="checkout-form">
                    <div class="checkout-field">
                        <h4 data-i18n="form.checkin"><label for="StartDate">Настаняване</label></h4>
                        <div class="chk-field">
                            <input class="date-pick" type="date" name="StartDate"
                                value="<?php echo esc_attr(isset($_GET['StartDate']) ? $_GET['StartDate'] : date('Y-m-d')); ?>"
                                min="<?php echo date('Y-m-d'); ?>" />
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </div>

                    <div class="checkout-field">
                        <h4 data-i18n="form.checkout"><label for="EndDate">Освобождаване</label></h4>
                        <div class="chk-field">
                            <input class="date-pick" type="date" name="EndDate"
                                value="<?php echo esc_attr(isset($_GET['EndDate']) ? $_GET['EndDate'] : date('Y-m-d', strtotime('+1 day'))); ?>"
                                min="<?php echo date('Y-m-d'); ?>" />
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </div>

                    <div class="checkout-field select-field br-0">
                        <h4 data-i18n="form.guests">Гости</h4>
                        <div class="chk-field">
                            <input type="number" name="Guests" placeholder="0"
                                value="<?php echo esc_attr(isset($_GET['Guests']) ? $_GET['Guests'] : ''); ?>" />
                        </div>
                    </div>

                    <button type="submit" class="theme-btn btn-style-one">
                        <span class="btn-title" data-i18n="form.check">Провери <br />наличност</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ░░░ ROOM AVAILABILITY TABLE ░░░ -->
    <section class="room-service-section pt-120 pb-60">
        <div class="auto-container">
            <div class="sec-title text-center">
                <span class="sub-title" data-i18n="availability.subtitle">Нашите стаи и апартаменти</span>
                <h2 data-i18n="availability.title">Изберете и резервирайте</h2>
            </div>

            <div class="availability-table-wrapper">
                <div class="availability-table-scroll">
                    <table class="availability-table">
                        <thead>
                            <tr>
                                <th data-i18n="availability.col.room">Стая / Апартамент</th>
                                <th data-i18n="availability.col.features">Удобства</th>
                                <th data-i18n="availability.col.guests">Гости</th>
                                <th data-i18n="availability.col.price">Цена / нощувка</th>
                                <th data-i18n="availability.col.action">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- ▷ ECONOMIC ROOM -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/economic-double.jpeg"
                                            alt="Economic Room" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="room.economic.name">Икономична стая</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-2</td>
                                <td data-label="Цена" class="availability-table__price">194 лв. <span>/ 99 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="economic">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ DOUBLE ROOM -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/double.jpeg"
                                            alt="Double Room" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="room.double.name">Двойна стая</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-2</td>
                                <td data-label="Цена" class="availability-table__price">207 лв. <span>/ 106 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="double">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ SUPERIOR ROOM -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/superior-room.png"
                                            alt="Superior Room" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="room.superior.name">Супериорна стая</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                        <li><i class="fal fa-bath me-1"></i> <span data-i18n="availability.bathtub">Вана</span></li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-2</td>
                                <td data-label="Цена" class="availability-table__price">219 лв. <span>/ 112 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="superior">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ BOUTIQUE ROOM -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/deluxe-room.png"
                                            alt="Boutique Room" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="room.boutique.name">Бутикова стая</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                        <li><i class="fal fa-bath me-1"></i> <span data-i18n="availability.bathtub">Вана</span></li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-2</td>
                                <td data-label="Цена" class="availability-table__price">238 лв. <span>/ 122 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="boutique">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ APARTMENT 1 -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/apartment.jpeg"
                                            alt="Apartment 1" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="navbar.room.apartment1">Апартамент 1</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                        <li><i class="fal fa-utensils me-1"></i> <span data-i18n="availability.kitchen">Кухня</span></li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-4</td>
                                <td data-label="Цена" class="availability-table__price">257 лв. <span>/ 131 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="apartment1">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ APARTMENT 2 -->
                            <tr>
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/apartment.jpeg"
                                            alt="Apartment 2" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="navbar.room.apartment2">Апартамент 2</strong>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span></li>
                                        <li><i class="fal fa-tv me-1"></i> TV</li>
                                        <li><i class="fal fa-utensils me-1"></i> <span data-i18n="availability.kitchen">Кухня</span></li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-4</td>
                                <td data-label="Цена" class="availability-table__price">257 лв. <span>/ 131 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="apartment2">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>

                            <!-- ▷ VILLA / PINO CASA -->
                            <tr style="background:#f8f5f0;">
                                <td data-label="Стая">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa.jpg"
                                            alt="Villa Pino Casa" style="width:80px;height:56px;object-fit:cover;border-radius:6px;">
                                        <strong data-i18n="room.villa.name">Самостоятелна къща</strong>
                                        <span style="background:#AE7D54;color:#fff;font-size:11px;padding:2px 8px;border-radius:4px;text-transform:uppercase;" data-i18n="availability.premium">Pino Casa</span>
                                    </div>
                                </td>
                                <td data-label="Удобства">
                                    <ul class="availability-table__list">
                                        <li><i class="fal fa-wifi me-1"></i> WiFi</li>
                                        <li><i class="fal fa-snowflake me-1"></i> <span data-i18n="availability.ac">Климатик</span> x4</li>
                                        <li><i class="fal fa-tv me-1"></i> TV x5</li>
                                        <li><i class="fal fa-utensils me-1"></i> <span data-i18n="availability.kitchen">Кухня</span></li>
                                        <li><i class="fal fa-hot-tub me-1"></i> <span data-i18n="availability.jacuzzi">Джакузи</span></li>
                                        <li><i class="fal fa-swimming-pool me-1"></i> <span data-i18n="availability.pool">Басейн</span></li>
                                    </ul>
                                </td>
                                <td data-label="Гости"><i class="fal fa-circle-user me-1"></i> 1-6*</td>
                                <td data-label="Цена" class="availability-table__price">700 лв. <span>/ 358 €</span></td>
                                <td data-label="" class="availability-table__action">
                                    <a href="#pino-booking" class="theme-btn btn-style-one availability-table__book-btn pino-book-btn" data-room="villa">
                                        <span class="btn-title" data-i18n="availability.book">Резервирай</span>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- ░░░ PINO BOOKING SECTION ░░░ -->
    <section id="pino-booking" class="pt-0 pb-120">
        <div class="auto-container">
            <div class="sec-title text-center booking">
                <span class="sub-title" data-i18n="availability.booking.subtitle">Онлайн резервация</span>
                <h2 data-i18n="availability.booking.title">Резервирайте вашия престой</h2>
                <p style="color:#828282; margin-top:12px; font-size:16px;" data-i18n="availability.booking.desc">
                    Изберете дати и брой гости, за да видите наличните стаи и да направите резервация.
                </p>
            </div>

            <div style="max-width:960px; margin:0 auto;">
                <?php echo do_shortcode('[pino_booking]'); ?>
            </div>
        </div>
    </section>

</main>

<style>
    /* Room Availability page overrides */
    .page-breadcrumb {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .page-breadcrumb li::before {
        content: "/ ";
        margin-right: 8px;
        color: #666;
    }

    .page-breadcrumb li:first-child::before {
        content: "";
        margin-right: 0;
    }

    .page-breadcrumb li a {
        text-decoration: none;
    }

    .page-breadcrumb li a:hover {
        color: #fff !important;
    }

    .pino-book-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(169, 123, 86, 0.3);
    }

    .pino-booking-wrapper {
        transition: all 0.3s ease;
    }

    /* Smooth scroll offset for amelia-booking anchor */
    #pino-booking {
        scroll-margin-top: 100px;
    }

    .pb-120 {
        padding-bottom: 120px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll to Amelia booking when any "Резервирай" button is clicked
        document.querySelectorAll('.pino-book-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.getElementById('pino-booking');
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Flatpickr sync for the check-in/check-out on this page
        var startInput = document.querySelector('input[name="StartDate"]');
        var endInput = document.querySelector('input[name="EndDate"]');
        if (startInput && endInput) {
            startInput.addEventListener('change', function() {
                if (!endInput.value || endInput.value <= startInput.value) {
                    var d = new Date(startInput.value);
                    d.setDate(d.getDate() + 1);
                    var mm = String(d.getMonth() + 1).padStart(2, '0');
                    var dd = String(d.getDate()).padStart(2, '0');
                    endInput.value = d.getFullYear() + '-' + mm + '-' + dd;
                }
                endInput.min = startInput.value;
            });
        }
    });
</script>

<?php get_footer(); ?>