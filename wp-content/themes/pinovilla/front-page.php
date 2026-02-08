<?php get_header(); ?>


    <main b-21sjaj47gr role="main">
        <section class="banner-section-seven">
    <div class="banner-slider banner-slider-home1">
        <div class="banner-slide">
            <div class="outer-box">
                <img class="image-1 tm-gsap-img-parallax overflow-hidden index-hero" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/hero.jpg" alt="">
                <div class="content-box">
                    <div class="star-rating" data-animation-in="fadeInUp" data-wow-delay="300ms">
                        <i class="fa-sharp fa-solid fa-star-sharp"></i>
                        <i class="fa-sharp fa-solid fa-star-sharp"></i>
                        <i class="fa-sharp fa-solid fa-star-sharp"></i>
                        <i class="fa-sharp fa-solid fa-star-sharp"></i>
                        <i class="fa-sharp fa-solid fa-star-sharp"></i>
                    </div>
                    <h1 data-i18n="banner.heading" data-animation-in="fadeInUp" data-delay-in="0.3">Pino - Villa, Casa,
                        Cucina е Terrazza</h1>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Section -->

<!-- Form Section -->
<div class="checkout-form-section-two">
    <div class="container">
        <form method="get" action="<?php echo home_url('/RoomAvalability'); ?>">
            <div class="checkout-form">
                <div class="checkout-field">
                    <h4 data-i18n="form.checkin"><label for="StartDate">Настаняване</label></h4>

                    <div class="chk-field">
                        <input class="date-pick" type="date" name="StartDate"
                               value="2025-12-12"
                               min="2025-12-12"/>
                        <i class="fas fa-angle-down"></i>
                    </div>
                </div>

                <div class="checkout-field">
                    <h4 data-i18n="form.checkout"><label for="EndDate">Освобождаване</label></h4>
                    <div class="chk-field">
                        <input class="date-pick" type="date" name="EndDate"
                               value="2025-12-13"
                               min="2025-12-12"/>
                        <i class="fas fa-angle-down"></i>
                    </div>
                </div>

                <div class="checkout-field select-field br-0">
                    <h4 data-i18n="form.guests">Гости</h4>
                    <div class="chk-field">
                        <input type="number" name="Guests" placeholder="0"/>
                    </div>
                </div>

                <button type="submit" class="theme-btn btn-style-one">
                    <span class="btn-title" data-i18n="form.check">Провери <br/>наличност</span>
                </button>
            </div>

        </form>
    </div>
