    <footer class="main-footer footer-style-one">
    <!-- ░░░ WIDGETS ░░░ -->
    <div class="widgets-section">
        <div class="auto-container">
            <div class="row">

                <!-- ▸ Column 1 – Logo & newsletter -->
                <div class="footer-column col-lg-4 col-sm-6">
                    <div class="footer-widget about-widget">
                        <div class="widget-content">
                            <div class="logo footer-logo">
                                <a href="<?php echo home_url('/'); ?>">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/LOGO/logo-text-only-gold.png" alt="Pino Villa Logo">
                                </a>
                            </div>

                            <div class="text mb-0" data-i18n="footer.noticeKids">
                                В Pino Villa, Casa, Cucina е Terrazza не се допускат деца под 10 години!
                            </div>

                            <div class="text mb-0" style="margin-top:32px" data-i18n="footer.newsletterPrompt">
                                Абонирайте се за нашия newsletter, за да получавате всички наши оферти и промоции.
                            </div>
                        </div>
                    </div>

                    <div class="footer-widget news-widget">
                        <div class="subscribe-form-three">
                            <form method="post" action="#">
                                <div class="form-group">
                                    <input type="email" name="email" class="email"
                                           placeholder="Имейл адрес"
                                           data-i18n="footer.emailPlaceholder" required>
                                    <button type="submit" class="theme-btn btn-style-one">
                                        <span class="btn-title"><i class="fa fa-paper-plane"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="footer-widget widget-social">
                        <h4 class="widget-title" data-i18n="footer.follow">Follow Us</h4>
                        <div class="widget-content">
                            <ul class="social-icon">
                                <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61558690053409"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/pinovillaecucina/"><i class="fa-brands fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ▸ Column 2 – Site links -->
                <div class="footer-column col-lg-2 col-sm-6 mb-0 ps-xl-4">
                    <div class="footer-widget links-widget ps-xl-4">
                        <h4 class="widget-title" data-i18n="footer.widgetPino">PINO</h4>
                        <div class="widget-content">
                            <ul class="user-links">
                                <li><a data-i18n="navbar.home" href="<?php echo home_url('/'); ?>">Начало</a></li>
                                <li><a data-i18n="navbar.rooms" href="<?php echo home_url('/Rooms'); ?>">Хотел</a></li>
                                <li><a data-i18n="navbar.villa" href="<?php echo home_url('/Villa'); ?>">Къща</a></li>
                                <li><a data-i18n="navbar.restaurant" href="<?php echo home_url('/Restaurant'); ?>">Ресторант</a></li>
                                <li><a data-i18n="navbar.halls" href="<?php echo home_url('/Halls'); ?>">Конферентни зали</a></li>
                                <li><a data-i18n="navbar.about" href="<?php echo home_url('/About'); ?>">За нас</a></li>
                                <li><a data-i18n="navbar.contact" href="<?php echo home_url('/Contact'); ?>">Контакти</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ▸ Column 3 – Room links -->
                <div class="footer-column col-lg-3 col-sm-6 mb-0 ps-xl-5">
                    <div class="footer-widget links-widget ps-xl-5">
                        <h4 class="widget-title" data-i18n="footer.roomsTitle">Стаи</h4>
                        <div class="widget-content">
                            <ul class="user-links">
                                <li><a href="<?php echo home_url('/Rooms#ECONOMIC'); ?>"   data-i18n="navbar.room.economic">Икономична</a></li>
                                <li><a href="<?php echo home_url('/Rooms#DOUBLE'); ?>"     data-i18n="navbar.room.double">Двойна</a></li>
                                <li><a href="<?php echo home_url('/Rooms#SUPERIOR'); ?>"   data-i18n="navbar.room.superior">Супериорна</a></li>
                                <li><a href="<?php echo home_url('/Rooms#BOUTIQUE'); ?>"   data-i18n="navbar.room.boutique">Бутикова</a></li>
                                <li><a href="<?php echo home_url('/Rooms#APARTMENT1'); ?>" data-i18n="navbar.room.apartment1">Апартамент 1</a></li>
                                <li><a href="<?php echo home_url('/Rooms#APARTMENT2'); ?>" data-i18n="navbar.room.apartment2">Апартамент 2</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ▸ Column 4 – Contacts -->
                <div class="footer-column col-lg-3 col-sm-6 mb-0 ps-xl-5">
                    <div class="footer-widget links-widget ps-xl-5">
                        <h4 class="widget-title" data-i18n="footer.contactTitle">Контакти</h4>
                        <div class="widget-content">
                            <ul class="user-links">
                                <li><a href="tel:+359885185008">+359 88 51 85 008</a></li>
                                <li><a href="mailto:info@pinovilla.com">info@pinovilla.com</a></li>
                                <li><a target="_blank"
                                       href="https://www.google.com/maps/place/Pino+-+Villa,+Casa,+Cucina+e+Terrazza/@43.3696602,28.0640392,17z"
                                       data-i18n="navbar.location">Албена, България</a></li>
                            </ul>


                            <ul class="user-links policy">
                                <li><a href="<?php echo home_url('/Policy'); ?>"   data-i18n="footer.policy">Политика за сигурност</a></li>
                                <li><a href="<?php echo home_url('/Terms'); ?>"   data-i18n="footer.terms">Общи условия</a></li>

                            </ul>
                        </div>
                    </div>
                </div>

            </div><!-- /.row -->
        </div>
    </div>

    <!-- ░░░ BOTTOM STRIP ░░░ -->
    <div class="footer-bottom">
        <div class="auto-container">
            <div class="inner-container">
                <div class="copyright-text" data-i18n="footer.rights">
                    All rights reserved&nbsp;| Created by
                </div>
                <a href="https://codemode.bg/" target="_blank" style="display: flex; margin-left: 8px;">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/code_mode_logo.svg" alt="CodeMode" class="codemode-logo">
                </a>
            </div>
        </div>
    </div>
