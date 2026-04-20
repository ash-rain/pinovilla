<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/LOGO/white.jpg" type="image/x-icon" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php wp_head(); ?>
</head>

<body <?php body_class( !is_front_page() ? 'dark-layout' : '' ); ?>>
<div b-21sjaj47gr class="page-wrapper">

    <!-- Navbar -->

<header class="main-header <?php echo is_front_page() ? 'header-style-five' : 'header-style-four style-dark style-home5'; ?>">
        <div class="header-top">
            <div class="inner-box">
                <div class="top-left">
                    <ul class="social-icon-one">
                        <?php $fb = pino_setting('social.facebook'); if ($fb) : ?>
                            <li><a target="_blank" href="<?php echo esc_url($fb); ?>"><i class="fab fa-facebook-f"></i></a></li>
                        <?php endif; ?>
                        <?php $ig = pino_setting('social.instagram'); if ($ig) : ?>
                            <li><a target="_blank" href="<?php echo esc_url($ig); ?>"><i class="fa-brands fa-instagram"></i></a></li>
                        <?php endif; ?>
                        <?php $yt = pino_setting('social.youtube'); if ($yt) : ?>
                            <li><a target="_blank" href="<?php echo esc_url($yt); ?>"><i class="fa-brands fa-youtube"></i></a></li>
                        <?php endif; ?>
                        <?php $tt = pino_setting('social.tiktok'); if ($tt) : ?>
                            <li><a target="_blank" href="<?php echo esc_url($tt); ?>"><i class="fa-brands fa-tiktok"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="top-right">
                    <span>
                        <i class="icon fa-solid fa-envelope"></i>
                        <a class="navbar-link" href="mailto:<?php echo esc_attr(pino_setting('contact.email', 'info@pinovilla.com')); ?>"><?php echo esc_html(pino_setting('contact.email', 'info@pinovilla.com')); ?></a>
                    </span>
                    <span>
                        <i class="icon fa-sharp fa-solid fa-location-dot"></i>
                        <a class="navbar-link" target="_blank"
                           href="<?php echo esc_url(pino_setting('contact.map_url', 'https://www.google.com/maps/place/Pino+-+Villa,+Casa,+Cucina+e+Terrazza/@43.3696602,28.0640392,17z')); ?>"
                           data-i18n="navbar.location"><?php echo esc_html(pino_setting('contact.address_bg', 'Албена, България')); ?></a>
                    </span>
                    <div class="lang-switch-container" style="display:flex; align-items: center; justify-content: center; margin-left: 30px">
                        <?php echo pinovilla_lang_switcher(); ?>
                    </div>
                </div>
            </div>
        </div>

    <div class="header-lower">
        <div class="main-box">
            <!-- Logo -->
            <div class="logo-box">
                <div class="logo">
                    <a href="<?php echo home_url('/'); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/LOGO/logo-text-only-gold.png" alt="PINO" title="PINO">
                    </a>
                </div>

            </div>



            <!-- Main navigation -->
            <div class="nav-outer">
                <nav class="nav main-menu">
                    <ul class="navigation">
                        <li><a data-i18n="navbar.home" href="<?php echo home_url('/'); ?>">Начало</a></li>

                        <li class="dropdown">
                            <a data-i18n="navbar.rooms" href="<?php echo home_url('/rooms'); ?>">Стаи</a>
                            <ul>
                                <li><a href="<?php echo home_url('/rooms#ECONOMIC'); ?>" data-i18n="navbar.room.economic">Икономична</a></li>
                                <li><a href="<?php echo home_url('/rooms#DOUBLE'); ?>" data-i18n="navbar.room.double">Двойна</a></li>
                                <li><a href="<?php echo home_url('/rooms#SUPERIOR'); ?>" data-i18n="navbar.room.superior">Супериорна</a></li>
                                <li><a href="<?php echo home_url('/rooms#BOUTIQUE'); ?>" data-i18n="navbar.room.boutique">Бутикова</a></li>
                                <li><a href="<?php echo home_url('/rooms#APARTMENT1'); ?>" data-i18n="navbar.room.apartment1">Апартамент 1</a></li>
                                <li><a href="<?php echo home_url('/rooms#APARTMENT2'); ?>" data-i18n="navbar.room.apartment2">Апартамент 2</a></li>
                            </ul>
                        </li>

                        <li><a data-i18n="navbar.villa" href="<?php echo home_url('/villa'); ?>">Къща</a></li>
                        <li><a data-i18n="navbar.restaurant" href="<?php echo home_url('/restaurant'); ?>">Ресторант</a></li>
                        <li><a data-i18n="navbar.halls" href="<?php echo home_url('/halls'); ?>">Зали</a></li>
                        <li><a data-i18n="navbar.about" href="<?php echo home_url('/about'); ?>">За нас</a></li>
                        <li><a data-i18n="navbar.contact" href="<?php echo home_url('/contact'); ?>">Контакти</a></li>

                    </ul>
                </nav>
            </div>

            <div class="outer-box">
                <div class="lang-switch-container">
                    <?php echo pinovilla_lang_switcher(); ?>
                </div>
                <div class="ui-btn-outer"></div>
                <div class="mobile-nav-toggler"><span class="icon lnr-icon-bars"></span></div>
            </div>

        </div>

        <!-- Mobile menu -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <nav class="menu-box">
                <div class="upper-box">
                    <div class="nav-logo">
                        <a href="<?php echo home_url('/'); ?>">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/LOGO/logo-text-only-gold.png" alt="PINO">
                        </a>
                    </div>
                    <div class="close-btn"><i class="icon fa fa-times"></i></div>
                </div>

                <ul class="navigation clearfix"></ul>

                <div class="lang-switch-container" style="margin-top: 36px; margin-bottom: 12px;">
                    <?php echo pinovilla_lang_switcher(); ?>
                </div>

                <ul class="contact-list-one">

                    <li>
                        <div class="contact-info-box">
                            <i class="icon lnr-icon-phone-handset"></i>
                            <span class="title" data-i18n="navbar.callUs">Обадете ни се</span>
                            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', pino_setting('contact.phone', '+359885185008'))); ?>"><?php echo esc_html(pino_setting('contact.phone_display', '+359 885 185 008')); ?></a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-envelope1"></span>
                            <span class="title" data-i18n="navbar.writeUs">Пишете ни</span>
                            <a href="mailto:<?php echo esc_attr(pino_setting('contact.email', 'info@pinovilla.com')); ?>"><?php echo esc_html(pino_setting('contact.email', 'info@pinovilla.com')); ?></a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-clock"></span>
                            <span class="title" data-i18n="navbar.hours">Работно време</span>
                            <span data-i18n="navbar.openHours"><?php echo esc_html(pino_setting('hours.general_bg', 'Всеки ден 10:00 - 24:00')); ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-clock"></span>
                            <span class="title" data-i18n="navbar.hours.restaurant">Работно време ресторант</span>
                            <span data-i18n="navbar.openHours.restaurant"><?php echo esc_html(pino_setting('hours.restaurant_bg', 'Всеки ден 11:30 - 22:00')); ?></span>
                        </div>
                    </li>
                </ul>

                <ul class="social-links">
                    <?php if ($fb = pino_setting('social.facebook')) : ?>
                        <li><a target="_blank" href="<?php echo esc_url($fb); ?>"><i class="fab fa-facebook-f"></i></a></li>
                    <?php endif; ?>
                    <?php if ($ig = pino_setting('social.instagram')) : ?>
                        <li><a target="_blank" href="<?php echo esc_url($ig); ?>"><i class="fa-brands fa-instagram"></i></a></li>
                    <?php endif; ?>
                    <?php if ($yt = pino_setting('social.youtube')) : ?>
                        <li><a target="_blank" href="<?php echo esc_url($yt); ?>"><i class="fa-brands fa-youtube"></i></a></li>
                    <?php endif; ?>
                    <?php if ($tt = pino_setting('social.tiktok')) : ?>
                        <li><a target="_blank" href="<?php echo esc_url($tt); ?>"><i class="fa-brands fa-tiktok"></i></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Sticky header (logo only; nav is cloned by JS if desired) -->
        <div class="sticky-header">
            <div class="auto-container">
                <div class="inner-container">
                    <div class="logo">
                        <a href="<?php echo home_url('/'); ?>">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/LOGO/logo-text-only-gold.png" alt="PINO">
                        </a>
                    </div>
                    <div class="nav-outer">
                        <nav class="main-menu">
                            <div class="navbar-collapse show collapse clearfix">
                                <ul class="navigation clearfix"></ul>
                            </div>
                        </nav>
                        <div class="mobile-nav-toggler"><span class="icon lnr-icon-bars"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
