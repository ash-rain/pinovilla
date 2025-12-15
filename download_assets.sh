#!/bin/bash

BASE_URL="https://pinovilla.com"
THEME_DIR="wp-content/themes/pinovilla"

# List of assets
ASSETS=(
"/assets/Website/css/availability.css"
"/assets/Website/css/bootstrap.min.css"
"/assets/Website/css/cookies.css"
"/assets/Website/css/events-carousel.css"
"/assets/Website/css/flatpickr.min.css"
"/assets/Website/css/pino-reservation.css"
"/assets/Website/css/pino.css"
"/assets/Website/css/slick-theme.css"
"/assets/Website/css/slick.css"
"/assets/Website/css/style.css"
"/assets/Website/images/icons/bg-shape2.png"
"/assets/Website/images/icons/cookies-icon.svg"
"/assets/Website/images/icons/icon-call-1.png"
"/assets/Website/images/icons/icon-call-2.png"
"/assets/Website/images/icons/icon-home1.png"
"/assets/Website/images/icons/shape-5.png"
"/assets/Website/images/icons/testi-shape1.png"
"/assets/Website/images/PINO/Icons/prawn.svg"
"/assets/Website/images/PINO/Icons/sunrise.svg"
"/assets/Website/images/PINO/images/apartment.jpeg"
"/assets/Website/images/PINO/images/code_mode_logo.svg"
"/assets/Website/images/PINO/images/conference-room-1.jpg"
"/assets/Website/images/PINO/images/conference-room-2.jpg"
"/assets/Website/images/PINO/images/conference-room-3.jpg"
"/assets/Website/images/PINO/images/contact-us.jpg"
"/assets/Website/images/PINO/images/deluxe-room.png"
"/assets/Website/images/PINO/images/double.jpeg"
"/assets/Website/images/PINO/images/economic-double.jpeg"
"/assets/Website/images/PINO/images/hero.jpg"
"/assets/Website/images/PINO/images/hotel-outside.jpg"
"/assets/Website/images/PINO/images/hotel.jpg"
"/assets/Website/images/PINO/images/map.jpg"
"/assets/Website/images/PINO/images/pool.png"
"/assets/Website/images/PINO/images/restaurant-1.jpg"
"/assets/Website/images/PINO/images/restaurant-card.jpg"
"/assets/Website/images/PINO/images/superior-room.png"
"/assets/Website/images/PINO/images/villa-index-1.jpg"
"/assets/Website/images/PINO/images/villa-index-2.jpg"
"/assets/Website/images/PINO/images/villa-main.jpg"
"/assets/Website/images/PINO/images/villa-pool.jpg"
"/assets/Website/images/PINO/images/villa.jpg"
"/assets/Website/images/PINO/LOGO/logo-text-only-gold.png"
"/assets/Website/images/PINO/LOGO/white.jpg"
"/assets/Website/js/appear.js"
"/assets/Website/js/bootstrap.min.js"
"/assets/Website/js/conference-room.js"
"/assets/Website/js/events-carousel.js"
"/assets/Website/js/gsap.min.js"
"/assets/Website/js/jquery.fancybox.js"
"/assets/Website/js/jquery.js"
"/assets/Website/js/mixitup.js"
"/assets/Website/js/popper.min.js"
"/assets/Website/js/script-gsap.js"
"/assets/Website/js/script.js"
"/assets/Website/js/ScrollTrigger.min.js"
"/assets/Website/js/slick-animation.min.js"
"/assets/Website/js/slick.min.js"
"/assets/Website/js/SplitText.min.js"
"/assets/Website/js/splitType.js"
"/assets/Website/js/swiper.min.js"
"/assets/Website/js/translate.js"
"/assets/Website/js/wow.js"
)

for asset in "${ASSETS[@]}"; do
    # Remove query strings
    clean_asset=$(echo "$asset" | cut -d? -f1)
    
    # Create directory
    dir=$(dirname "$THEME_DIR$clean_asset")
    mkdir -p "$dir"
    
    # Download
    echo "Downloading $BASE_URL$asset to $THEME_DIR$clean_asset"
    curl -L -s "$BASE_URL$asset" -o "$THEME_DIR$clean_asset"
done
