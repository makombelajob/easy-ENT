function slideInterval() {

    // Récupère l'élément avec l'id 'carousel' dans le DOM
    const slide = document.querySelector('#carousel');

    // Initialise un carrousel Bootstrap sur cet élément
    const carousel = new bootstrap.Carousel(slide);

    // Lance un intervalle pour passer automatiquement à la diapositive suivante toutes les 3 secondes
    let interval = setInterval(() => {
        carousel.next();
    }, 3000);

    // Met en pause le carrousel lorsque la souris passe dessus
    slide.addEventListener("mouseenter", function () {
        clearInterval(interval);
    });

    // Relance le carrousel lorsque la souris quitte l'élément
    slide.addEventListener("mouseleave", function () {
        interval = setInterval(() => {
            carousel.next();
        }, 3000);
    });
}
slideInterval();