</div>
<!-- End Form Section -->





    <section class="service-section-three">
        <div class="auto-container">
            <div class="sec-title text-center">
                <span class="sub-title" data-i18n="services.subtitle">Какво предлагаме </span>
                <h2 data-i18n="services.title">Тук ще намерите</h2>
            </div>
            <div class="outer-box">
                <div class="row">
                    <div class="service-block-three col-lg-4 col-md-6 wow fadeInUp">
                        <div class="inner-box">
                            <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/hotel.jpg" alt="">
                            <div class="content-box">
                                <h6 class="title"><a href="<?php echo home_url('/rooms'); ?>" data-i18n="service.hotel">Хотел</a></h6>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-three col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="inner-box">
                            <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/restaurant-card.jpg" alt="">
                            <div class="content-box">
                                <h6 class="title"><a href="<?php echo home_url('/restaurant'); ?>" data-i18n="service.restaurant">Ресторант</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="service-block-three col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                        <div class="inner-box">
                            <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-main.jpg" alt="">
                            <div class="content-box">
                                <h6 class="title"><a href="<?php echo home_url('/villa'); ?>" data-i18n="service.villa">Къща</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Service section -->

    <!-- About Section -->
    <section class="about-section-two pt-0">
        <div class="anim-icons">
            <img class="image-1" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/icon-home1.png" alt="">
        </div>
        <div class="auto-container">
            <div class="row">
                <div class="content-column col-lg-7 wow fadeInRight" data-wow-delay="600ms">
                    <div class="inner-column">
                        <div class="sec-title">
                        <span class="sub-title style-three"
                              data-i18n="about.subtitle">Villa, Casa, Cucina е Terrazza</span>
                            <h2 data-i18n="about.title">Вашето морско бягство <br/>в Албена</h2>
                            <div class="text" data-i18n="about.text">Добре дошли в нашия бутиков комплекс, разположен в
                                близост до борова гора на 1.5 км от морето край Албена. Предлагаме 15 уютни стаи, зали
                                за
                                събития, релакс зона, фитнес и басейн сред зеленина. На терасата-ресторант ви очакват
                                подбрани ястия и незабравима атмосфера.
                            </div>
                        </div>
                        <div class="outer-box">
                            <div class="info-block">
                                <div class="inner">
                                    <div class="icon-box"><img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/Icons/sunrise.svg" alt="" class="index-sea-icons"></div>
                                    <h4 class="title" data-i18n="about.sunrises">Красиви <br>изгреви</h4>
                                </div>
                            </div>
                            <div class="info-block">
                                <div class="inner">
                                    <div class="icon-box"><img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/Icons/prawn.svg" alt="" class="index-sea-icons"></div>
                                    <h4 class="title" data-i18n="about.mediterranean">Средиземноморска<br> кухня</h4>
                                </div>
                            </div>
                        </div>
                        <ul class="list-style-two">
                            <li data-i18n="about.relax"><i class="icon fa fa-circle-check"></i>Пълноценна почивка.</li>
                            <li data-i18n="about.calm"><i class="icon fa fa-circle-check"></i>Спокойна атмосфера – за
                                възрастни и деца над 10 г.
                            </li>
                            <li data-i18n="about.food"><i class="icon fa fa-circle-check"></i>Вкусна и разнообразна
                                храна.
                            </li>
                            <li data-i18n="about.parking"><i class="icon fa fa-circle-check"></i>Безплатен паркинг.</li>
                            <li data-i18n="about.wifi"><i class="icon fa fa-circle-check"></i>Безплатен Wi-Fi</li>
                        </ul>
                        <div class="btn-box">
                            <a href="<?php echo home_url('/about'); ?>" class="theme-btn btn-style-one"><span class="btn-title"
                                                                                   data-i18n="about.discover">Открийте още</span></a>
                        </div>
                    </div>
                </div>
                <div class="image-column col-md-7 col-lg-5">
                    <div class="inner-column wow fadeInLeft">
                        <img class="image-1 overlay-anim" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/pool.png" alt="">
                        <img class="image-2 overlay-anim" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/hotel-outside.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Emd About Section -->

    <!-- Room-section two -->
    <!-- ░░░ ROOM-SECTION TWO ░░░ -->
    <section class="room-service-section pt-120 pb-60">
        <div class="auto-container">
            <div class="sec-title text-center">
                <!-- rooms.subtitle -->
                <span class="sub-title" data-i18n="rooms.subtitle">Хотелски стаи</span>
                <!-- rooms.title -->
                <h2 data-i18n="rooms.title">Резервирайте стая</h2>
            </div>

            <div class="row">
                <!-- ▷ ECONOMIC DOUBLE -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/rooms'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/economic-double.jpeg" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.economic.name -->
                                <h4 class="title"><a href="<?php echo home_url('/rooms#ECONOMIC'); ?>" data-i18n="room.economic.name">Икономична
                                        стая</a></h4>

                                <div class="price">194 BGN
                                    / 99.19 €<span
                                        data-i18n="currencyPerNight"> - вечер</span></div>

                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li>
                                        <i class="fal fa-circle-user me-2"></i>
                                        1-2 <span data-i18n="guestsWord">гости</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ▷ DOUBLE -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/rooms'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/double.jpeg" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.double.name -->
                                <h4 class="title"><a href="<?php echo home_url('/rooms#DOUBLE'); ?>" data-i18n="room.double.name">Двойна стая</a>
                                </h4>
                                <div class="price">207 BGN
                                    / 105.84 €<span
                                        data-i18n="currencyPerNight"> - вечер</span></div>

                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li><i class="fal fa-circle-user me-2"></i>1-2 <span
                                            data-i18n="guestsWord">гости</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ▷ SUPERIOR -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="300ms">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/rooms'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/superior-room.png" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.superior.name -->
                                <h4 class="title"><a href="<?php echo home_url('/rooms#SUPERIOR'); ?>" data-i18n="room.superior.name">Супериорна
                                        стая</a></h4>
                                <div class="price">219 BGN
                                    / 111.97 € <span
                                        data-i18n="currencyPerNight">- вечер</span>
                                </div>
                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li><i class="fal fa-circle-user me-2"></i>1-2 <span
                                            data-i18n="guestsWord">гости</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ▷ BOUTIQUE -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="600ms">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/rooms'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/deluxe-room.png" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.boutique.name -->
                                <h4 class="title"><a href="<?php echo home_url('/rooms#BOUTIQUE'); ?>" data-i18n="room.boutique.name">Бутикова
                                        стая</a>
                                </h4>
                                <div class="price">238 BGN
                                    / 121.69 € <span
                                        data-i18n="currencyPerNight">- вечер</span>
                                </div>
                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li><i class="fal fa-circle-user me-2"></i>1-2 <span
                                            data-i18n="guestsWord">гости</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ▷ APARTMENT -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="600ms">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/rooms'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/apartment.jpeg" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.apartment.name -->
                                <h4 class="title"><a href="<?php echo home_url('/rooms#APARTMENT1'); ?>"
                                                     data-i18n="room.apartment.name">Апартамент</a>
                                </h4>
                                <div class="price">257 BGN
                                    / 131.4 € <span
                                        data-i18n="currencyPerNight">- вечер</span>
                                </div>

                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li><i class="fal fa-circle-user me-2"></i>1-4 <span
                                            data-i18n="guestsWord">гости</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ▷ VILLA -->
                <div class="room-service-block-one col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="600ms">
                    <div class="inner-box">
                        <div class="image-box">
                            <a href="<?php echo home_url('/villa'); ?>">
                                <img class="image mb-0 pino-rooms" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa.jpg" alt="">
                            </a>
                        </div>
                        <div class="content-box">
                            <div class="inner-box">
                                <!-- room.villa.name -->
                                <h4 class="title"><a href="<?php echo home_url('/villa'); ?>" data-i18n="room.villa.name">Самостоятелна къща</a>
                                </h4>
                                <div class="price">700 BGN
                                    / 357.9 € <span
                                        data-i18n="currencyPerNight">- вечер</span>
                                </div>

                            </div>
                            <div class="facilities-box align-items-center d-flex justify-content-between">
                                <ul class="facilities-list">
                                    <li><i class="fal fa-circle-user me-2"></i>1-6 <span
                                            data-i18n="guestsWord">гости</span>*
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.row -->
        </div>
    </section>
    <!-- End Room section -->

    <!-- Testimonial Section Two -->
    <!-- ░░░ TESTIMONIAL SECTION TWO ░░░ -->
    <section class="testimonial-section-two pt-0">
        <div class="anim-icons">
            <img class="image-1" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/shape-5.png" alt="">
        </div>
        <div class="auto-container">
            <div class="row">
                <div class="testimonials overflow-hidden col-lg-12">
                    <div class="swiper-container testimonial-slider-content">
                        <div class="swiper-wrapper">

                            <!-- ▷ TESTIMONIAL 1 -->
                            <div class="testimonial-block-two swiper-slide">
                                <div class="inner-box">
                                    <div class="quote-icon">
                                        <img class="icon-img" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/testi-shape1.png" alt="">
                                    </div>
                                    <!-- testimonial.1.text -->
                                    <div class="text" data-i18n="testimonial.1.text">
                                        Престоят ни в този хотел беше изключително приятен и запомнящ се.
                                        Хотелът е уютен и изключително добре поддържан, създаващ усещане за домашен
                                        комфорт
                                        и артистичност, благодарение на талантливата художничка и дизайнер Мария Илиева.
                                        Обслужването беше на най-високо ниво – персоналът винаги беше усмихнат и готов
                                        да
                                        помогне с всяко наше желание. Особено впечатление ми направи тяхната любезност и
                                        професионализъм. С удоволствие бихме се върнали отново и препоръчвам този хотел
                                        на
                                        всеки, който търси спокойствие и отлично обслужване.
                                    </div>
                                    <div class="info-box">
                                        <!-- testimonial.1.name -->
                                        <h5 class="name" data-i18n="testimonial.1.name">Деница</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- ▷ TESTIMONIAL 2 -->
                            <div class="testimonial-block-two swiper-slide">
                                <div class="inner-box">
                                    <div class="quote-icon">
                                        <img class="icon-img" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/testi-shape1.png" alt="">
                                    </div>
                                    <!-- testimonial.2.text -->
                                    <div class="text" data-i18n="testimonial.2.text">
                                        Уютен малък хотел, точно на кръговото на входа на курорт Албена, по
                                        посока на Добрич. Удобно и приятно място, в което можеш да отседнал за почивка
                                        извън
                                        лудницата на курорта, но близо до него и да ползваш плажовете му. Има и басейн,
                                        който може да ползваш, ако искаш да си извън лудницата на курорта. Място
                                        подходящо
                                        за почивка и за бизнес срещи и добро обслужвани, приветлив персонал, добър
                                        ресторант
                                        и добра храна.
                                    </div>
                                    <div class="info-box">
                                        <!-- testimonial.2.name -->
                                        <h5 class="name" data-i18n="testimonial.2.name">Иван</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- ▷ TESTIMONIAL 3 -->
                            <div class="testimonial-block-two swiper-slide">
                                <div class="inner-box">
                                    <div class="quote-icon">
                                        <img class="icon-img" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/testi-shape1.png" alt="">
                                    </div>
                                    <!-- testimonial.3.text -->
                                    <div class="text" data-i18n="testimonial.3.text">
                                        Интериора на хотела е прекрасен , създава много приятна атмосфера.
                                        Чисто, спретнато, персонала отзивчив във всичко . Приятни и усмихнати хора . На
                                        закуската имаше много вкусни и пресни плодове , прясна храна , вкусно кафе .
                                        Чист
                                        басейн , тенис на маса, баскетболното игрище но сянка под дърветата на вън.
                                        Страхотно място и за деца и само за възрастни ! Аквапарка е много близко
                                        предлагат
                                        от хотела и превоз до плажа , на мен и децата много ни хареса! Имахме уютна стая
                                        със
                                        страхотно огледало което ти създава усещането , че слънцето грее над леглото ти.
                                    </div>
                                    <div class="info-box">
                                        <!-- testimonial.3.name -->
                                        <h5 class="name" data-i18n="testimonial.3.name">Диана</h5>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- /.swiper-wrapper -->

                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div> <!-- /.testimonial-slider-content -->

                    <!-- empty thumbs slider remains unchanged -->
                    <div class="swiper-container testimonial-thumbs mx-auto">
                        <div class="swiper-wrapper"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ░░░ END TESTIMONIAL SECTION ░░░ -->

    <!-- End Testimonial Section Two -->


    <!-- Banner Section Eight -->
    <section class="banner-section-eight">
        <div class="banner-slider banner-slider-home3">
            <!-- Slide 1 -->
            <div class="banner-slide">
                <div class="inner-slide">
                    <div class="bg bg-image overflow-hidden"
                         style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-pool.jpg);"></div>
                    <div class="outer-box">
                        <div class="images-column">
                            <div class="inner-column wow fadeInRight" data-wow-delay="200ms">
                                <div class="bg bg-image1"
                                     style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-pool.jpg);"></div>
                            </div>
                        </div>
                        <div class="content-column">
                            <div class="inner-column">
                                <div class="offer-text wow fadeInUp" data-wow-delay="300ms" data-i18n="villa.offer">
                                    Резрервирай нашата къща
                                </div>
                                <h1 class="title wow fadeInUp" data-wow-delay="600ms" data-i18n="villa.title1">Луксозно
                                    уединение <br> сред природата</h1>
                                <div class="btn-box wow fadeInUp" data-wow-delay="900ms">
                                    <a href="/HallAvailabilityPage" class="theme-btn btn-style-two"><span
                                            class="btn-title"
                                            data-i18n="villa.book">Резервирай</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="info-text">PINO CASA</div>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="banner-slide">
                <div class="inner-slide">
                    <div class="bg bg-image overflow-hidden"
                         style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-pool.jpg);"></div>
                    <div class="outer-box">
                        <div class="images-column">
                            <div class="inner-column wow fadeInRight" data-wow-delay="200ms">
                                <div class="bg bg-image1"
                                     style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-index-1.jpg);"></div>
                            </div>
                        </div>
                        <div class="content-column">
                            <div class="inner-column">
                                <div class="offer-text wow fadeInUp" data-wow-delay="300ms" data-i18n="villa.offer">
                                    Резрервирай нашата къща
                                </div>
                                <h1 class="title wow fadeInUp" data-wow-delay="600ms" data-i18n="villa.title2">Уют за
                                    <br>
                                    споделени моменти</h1>
                                <div class="btn-box wow fadeInUp" data-wow-delay="900ms">
                                    <a href="/BookVilla" class="theme-btn btn-style-two"><span class="btn-title"
                                                                                               data-i18n="villa.book">Резервирай</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="info-text">PINO CASA</div>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="banner-slide">
                <div class="inner-slide">
                    <div class="bg bg-image overflow-hidden"
                         style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-pool.jpg);"></div>
                    <div class="outer-box">
                        <div class="images-column">
                            <div class="inner-column wow fadeInRight" data-wow-delay="200ms">
                                <div class="bg bg-image1"
                                     style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/villa-index-2.jpg);"></div>
                            </div>
                        </div>
                        <div class="content-column">
                            <div class="inner-column">
                                <div class="offer-text wow fadeInUp" data-wow-delay="300ms" data-i18n="villa.offer">
                                    Резрервирай нашата къща
                                </div>
                                <h1 class="title wow fadeInUp" data-wow-delay="600ms" data-i18n="villa.title3">Стил, уют
                                    и
                                    <br> комфорт</h1>
                                <div class="btn-box wow fadeInUp" data-wow-delay="900ms">
                                    <a href="<?php echo home_url('/VillaAvailability'); ?>" class="theme-btn btn-style-two"><span class="btn-title"
                                                                                                       data-i18n="villa.book">Резервирай</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="info-text">PINO CASA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Banner Section -->



    <!-- pricing-section -->
    <section class="pricing-section">
        <div class="auto-container">
            <div class="sec-title text-center wow fadeInUp">
                <span class="sub-title" data-i18n="pricing.subtitle">Ресторант</span>
                <h2 data-i18n="pricing.title">Специални предложения</h2>
            </div>

            <div class="row gx-xl-5 wow slideInUp">
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x421;&#x430;&#x43B;&#x430;&#x442;&#x430; &#x201E;&#x411;&#x443;&#x440;&#x430;&#x442;&#x430; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x201C;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x421;&#x430;&#x43B;&#x430;&#x442;&#x430; &#x201E;&#x411;&#x443;&#x440;&#x430;&#x442;&#x430; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x201C;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    19.90 &#x43B;&#x432;. / 10.17 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x438;&#x437;&#x43A;&#x443;&#x441;&#x438;&#x442;&#x435;&#x43B;&#x43D;&#x430; &#x43A;&#x43E;&#x43C;&#x431;&#x438;&#x43D;&#x430;&#x446;&#x438;&#x44F; &#x43E;&#x442; &#x441;&#x438;&#x440;&#x435;&#x43D;&#x435; &#x201E;&#x411;&#x443;&#x440;&#x430;&#x442;&#x430;&#x201C;, &#x43E;&#x431;&#x43E;&#x433;&#x430;&#x442;&#x435;&#x43D;&#x43E; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#xD;&#xA;&#x43D;&#x430; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x43E; &#x441; &#x440;&#x443;&#x43A;&#x43E;&#x43B;&#x430;, &#x437;&#x435;&#x43B;&#x435;&#x43D; &#x43B;&#x443;&#x43A;, &#x431;&#x435;&#x43B;&#x435;&#x43D;&#x438; &#x438;/&#x438;&#x43B;&#x438; &#x447;&#x435;&#x440;&#x438; &#x434;&#x43E;&#x43C;&#x430;&#x442;&#x438;,&#xD;&#xA;&#x441;&#x444;&#x435;&#x440;&#x438; &#x43E;&#x442; &#x431;&#x430;&#x43B;&#x441;&#x430;&#x43C;&#x43E;&#x432; &#x43E;&#x446;&#x435;&#x442; &#x438; &#x43B;&#x438;&#x43E;&#x444;&#x438;&#x43B;&#x438;&#x437;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x434;&#x43E;&#x43C;&#x430;&#x442;&#x438; &#x438; &#x43F;&#x440;&#x430;&#x445; &#x43E;&#x442; &#x43C;&#x430;&#x441;&#x43B;&#x438;&#x43D;&#x438;,&#xD;&#xA;&#x43F;&#x440;&#x438;&#x431;&#x430;&#x432;&#x44F;&#x449; &#x434;&#x44A;&#x43B;&#x431;&#x43E;&#x447;&#x438;&#x43D;&#x430; &#x43D;&#x430; &#x432;&#x43A;&#x443;&#x441;&#x430;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x438;&#x437;&#x43A;&#x443;&#x441;&#x438;&#x442;&#x435;&#x43B;&#x43D;&#x430; &#x43A;&#x43E;&#x43C;&#x431;&#x438;&#x43D;&#x430;&#x446;&#x438;&#x44F; &#x43E;&#x442; &#x441;&#x438;&#x440;&#x435;&#x43D;&#x435; &#x201E;&#x411;&#x443;&#x440;&#x430;&#x442;&#x430;&#x201C;, &#x43E;&#x431;&#x43E;&#x433;&#x430;&#x442;&#x435;&#x43D;&#x43E; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#xD;&#xA;&#x43D;&#x430; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x43E; &#x441; &#x440;&#x443;&#x43A;&#x43E;&#x43B;&#x430;, &#x437;&#x435;&#x43B;&#x435;&#x43D; &#x43B;&#x443;&#x43A;, &#x431;&#x435;&#x43B;&#x435;&#x43D;&#x438; &#x438;/&#x438;&#x43B;&#x438; &#x447;&#x435;&#x440;&#x438; &#x434;&#x43E;&#x43C;&#x430;&#x442;&#x438;,&#xD;&#xA;&#x441;&#x444;&#x435;&#x440;&#x438; &#x43E;&#x442; &#x431;&#x430;&#x43B;&#x441;&#x430;&#x43C;&#x43E;&#x432; &#x43E;&#x446;&#x435;&#x442; &#x438; &#x43B;&#x438;&#x43E;&#x444;&#x438;&#x43B;&#x438;&#x437;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x434;&#x43E;&#x43C;&#x430;&#x442;&#x438; &#x438; &#x43F;&#x440;&#x430;&#x445; &#x43E;&#x442; &#x43C;&#x430;&#x441;&#x43B;&#x438;&#x43D;&#x438;,&#xD;&#xA;&#x43F;&#x440;&#x438;&#x431;&#x430;&#x432;&#x44F;&#x449; &#x434;&#x44A;&#x43B;&#x431;&#x43E;&#x447;&#x438;&#x43D;&#x430; &#x43D;&#x430; &#x432;&#x43A;&#x443;&#x441;&#x430;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x421;&#x422;&#x410;&#x420;&#x422;&#x415;&#x420;&#x418;"
                                  data-t-en="STARTERS"
                                  data-t-ro="STARTERS">
                              &#x421;&#x422;&#x410;&#x420;&#x422;&#x415;&#x420;&#x418;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x413;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431; &#x441;&#x44A;&#x441; &#x437;&#x435;&#x43B;&#x435;&#x43D;&#x430; &#x44F;&#x431;&#x44A;&#x43B;&#x43A;&#x430;, &#x444;&#x43B;&#x430;&#x43C;&#x431;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x441; &#x43A;&#x43E;&#x43D;&#x44F;&#x43A; &#x201E;&#x41A;&#x430;&#x43B;&#x432;&#x430;&#x434;&#x43E;&#x441;&#x201C;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x413;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431; &#x441;&#x44A;&#x441; &#x437;&#x435;&#x43B;&#x435;&#x43D;&#x430; &#x44F;&#x431;&#x44A;&#x43B;&#x43A;&#x430;, &#x444;&#x43B;&#x430;&#x43C;&#x431;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x441; &#x43A;&#x43E;&#x43D;&#x44F;&#x43A; &#x201E;&#x41A;&#x430;&#x43B;&#x432;&#x430;&#x434;&#x43E;&#x441;&#x201C;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    27.90 &#x43B;&#x432;. / 14.27 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x43D;&#x435;&#x436;&#x435;&#x43D; &#x438; &#x434;&#x435;&#x43B;&#x438;&#x43A;&#x430;&#x442;&#x435;&#x43D; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431;, &#x441;&#x44A;&#x447;&#x435;&#x442;&#x430;&#x43D; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x435;&#x436;&#x435;&#x441;&#x442;&#x442;&#x430; &#x43D;&#x430; &#x437;&#x435;&#x43B;&#x435;&#x43D;&#x430;&#xD;&#xA;&#x44F;&#x431;&#x44A;&#x43B;&#x43A;&#x430; &#x438; &#x444;&#x43B;&#x430;&#x43C;&#x431;&#x438;&#x440;&#x430;&#x43D; &#x441; &#x438;&#x437;&#x438;&#x441;&#x43A;&#x430;&#x43D; &#x201E;&#x41A;&#x430;&#x43B;&#x432;&#x430;&#x434;&#x43E;&#x441;&#x201C;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x43D;&#x435;&#x436;&#x435;&#x43D; &#x438; &#x434;&#x435;&#x43B;&#x438;&#x43A;&#x430;&#x442;&#x435;&#x43D; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431;, &#x441;&#x44A;&#x447;&#x435;&#x442;&#x430;&#x43D; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x435;&#x436;&#x435;&#x441;&#x442;&#x442;&#x430; &#x43D;&#x430; &#x437;&#x435;&#x43B;&#x435;&#x43D;&#x430;&#xD;&#xA;&#x44F;&#x431;&#x44A;&#x43B;&#x43A;&#x430; &#x438; &#x444;&#x43B;&#x430;&#x43C;&#x431;&#x438;&#x440;&#x430;&#x43D; &#x441; &#x438;&#x437;&#x438;&#x441;&#x43A;&#x430;&#x43D; &#x201E;&#x41A;&#x430;&#x43B;&#x432;&#x430;&#x434;&#x43E;&#x441;&#x201C;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x422;&#x41E;&#x41F;&#x41B;&#x418; &#x41F;&#x420;&#x415;&#x414;&#x42F;&#x421;&#x422;&#x418;&#x42F;"
                                  data-t-en="WARM STARTERS"
                                  data-t-ro="WARM STARTERS">
                              &#x422;&#x41E;&#x41F;&#x41B;&#x418; &#x41F;&#x420;&#x415;&#x414;&#x42F;&#x421;&#x422;&#x418;&#x42F;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x413;&#x44C;&#x43E;&#x437;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x438;&#x43D;&#x441;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x438; &#x441; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431; &#x438; &#x441;&#x43E;&#x441; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x438;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x413;&#x44C;&#x43E;&#x437;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x438;&#x43D;&#x441;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x438; &#x441; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431; &#x438; &#x441;&#x43E;&#x441; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x438;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    25.90 &#x43B;&#x432;. / 13.24 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x442;&#x440;&#x430;&#x434;&#x438;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x438; &#x433;&#x44C;&#x43E;&#x437;&#x438;, &#x43F;&#x44A;&#x43B;&#x43D;&#x435;&#x43D;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x438;&#x43D;&#x441;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x438; &#x432;&#xD;&#xA;&#x441;&#x44A;&#x432;&#x44A;&#x440;&#x448;&#x435;&#x43D;&#x441;&#x442;&#x432;&#x43E; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430; &#x442;&#x435;&#x43A;&#x441;&#x442;&#x443;&#x440;&#x430; &#x438; &#x43E;&#x431;&#x43E;&#x433;&#x430;&#x442;&#x435;&#x43D;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x43F;&#x435;&#x446;&#x438;&#x430;&#x43B;&#x43D;&#x438;&#x44F; &#x432;&#x43A;&#x443;&#x441;&#xD;&#xA;&#x43D;&#x430; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431;, &#x441;&#x444;&#x435;&#x440;&#x438; &#x43E;&#x442; &#x441;&#x43E;&#x435;&#x432; &#x441;&#x43E;&#x441; &#x438; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x435;&#x43D; &#x441;&#x43E;&#x441; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x438;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x442;&#x440;&#x430;&#x434;&#x438;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x438; &#x433;&#x44C;&#x43E;&#x437;&#x438;, &#x43F;&#x44A;&#x43B;&#x43D;&#x435;&#x43D;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x432;&#x438;&#x43D;&#x441;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x438; &#x432;&#xD;&#xA;&#x441;&#x44A;&#x432;&#x44A;&#x440;&#x448;&#x435;&#x43D;&#x441;&#x442;&#x432;&#x43E; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430; &#x442;&#x435;&#x43A;&#x441;&#x442;&#x443;&#x440;&#x430; &#x438; &#x43E;&#x431;&#x43E;&#x433;&#x430;&#x442;&#x435;&#x43D;&#x438; &#x441;&#x44A;&#x441; &#x441;&#x43F;&#x435;&#x446;&#x438;&#x430;&#x43B;&#x43D;&#x438;&#x44F; &#x432;&#x43A;&#x443;&#x441;&#xD;&#xA;&#x43D;&#x430; &#x433;&#x44A;&#x448;&#x438; &#x434;&#x440;&#x43E;&#x431;, &#x441;&#x444;&#x435;&#x440;&#x438; &#x43E;&#x442; &#x441;&#x43E;&#x435;&#x432; &#x441;&#x43E;&#x441; &#x438; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x435;&#x43D; &#x441;&#x43E;&#x441; &#x441; &#x442;&#x440;&#x44E;&#x444;&#x435;&#x43B;&#x438;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x422;&#x41E;&#x41F;&#x41B;&#x418; &#x41F;&#x420;&#x415;&#x414;&#x42F;&#x421;&#x422;&#x418;&#x42F;"
                                  data-t-en="WARM STARTERS"
                                  data-t-ro="WARM STARTERS">
                              &#x422;&#x41E;&#x41F;&#x41B;&#x418; &#x41F;&#x420;&#x415;&#x414;&#x42F;&#x421;&#x422;&#x418;&#x42F;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x422;&#x430;&#x43B;&#x438;&#x430;&#x442;&#x435;&#x43B;&#x438; &#x201E;&#x41F;&#x430;&#x440;&#x43C;&#x438;&#x434;&#x436;&#x430;&#x43D;&#x430;&#x201C;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x422;&#x430;&#x43B;&#x438;&#x430;&#x442;&#x435;&#x43B;&#x438; &#x201E;&#x41F;&#x430;&#x440;&#x43C;&#x438;&#x434;&#x436;&#x430;&#x43D;&#x430;&#x201C;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    17.90 &#x43B;&#x432;. / 9.15 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x43F;&#x440;&#x438;&#x433;&#x43E;&#x442;&#x432;&#x435;&#x43D;&#x438; &#x432; &#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432;&#x430; &#x43F;&#x438;&#x442;&#x430;, &#x433;&#x430;&#x440;&#x43D;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x441; &#x433;&#x443;&#x430;&#x43D;&#x447;&#x430;&#x43B;&#x435; &#x438; &#x43F;&#x438;&#x43F;&#x435;&#x440;&#xD;&#xA;&#x43C;&#x435;&#x43B;&#x430;&#x43D;&#x436;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x43F;&#x440;&#x438;&#x433;&#x43E;&#x442;&#x432;&#x435;&#x43D;&#x438; &#x432; &#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432;&#x430; &#x43F;&#x438;&#x442;&#x430;, &#x433;&#x430;&#x440;&#x43D;&#x438;&#x440;&#x430;&#x43D;&#x438; &#x441; &#x433;&#x443;&#x430;&#x43D;&#x447;&#x430;&#x43B;&#x435; &#x438; &#x43F;&#x438;&#x43F;&#x435;&#x440;&#xD;&#xA;&#x43C;&#x435;&#x43B;&#x430;&#x43D;&#x436;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x41F;&#x410;&#x421;&#x422;&#x410;"
                                  data-t-en="PASTA"
                                  data-t-ro="PASTA">
                              &#x41F;&#x410;&#x421;&#x422;&#x410;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x412;&#x438;&#x435;&#x43D;&#x441;&#x43A;&#x438; &#x448;&#x43D;&#x438;&#x446;&#x435;&#x43B; &#x441; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43C;&#x430;&#x441;&#x43B;&#x43E; &#x438; &#x447;&#x438;&#x43F;&#x441;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x412;&#x438;&#x435;&#x43D;&#x441;&#x43A;&#x438; &#x448;&#x43D;&#x438;&#x446;&#x435;&#x43B; &#x441; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43C;&#x430;&#x441;&#x43B;&#x43E; &#x438; &#x447;&#x438;&#x43F;&#x441;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    32.00 &#x43B;&#x432;. / 16.36 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x43A;&#x43B;&#x430;&#x441;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438; &#x432;&#x438;&#x435;&#x43D;&#x441;&#x43A;&#x438; &#x448;&#x43D;&#x438;&#x446;&#x435;&#x43B; &#x43E;&#x442; &#x43D;&#x430;&#x447;&#x443;&#x43A;&#x430;&#x43D;&#x43E; &#x442;&#x435;&#x43B;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430;&#xD;&#xA;&#x437;&#x43B;&#x430;&#x442;&#x438;&#x441;&#x442;&#x430; &#x43A;&#x43E;&#x440;&#x438;&#x447;&#x43A;&#x430;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43C;&#x430;&#x441;&#x43B;&#x43E;, &#x43F;&#x440;&#x438;&#x434;&#x430;&#x432;&#x430;&#x449;&#x43E;&#xD;&#xA;&#x441;&#x432;&#x435;&#x436;&#x438; &#x438; &#x438;&#x437;&#x438;&#x441;&#x43A;&#x430;&#x43D;&#x438; &#x43D;&#x43E;&#x442;&#x43A;&#x438;, &#x438; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432; &#x447;&#x438;&#x43F;&#x441;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x43A;&#x43B;&#x430;&#x441;&#x438;&#x447;&#x435;&#x441;&#x43A;&#x438; &#x432;&#x438;&#x435;&#x43D;&#x441;&#x43A;&#x438; &#x448;&#x43D;&#x438;&#x446;&#x435;&#x43B; &#x43E;&#x442; &#x43D;&#x430;&#x447;&#x443;&#x43A;&#x430;&#x43D;&#x43E; &#x442;&#x435;&#x43B;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x435;&#x441;&#x43E; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430;&#xD;&#xA;&#x437;&#x43B;&#x430;&#x442;&#x438;&#x441;&#x442;&#x430; &#x43A;&#x43E;&#x440;&#x438;&#x447;&#x43A;&#x430;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43C;&#x430;&#x441;&#x43B;&#x43E;, &#x43F;&#x440;&#x438;&#x434;&#x430;&#x432;&#x430;&#x449;&#x43E;&#xD;&#xA;&#x441;&#x432;&#x435;&#x436;&#x438; &#x438; &#x438;&#x437;&#x438;&#x441;&#x43A;&#x430;&#x43D;&#x438; &#x43D;&#x43E;&#x442;&#x43A;&#x438;, &#x438; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432; &#x447;&#x438;&#x43F;&#x441;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;"
                                  data-t-en="MAIN DISHES"
                                  data-t-ro="MAIN DISHES">
                              &#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x424;&#x438;&#x43B;&#x435; &#x43E;&#x442; &#x43B;&#x430;&#x432;&#x440;&#x430;&#x43A; &#x441; &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x43E; &#x440;&#x438;&#x437;&#x43E;&#x442;&#x43E; &#x438; &#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432; &#x447;&#x438;&#x43F;&#x441;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x424;&#x438;&#x43B;&#x435; &#x43E;&#x442; &#x43B;&#x430;&#x432;&#x440;&#x430;&#x43A; &#x441; &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x43E; &#x440;&#x438;&#x437;&#x43E;&#x442;&#x43E; &#x438; &#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432; &#x447;&#x438;&#x43F;&#x441;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    29.90 &#x43B;&#x432;. / 15.29 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x444;&#x438;&#x43B;&#x435; &#x43E;&#x442; &#x43B;&#x430;&#x432;&#x440;&#x430;&#x43A;, &#x441;&#x44A;&#x447;&#x435;&#x442;&#x430;&#x43D;&#x43E; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x43E; &#x440;&#x438;&#x437;&#x43E;&#x442;&#x43E;, &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#xD;&#xA;&#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432; &#x447;&#x438;&#x43F;&#x441;, &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x438; &#x441;&#x444;&#x435;&#x440;&#x438; &#x438; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43E;&#x43B;&#x438;&#x43E;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x444;&#x438;&#x43B;&#x435; &#x43E;&#x442; &#x43B;&#x430;&#x432;&#x440;&#x430;&#x43A;, &#x441;&#x44A;&#x447;&#x435;&#x442;&#x430;&#x43D;&#x43E; &#x441; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x43E; &#x440;&#x438;&#x437;&#x43E;&#x442;&#x43E;, &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#xD;&#xA;&#x43F;&#x430;&#x440;&#x43C;&#x435;&#x437;&#x430;&#x43D;&#x43E;&#x432; &#x447;&#x438;&#x43F;&#x441;, &#x43B;&#x438;&#x43C;&#x43E;&#x43D;&#x43E;&#x432;&#x438; &#x441;&#x444;&#x435;&#x440;&#x438; &#x438; &#x430;&#x440;&#x43E;&#x43C;&#x430;&#x442;&#x43D;&#x43E; &#x431;&#x438;&#x43B;&#x43A;&#x43E;&#x432;&#x43E; &#x43E;&#x43B;&#x438;&#x43E;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;"
                                  data-t-en="MAIN DISHES"
                                  data-t-ro="MAIN DISHES">
                              &#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x41F;&#x430;&#x442;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x430;&#x433;&#x440;&#x435; &#x441; &#x43F;&#x44E;&#x440;&#x435; &#x43E;&#x442; &#x43A;&#x430;&#x440;&#x444;&#x438;&#x43E;&#x43B; &#x438; &#x43F;&#x43E;&#x440;&#x442;&#x43E;&#x43A;&#x430;&#x43B;&#x43E;&#x432; &#x433;&#x430;&#x441;&#x442;&#x440;&#x438;&#x43A;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x41F;&#x430;&#x442;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x430;&#x433;&#x440;&#x435; &#x441; &#x43F;&#x44E;&#x440;&#x435; &#x43E;&#x442; &#x43A;&#x430;&#x440;&#x444;&#x438;&#x43E;&#x43B; &#x438; &#x43F;&#x43E;&#x440;&#x442;&#x43E;&#x43A;&#x430;&#x43B;&#x43E;&#x432; &#x433;&#x430;&#x441;&#x442;&#x440;&#x438;&#x43A;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    29.90 &#x43B;&#x432;. / 15.29 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x43D;&#x435;&#x436;&#x43D;&#x43E; &#x43F;&#x430;&#x442;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x430;&#x433;&#x440;&#x435; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430; &#x43A;&#x43E;&#x436;&#x438;&#x447;&#x43A;&#x430;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x43E; &#x432;&#x44A;&#x440;&#x445;&#x443; &#x444;&#x438;&#x43D;&#x43E;&#xD;&#xA;&#x43F;&#x44E;&#x440;&#x435; &#x441; &#x43A;&#x430;&#x440;&#x444;&#x438;&#x43E;&#x43B; &#x438; &#x434;&#x43E;&#x43F;&#x44A;&#x43B;&#x43D;&#x435;&#x43D;&#x43E; &#x43E;&#x442; &#x43F;&#x43E;&#x440;&#x442;&#x43E;&#x43A;&#x430;&#x43B;&#x43E;&#x432;&#x438;&#x44F; &#x433;&#x430;&#x441;&#x442;&#x440;&#x438;&#x43A;, &#x43E;&#x441;&#x438;&#x433;&#x443;&#x440;&#x44F;&#x432;&#x430;&#x449;&#xD;&#xA;&#x43B;&#x435;&#x43A;&#x43E; &#x43A;&#x438;&#x441;&#x435;&#x43B;&#x430; &#x438; &#x441;&#x43B;&#x430;&#x434;&#x43A;&#x430; &#x43D;&#x43E;&#x442;&#x43A;&#x430;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x43D;&#x435;&#x436;&#x43D;&#x43E; &#x43F;&#x430;&#x442;&#x435;&#x448;&#x43A;&#x43E; &#x43C;&#x430;&#x433;&#x440;&#x435; &#x441; &#x445;&#x440;&#x443;&#x43F;&#x43A;&#x430;&#x432;&#x430; &#x43A;&#x43E;&#x436;&#x438;&#x447;&#x43A;&#x430;, &#x43F;&#x43E;&#x434;&#x43D;&#x435;&#x441;&#x435;&#x43D;&#x43E; &#x432;&#x44A;&#x440;&#x445;&#x443; &#x444;&#x438;&#x43D;&#x43E;&#xD;&#xA;&#x43F;&#x44E;&#x440;&#x435; &#x441; &#x43A;&#x430;&#x440;&#x444;&#x438;&#x43E;&#x43B; &#x438; &#x434;&#x43E;&#x43F;&#x44A;&#x43B;&#x43D;&#x435;&#x43D;&#x43E; &#x43E;&#x442; &#x43F;&#x43E;&#x440;&#x442;&#x43E;&#x43A;&#x430;&#x43B;&#x43E;&#x432;&#x438;&#x44F; &#x433;&#x430;&#x441;&#x442;&#x440;&#x438;&#x43A;, &#x43E;&#x441;&#x438;&#x433;&#x443;&#x440;&#x44F;&#x432;&#x430;&#x449;&#xD;&#xA;&#x43B;&#x435;&#x43A;&#x43E; &#x43A;&#x438;&#x441;&#x435;&#x43B;&#x430; &#x438; &#x441;&#x43B;&#x430;&#x434;&#x43A;&#x430; &#x43D;&#x43E;&#x442;&#x43A;&#x430;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;"
                                  data-t-en="MAIN DISHES"
                                  data-t-ro="MAIN DISHES">
                              &#x41E;&#x421;&#x41D;&#x41E;&#x412;&#x41D;&#x418;
                        </span>
                        </div>
                    </div>
                    <div class="pricing-block-two col-lg-6 col-md-6 col-sm-12">
                        <div class="inner-box">


                            <div class="content-box">
                                <h6 class="title">
                                    <!-- menu name -->
                                    <span data-t-bg="&#x417;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x201E;&#x41C;&#x430;&#x442;&#x447;&#x430; &#x422;&#x438;&#x440;&#x430;&#x43C;&#x438;&#x441;&#x443;&#x201C;"
                                          data-t-en=""
                                          data-t-ro="">
                                    &#x417;&#x43B;&#x430;&#x442;&#x43D;&#x43E; &#x201E;&#x41C;&#x430;&#x442;&#x447;&#x430; &#x422;&#x438;&#x440;&#x430;&#x43C;&#x438;&#x441;&#x443;&#x201C;
                                </span>

                                    <!-- price -->
                                    <span class="menu-price" style="flex-shrink:0;">
                                    13.90 &#x43B;&#x432;. / 7.11 &#x20AC;
                                </span>
                                </h6>

                                <!-- description -->
                                <span class="designation"
                                      data-t-bg="/&#x442;&#x440;&#x430;&#x434;&#x438;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x438;&#x44F;&#x442; &#x438;&#x442;&#x430;&#x43B;&#x438;&#x430;&#x43D;&#x441;&#x43A;&#x438; &#x434;&#x435;&#x441;&#x435;&#x440;&#x442; &#x201E;&#x422;&#x438;&#x440;&#x430;&#x43C;&#x438;&#x441;&#x443;&#x201C;, &#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x432;&#x430;&#x449;&#xD;&#xA;&#x432;&#x44A;&#x43B;&#x43D;&#x443;&#x432;&#x430;&#x449; &#x430;&#x437;&#x438;&#x430;&#x442;&#x441;&#x43A;&#x438; &#x43E;&#x431;&#x440;&#x430;&#x442; &#x441; &#x434;&#x43E;&#x431;&#x430;&#x432;&#x44F;&#x43D;&#x435;&#x442;&#x43E; &#x43D;&#x430; &#x43C;&#x430;&#x442;&#x447;&#x430; &#x438; &#x44E;&#x437;&#x443;/"
                                      data-t-en=""
                                      data-t-ro="">
                                /&#x442;&#x440;&#x430;&#x434;&#x438;&#x446;&#x438;&#x43E;&#x43D;&#x43D;&#x438;&#x44F;&#x442; &#x438;&#x442;&#x430;&#x43B;&#x438;&#x430;&#x43D;&#x441;&#x43A;&#x438; &#x434;&#x435;&#x441;&#x435;&#x440;&#x442; &#x201E;&#x422;&#x438;&#x440;&#x430;&#x43C;&#x438;&#x441;&#x443;&#x201C;, &#x43F;&#x43E;&#x43B;&#x443;&#x447;&#x430;&#x432;&#x430;&#x449;&#xD;&#xA;&#x432;&#x44A;&#x43B;&#x43D;&#x443;&#x432;&#x430;&#x449; &#x430;&#x437;&#x438;&#x430;&#x442;&#x441;&#x43A;&#x438; &#x43E;&#x431;&#x440;&#x430;&#x442; &#x441; &#x434;&#x43E;&#x431;&#x430;&#x432;&#x44F;&#x43D;&#x435;&#x442;&#x43E; &#x43D;&#x430; &#x43C;&#x430;&#x442;&#x447;&#x430; &#x438; &#x44E;&#x437;&#x443;/
                            </span>
                            </div>

                            <!-- category badge -->
                            <span class="food-pack"
                                  data-t-bg="&#x414;&#x415;&#x421;&#x415;&#x420;&#x422;&#x418;"
                                  data-t-en="DESSERTS"
                                  data-t-ro="DESSERTS">
                              &#x414;&#x415;&#x421;&#x415;&#x420;&#x422;&#x418;
                        </span>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- End pricing-section -->

    <!-- video-section -->
    <section class="video-section">
        <div class="bg bg-image" data-speed="0.5" data-parallax="scroll"
             style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/restaurant-1.jpg);">
        </div>
        <div class="auto-container">
            <div class="row align-items-center">
                <div class="btn-column col-lg-12">
                    <div class="inner-column text-center">

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End video section -->

    <!-- Feature section four-->
    <section class="feature-section-four">
        <div class="container">
            <div class="outer-box">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="sec-title">
                            <span class="sub-title style-three">PINO</span>
                            <h2 data-i18n="feature.title">Конферентни зали</h2>
                            <div class="text" data-i18n="feature.text">Нашите модерни конферентни зали предлагат
                                перфектната
                                обстановка за успешни бизнес срещи, семинари и корпоративни събития.
                            </div>
                            <div class="btn-box">
                                <a href="<?php echo home_url('/halls'); ?>" class="theme-btn btn-style-two"><span class="btn-title"
                                                                                       data-i18n="feature.more">Научете повече</span></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="outer-box">
                            <div class="row">
                                <div class="feature-block-four col-lg-4 col-sm-6 wow fadeIn" data-wow-delay="100ms">
                                    <div class="inner-box">
                                        <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/conference-room-1.jpg" alt="">
                                        <div class="content-box"><h4 class="title"><a href="<?php echo home_url('/halls'); ?>"
                                                                                      data-i18n="feature.events">Събития</a>
                                            </h4></div>
                                    </div>
                                </div>
                                <div class="feature-block-four col-lg-4 col-sm-6 wow fadeIn" data-wow-delay="200ms">
                                    <div class="inner-box">
                                        <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/conference-room-2.jpg" alt="">
                                        <div class="content-box"><h4 class="title"><a href="<?php echo home_url('/halls'); ?>"
                                                                                      data-i18n="feature.business">Бизнес
                                                    събирания</a></h4></div>
                                    </div>
                                </div>
                                <div class="feature-block-four col-lg-4 col-sm-6 wow fadeIn" data-wow-delay="300ms">
                                    <div class="inner-box">
                                        <img class="image" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/conference-room-3.jpg" alt="">
                                        <div class="content-box"><h4 class="title"><a href="<?php echo home_url('/halls'); ?>"
                                                                                      data-i18n="feature.celebrations">Празници</a>
                                            </h4></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End feature section -->



    <!-- Funfact Section -->
    <section class="funfact-section">
        <div class="bg bg-image" style="background-image: url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/bg-shape2.png);"></div>
        <div class="container">
            <div class="fact-counter">
                <div class="row">
                    <div class="counter-block-one col-lg-3 col-sm-6">
                        <div class="inner-box">
                            <div class="count-box"><span class="count-text" data-speed="2000" data-stop="15">0</span>
                            </div>
                            <div class="counter-text" data-i18n="funfact.rooms">Стаи</div>
                        </div>
                    </div>
                    <div class="counter-block-one col-lg-3 col-sm-6">
                        <div class="inner-box">
                            <div class="count-box"><span class="count-text" data-speed="1000" data-stop="1">0</span>
                            </div>
                            <div class="counter-text" data-i18n="funfact.house">Къща</div>
                        </div>
                    </div>
                    <div class="counter-block-one col-lg-3 col-sm-6">
                        <div class="inner-box">
                            <div class="count-box"><span class="count-text" data-speed="2000" data-stop="2">0</span>
                            </div>
                            <div class="counter-text" data-i18n="funfact.halls">Зали</div>
                        </div>
                    </div>
                    <div class="counter-block-one col-lg-3 col-sm-6">
                        <div class="inner-box">
                            <div class="count-box"><span class="count-text" data-speed="1000" data-stop="1">0</span>
                            </div>
                            <div class="counter-text" data-i18n="funfact.restaurant">Ресторант</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    </main>

        
