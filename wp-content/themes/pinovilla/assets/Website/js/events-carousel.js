// wwwroot/assets/js/events-carousel.js
document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.querySelector('.events-carousel');
    if (!carousel) return;

    const track  = carousel.querySelector('.events-carousel-track');
    const slides = Array.from(carousel.querySelectorAll('.events-slide'));

    if (!track || slides.length === 0) return;

    const prevBtn = carousel.querySelector('.events-carousel-arrow.prev');
    const nextBtn = carousel.querySelector('.events-carousel-arrow.next');
    const dots    = Array.from(carousel.querySelectorAll('.events-dot'));

    const hasMultiple = slides.length > 1;
    let currentIndex  = 0;
    let autoTimer     = null;
    const AUTO_DELAY  = 5000; // 5 seconds

    /* ----------  responsive images (desktop vs mobile) ---------- */
    function updateImageSources() {
        const isDesktop = window.innerWidth >= 768;

        slides.forEach(slide => {
            const img = slide.querySelector('.events-slide-image');
            if (!img) return;

            const desktopSrc = img.dataset.desktop;
            const mobileSrc  = img.dataset.mobile;
            const desiredSrc = isDesktop ? desktopSrc : mobileSrc;

            if (desiredSrc && img.getAttribute('src') !== desiredSrc) {
                img.setAttribute('src', desiredSrc);
            }
        });
    }

    window.addEventListener('resize', updateImageSources);
    updateImageSources();

    /* ----------  if only one slide, no arrows/dots/auto ---------- */
    if (!hasMultiple) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        dots.forEach(d => d.style.display = 'none');
        return;
    }

    /* ----------  slide switching ---------- */
    function setActiveSlide(index) {
        slides.forEach((slide, i) => {
            const isActive = (i === index);
            slide.classList.toggle('active', isActive);

            // simple fade animation hook
            if (isActive) {
                slide.classList.add('fade');
                setTimeout(() => slide.classList.remove('fade'), 400);
            }
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });

        currentIndex = index;
    }

    function goToSlide(index) {
        const total    = slides.length;
        const newIndex = (index + total) % total;
        setActiveSlide(newIndex);
    }

    /* ----------  autoplay ---------- */
    function startAuto() {
        stopAuto();
        autoTimer = setInterval(function () {
            goToSlide(currentIndex + 1);
        }, AUTO_DELAY);
    }

    function stopAuto() {
        if (autoTimer) {
            clearInterval(autoTimer);
            autoTimer = null;
        }
    }

    /* ----------  controls ---------- */
    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            stopAuto();
            goToSlide(currentIndex - 1);
            startAuto();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            stopAuto();
            goToSlide(currentIndex + 1);
            startAuto();
        });
    }

    dots.forEach(dot => {
        dot.addEventListener('click', function () {
            const targetIndex = parseInt(dot.dataset.index, 10);
            if (isNaN(targetIndex)) return;

            stopAuto();
            goToSlide(targetIndex);
            startAuto();
        });
    });

    // start!
    setActiveSlide(0);
    startAuto();
});