</footer>



<script>
    /* -----------------------------------------------------------
       ❶  Wait until the window is fully loaded so the built-in
           $(window).on('load'…) Flatpickr initialiser is done.
       ----------------------------------------------------------- */
    window.addEventListener('load', function () {

        const startInput = document.querySelector('input[name="StartDate"]');
        const endInput   = document.querySelector('input[name="EndDate"]');
        if (!startInput || !endInput) return;        // page doesn’t have both inputs

        const startFP = startInput._flatpickr;       // the *second* (live) instance
        const endFP   = endInput._flatpickr;
        if (!startFP || !endFP) return;              // Flatpickr not ready? abort

        /* Helper → tomorrow for any given date */
        function plusOne(d) {
            return new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1);
        }

        /* Sync EndDate so it’s always ≥ StartDate + 1 */
        function syncEnd(preserveCurrent) {
            const checkIn =
                startFP.selectedDates[0] || startFP.parseDate(startInput.value, "Y-m-d");
            if (!checkIn) return;

            const minOut = plusOne(checkIn);
            endFP.set("minDate", minOut);              // grey-out earlier days

            const cur = endFP.selectedDates[0];
            if (!preserveCurrent || !cur || cur < minOut) {
                endFP.setDate(minOut, true);             // true ⇒ trigger onChange
            }
        }

        /* First call (keeps server-prefilled EndDate if still valid) */
        syncEnd(true);

        /* When the user changes StartDate, force EndDate update */
        startFP.config.onChange.push(() => syncEnd(false));
    });
</script>

<script>
/* Turn off ALL reveal/parallax effects on view-ports < 992 px */
if (window.matchMedia('(max-width: 991.98px)').matches) {

  const toReset =
    '.wow, .overlay-anim, .reveal-left, .reveal-right, .tm-gsap-img-parallax';

  document.querySelectorAll(toReset).forEach(el => {
    el.style.visibility = 'visible';
    el.style.opacity    = '1';
    el.style.transform  = 'none';

    /* Strip the classes so the libraries don’t touch the element again */
    el.className = el.className
      .replace(/\b(wow|overlay-anim|reveal-(left|right)|tm-gsap-img-parallax)\b/g, '')
      .replace(/\s{2,}/g, ' ');
  });
}
</script>


        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var statusMessage = '';
                if (statusMessage) {
                    alert(statusMessage);
                }
            });
        </script>

        <script>
            (() => {
                const start = document.querySelector('input[name="StartDate"]');
                const end = document.querySelector('input[name="EndDate"]');

                if (!start || !end) return;

                // make container taps trigger the picker
                document.querySelectorAll('.checkout-form .chk-field').forEach(box => {
                    const input = box.querySelector('input[type="date"]');
                    if (!input) return;

                    box.addEventListener('click', () => {
                        // Prefer the native picker if available (Chrome/Android, some desktop)
                        if (typeof input.showPicker === 'function') {
                            try {
                                input.showPicker();
                                return;
                            } catch (_) { /* Safari/iOS will throw */
                            }
                        }
                        // Fallbacks for iOS Safari + others
                        input.focus();
                        // one extra click helps some older mobile UAs to open the wheel instantly
                        input.click();
                    });
                });

                // === keep your previous sync logic & pretty labels ===
                const monthName = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const pad = n => String(n).padStart(2, '0');
                const fmt = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

                function updatePretty(input) {
                    const container = input.closest('.checkout-form');
                    if (!container || !input.value) return;
                    const dt = new Date(input.value);
                    if (isNaN(dt)) return;
                    container.querySelector('.val-date')?.replaceChildren(document.createTextNode(dt.getDate()));
                    container.querySelector('.month')?.replaceChildren(document.createTextNode(monthName[dt.getMonth()]));
                    container.querySelector('.year')?.replaceChildren(document.createTextNode(dt.getFullYear()));
                }

                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const tomorrow = new Date(today);
                tomorrow.setDate(today.getDate() + 1);

                if (!start.value) start.value = fmt(today);
                if (!end.value) end.value = fmt(tomorrow);
                start.min = fmt(today);
                end.min = start.value;

                start.addEventListener('change', () => {
                    if (!end.value || end.value < start.value) end.value = start.value;
                    end.min = start.value;
                    updatePretty(start);
                    updatePretty(end);
                });
                end.addEventListener('change', () => updatePretty(end));

                updatePretty(start);
                updatePretty(end);
            })();
        </script>

<?php wp_footer(); ?>
</body>
</html>
