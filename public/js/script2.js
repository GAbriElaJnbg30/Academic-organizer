// ---------------------------------------- Lo del Carrusel de Imágenes del Acerca De ------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const carrusel = document.querySelectorAll('.slider-section'); // Selecciona cada sección individual
    const btnLeft = document.querySelector('.btn-left');
    const btnRight = document.querySelector('.btn-right');

    let index = 0; // Empezamos en la primera imagen (índice 0)
    const totalSlides = carrusel.length;
    let autoSlideInterval;

    // Función para mostrar la imagen actual y ocultar las demás
    const updateCarousel = () => {
        carrusel.forEach((slide, i) => {
            slide.style.display = i === index ? 'block' : 'none'; // Solo muestra la imagen actual
        });
    };

    const moveToNext = () => {
        index = (index + 1) % totalSlides; // Incrementa el índice de manera circular
        updateCarousel();
    };

    const moveToPrev = () => {
        index = (index - 1 + totalSlides) % totalSlides; // Decrementa el índice de manera circular
        updateCarousel();
    };

    // Evento de clic en las flechas
    btnRight.addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Pausa el auto-slide temporalmente
        moveToNext();
        autoSlide(); // Reinicia el auto-slide inmediatamente
    });

    btnLeft.addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Pausa el auto-slide temporalmente
        moveToPrev();
        autoSlide(); // Reinicia el auto-slide inmediatamente
    });

    // Función de auto-slide
    const autoSlide = () => {
        clearInterval(autoSlideInterval); // Asegúrate de limpiar cualquier intervalo previo
        autoSlideInterval = setInterval(() => {
            moveToNext();
        }, 5000); // Cambia cada 5 segundos
    };

    // Inicializa el carrusel
    updateCarousel(); // Asegúrate de que solo se muestre la primera imagen
    autoSlide(); // Inicia el auto-slide
});