<!-- ░░░ CONTACT SECTION ░░░ -->
<section class="contact-section">
    <div class="bg bg-image wow reveal-top tm-gsap-img-parallax overflow-hidden">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/contact-us.jpg" alt="Image">
    </div>

    <div class="icon icon-contact-shape1"
         style="background-image:url(.<?php echo get_template_directory_uri(); ?>/assets/Website/images/PINO/images/map.jpg)"></div>

    <div class="auto-container">
        <div class="outer-box">
            <div class="row">

                <!-- ▸ Form column -->
                <div class="form-column col-lg-8 offset-lg-4 col-md-12 col-sm-12">
                    <div class="inner-column">
                        <div class="contact-form wow fadeInLeft">
                            <div class="icon-anchor-1 bounce-y"></div>

                            <div class="sec-title">
                                <span class="sub-title style-three"
                                      data-i18n="contact.subtitle">Контакти</span>
                                <h2 data-i18n="contact.title">Свържете се с нас</h2>
                            </div>

                            <form method="post" action="<?php echo home_url('/contact'); ?>">
                                

                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6">
                                        <input name="FormName"
                                               placeholder="Име и Фамилия"
                                               data-i18n="contact.placeholder.name"
                                               required />
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <input name="FormEmail" type="email"
                                               placeholder="Имейл"
                                               data-i18n="contact.placeholder.email"
                                               required />
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <input name="FormSubject"
                                               placeholder="Тема"
                                               data-i18n="contact.placeholder.subject"
                                               required />
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <input name="FormPhone"
                                               placeholder="Телефон"
                                               data-i18n="contact.placeholder.phone"
                                               required />
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <textarea name="FormMessage" rows="2"
                                                  placeholder="Вашето съобщение..."
                                                  data-i18n="contact.placeholder.message"></textarea>
                                    </div>

                                    <!-- reCAPTCHA -->
                                    <div class="form-group col-lg-12">
                                        <div class="g-recaptcha"
                                             data-sitekey="6LeZMxQrAAAAAEGRdRcgjZrEzdxzUU36HHn2Fs8B"></div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <button type="submit" class="theme-btn btn-style-one">
                                            <span class="btn-title"
                                                  data-i18n="contact.send">Изпрати запитване</span>
                                        </button>
                                    </div>
                                </div>
                            <input name="__RequestVerificationToken" type="hidden" value="CfDJ8Kz235z0FTtHtg8Gwh883uFas9lNMxX9Q4u92WntLLDqJacGi2ogCoyhPYu6P3-9ZAfkBWHOHOjrBX4cl21lrKarhKDzUMtJjSoCMvxluzS6pjIrV5iTWInCY3f4R9uLTjR_u9J1NS3do-OMdZVHA4w" /></form>
                        </div><!-- /.contact-form -->

                        <!-- ▸ Call-us block -->
                        <div class="contact-block">
                            <div class="inner-box">
                                <img class="image icon-contact1" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/icon-call-2.png" alt="Image">

                                <div class="content-box">
                                    <img class="icon-img" src="<?php echo get_template_directory_uri(); ?>/assets/Website/images/icons/icon-call-1.png" alt="">

                                    <span class="text" data-i18n="contact.callUs">Обадете ни се</span>
                                    <a class="text-two" href="tel:+359885185008">+359&nbsp;88&nbsp;51&nbsp;85&nbsp;008</a>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.inner-column -->
                </div><!-- /.form-column -->

            </div>
        </div>
    </div>
</section>
<!-- ░░░ END CONTACT SECTION ░░░ -->


<?php get_footer(); ?>
