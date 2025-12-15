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
                        <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61558690053409"><i
                                    class="fab fa-facebook-f"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/pinovillaecucina/"><i
                                    class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>

                <div class="top-right">
                    <span>
                        <i class="icon fa-solid fa-envelope"></i>
                        <a class="navbar-link" href="mailto:info@pinovilla.com">info@pinovilla.com</a>
                    </span>
                    <span>
                        <i class="icon fa-sharp fa-solid fa-location-dot"></i>
                        <a class="navbar-link" target="_blank"
                           href="https://www.google.com/maps/place/Pino+-+Villa,+Casa,+Cucina+e+Terrazza/@43.3696602,28.0640392,17z"
                           data-i18n="navbar.location">Албена, България</a>
                    </span>
                    <div style="display:flex; align-items: center; justify-content: center; margin-left: 30px">
                        <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;"><a
                                href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="bg" style="font-size: 12px; color: white">BG</a>
                        </li>
                        <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center;">
                            |
                        </li>
                        <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;"><a
                                href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="en" style="font-size: 12px; color: white">EN</a>
                        </li>
                        <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center;">
                            |
                        </li>
                        <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;"><a
                                href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="ro" style="font-size: 12px; color: white">RO</a>
                        </li>
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
                            <a data-i18n="navbar.rooms" href="<?php echo home_url('/Rooms'); ?>">Стаи</a>
                            <ul>
                                <li><a href="<?php echo home_url('/Rooms#ECONOMIC'); ?>" data-i18n="navbar.room.economic">Икономична</a></li>
                                <li><a href="<?php echo home_url('/Rooms#DOUBLE'); ?>" data-i18n="navbar.room.double">Двойна</a></li>
                                <li><a href="<?php echo home_url('/Rooms#SUPERIOR'); ?>" data-i18n="navbar.room.superior">Супериорна</a></li>
                                <li><a href="<?php echo home_url('/Rooms#BOUTIQUE'); ?>" data-i18n="navbar.room.boutique">Бутикова</a></li>
                                <li><a href="<?php echo home_url('/Rooms#APARTMENT1'); ?>" data-i18n="navbar.room.apartment1">Апартамент 1</a></li>
                                <li><a href="<?php echo home_url('/Rooms#APARTMENT2'); ?>" data-i18n="navbar.room.apartment2">Апартамент 2</a></li>
                            </ul>
                        </li>

                        <li><a data-i18n="navbar.villa" href="<?php echo home_url('/Villa'); ?>">Къща</a></li>
                        <li><a data-i18n="navbar.restaurant" href="<?php echo home_url('/Restaurant'); ?>">Ресторант</a></li>
                        <li><a data-i18n="navbar.halls" href="<?php echo home_url('/Halls'); ?>">Зали</a></li>
                        <li><a data-i18n="navbar.about" href="<?php echo home_url('/About'); ?>">За нас</a></li>
                        <li><a data-i18n="navbar.contact" href="<?php echo home_url('/Contact'); ?>">Контакти</a></li>

                        <!-- Language switch -->

                        
                            

                    </ul>
                </nav>
            </div>

            <div class="outer-box">
                <div class="lang-switch-container">
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="bg" style="font-size: 12px; color:white;">BG</a>
                    </li>
                    <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center; color:white;">
                        |
                    </li>
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="en" style="font-size: 12px; color:white;">EN</a>
                    </li>
                    <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center; color:white;">
                        |
                    </li>
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="ro" style="font-size: 12px; color:white;">RO</a>
                    </li>
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
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="bg" style="font-size: 12px; color: white;">BG</a>
                    </li>
                    <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center">
                        |
                    </li>
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="en" style="font-size: 12px; color: white;">EN</a>
                    </li>
                    <li style="padding-left: 12px; font-size: 12px; display: flex; justify-content: center; align-items: center">
                        |
                    </li>
                    <li style="padding-left: 12px; display: flex; justify-content: center; align-items: center;">
                        <a href="<?php echo home_url('/Index'); ?>" class="lang-switch" data-lang="ro" style="font-size: 12px; color: white;">RO</a>
                    </li>
                </div>

                <ul class="contact-list-one">

                    <li>
                        <div class="contact-info-box">
                            <i class="icon lnr-icon-phone-handset"></i>
                            <span class="title" data-i18n="navbar.callUs">Обадете ни се</span>
                            <a href="tel:+359885185008">+359 885 185 008</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-envelope1"></span>
                            <span class="title" data-i18n="navbar.writeUs">Пишете ни</span>
                            <a href="mailto:info@pinovilla.com">info@pinovilla.com</a>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-clock"></span>
                            <span class="title" data-i18n="navbar.hours">Работно време</span>
                            <span data-i18n="navbar.openHours">Всеки ден 10:00 - 24:00</span>
                        </div>
                    </li>
                    <li>
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-clock"></span>
                            <span class="title" data-i18n="navbar.hours.restaurant">Работно време ресторант</span>
                            <span data-i18n="navbar.openHours.restaurant">Всеки ден 11:30 - 22:00</span>
                        </div>
                    </li>
                </ul>

                <ul class="social-links">
                    <li><a target="_blank" href="https://www.facebook.com/profile.php?id=61558690053409"><i
                                class="fab fa-facebook-f"></i></a></li>
                    <li><a target="_blank" href="https://www.instagram.com/pinovillaecucina/"><i
                                class="fa-brands fa-instagram"></i></a></li>
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
