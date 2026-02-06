document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carousel-slides');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-btn.next');
    const prevButton = document.querySelector('.carousel-btn.prev');
    const dotsNav = document.querySelector('.carousel-indicators');
    const dots = Array.from(dotsNav.children);

    let currentIndex = 0;
    let autoPlayInterval;

    // Mise à jour de la position des slides
    const updateSlidePosition = (index) => {
        track.style.transform = 'translateX(-' + (index * 100) + '%)';

        // Update dots
        const currentDot = dotsNav.querySelector('.active');
        if (currentDot) currentDot.classList.remove('active');
        dots[index].classList.add('active');

        currentIndex = index;
    };

    // Navigation Suivante
    const moveToNextSlide = () => {
        let nextIndex = currentIndex + 1;
        if (nextIndex >= slides.length) {
            nextIndex = 0; // Boucle au début
        }
        updateSlidePosition(nextIndex);
    };

    // Navigation Précédente
    const moveToPrevSlide = () => {
        let prevIndex = currentIndex - 1;
        if (prevIndex < 0) {
            prevIndex = slides.length - 1; // Boucle à la fin
        }
        updateSlidePosition(prevIndex);
    };

    // Event Listeners des boutons
    nextButton.addEventListener('click', () => {
        moveToNextSlide();
        resetAutoPlay();
    });

    prevButton.addEventListener('click', () => {
        moveToPrevSlide();
        resetAutoPlay();
    });

    // Event Listeners des indicateurs
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            updateSlidePosition(index);
            resetAutoPlay();
        });
    });

    // Auto Play
    const startAutoPlay = () => {
        autoPlayInterval = setInterval(moveToNextSlide, 5000); // 5 secondes
    };

    const stopAutoPlay = () => {
        clearInterval(autoPlayInterval);
    };

    const resetAutoPlay = () => {
        stopAutoPlay();
        startAutoPlay();
    };

    // Pause au survol
    const container = document.querySelector('.carousel-container');
    container.addEventListener('mouseenter', stopAutoPlay);
    container.addEventListener('mouseleave', startAutoPlay);

    // Initialisation
    startAutoPlay();
});
