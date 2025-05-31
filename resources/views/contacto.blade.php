<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>

    <!-- ICONO PESTAÑA -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilocontacto.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick-theme.css">
</head>

<body>
    <!-- LOGOS -->
    <div class="logos">
        <div class="left-logos">
            <a href="https://www.sep.gob.mx" target="_blank"><img src="{{ asset('imagenes/sep.png') }}" alt="Imagen 1"
                    class="sep"></a>
            <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/tecnm.png') }}" alt="Imagen 2"
                    class="tecnm"></a>
            <a href="https://leon.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/itl.png') }}" alt="Imagen 3"
                    class="itl"></a>
        </div>
        <a href="{{ route('iniciarsesion') }}"><img src="{{ asset('imagenes/ao.png') }}" alt="Imagen 4"
                class="logo"></a>
    </div>


    <!-- MENÚ -->
    <header class="header">
        <nav class="nav">
            <ul class="nav-links" id="navLinks">
                <?php
                $menu_items = [
                    'Inicio' => route('iniciarsesion'),
                    'Acerca de' => route('acercade'),
                    'Manual Usuario' => route('manualusuario'),
                    'Contacto' => route('contacto')
                ];
                foreach ($menu_items as $item => $link) {
                    echo "<li><a href='$link'>$item</a></li>";
                }
                ?>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>
        </nav>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="contact-carousel-container">
        <div class="support-container">
            <h2>Contacto y Soporte</h2>
            <p>En <strong>ACADEMIC ORGANIZER</strong>, estamos aquí para ayudarte. Si tienes alguna pregunta o necesitas
                asistencia, puedes comunicarte con nosotros a través de nuestro correo electrónico o seguirnos en
                nuestras redes sociales para mantenerte actualizado. Nuestro equipo de soporte estará encantado de
                atenderte.</p>
            <p>¡Gracias por elegirnos!</p>
        </div>

        <!-- CARRUSEL -->
        <div class="carousel">
            <div><img src="{{ asset('imagenes/carrusel/contacto1.png') }}" alt="Soporte en acción"></div>
            <div><img src="{{ asset('imagenes/carrusel/contacto2.png') }}" alt="Atención personalizada"></div>
            <div><img src="{{ asset('imagenes/carrusel/contacto3.png') }}" alt="Soluciones rápidas"></div>
        </div>
    </div>

    <!-- OPCIONES DE CONTACTO - DEBAJO DEL CONTENEDOR -->
    <div class="contact-options">
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=academicorganizersoporte@gmail.com" target="_blank">
            <i class="fas fa-envelope" title="Correo"></i>
        </a>
        <a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp" title="WhatsApp"></i></a>
        <a href="https://www.facebook.com/AcademicOrganizer" target="_blank"><i class="fab fa-facebook"
                title="Facebook"></i></a>
        <a href="https://twitter.com/AcademicOrg" target="_blank"><i class="fab fa-twitter" title="Twitter"></i></a>
        <a href="https://www.instagram.com/AcademicOrganizer" target="_blank"><i class="fab fa-instagram"
                title="Instagram"></i></a>
    </div>



    <!-- FOOTER -->
    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Información de Contacto</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> Teléfono: +34 123456789</li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        Email:
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=academicorganizersoporte@gmail.com" target="_blank" rel="noopener noreferrer">
                            academicorganizersoporte@gmail.com
                        </a>
                    </li>
                    <li><i class="fas fa-map-marker-alt"></i> Dirección: Calle Ejemplo, 123</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Enlaces</h3>
                <ul>
                    <li><a href="{{ route('iniciarsesion') }}">Inicio</a></li>
                    <li><a href="{{ route('acercade') }}">Acerca de</a></li>
                    <li><a href="{{ route('manualusuario') }}">Manual Usuario</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Redes Sociales</h3>
                <ul>
                    <li><a href="https://www.facebook.com/AcademicOrganizer" target="_blank"><i
                                class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="https://twitter.com/AcademicOrg" target="_blank"><i class="fab fa-twitter"></i>
                            Twitter</a></li>
                    <li><a href="https://www.instagram.com/AcademicOrganizer" target="_blank"><i
                                class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 ACADEMIC ORGANIZER. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel/slick/slick.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
    $(document).ready(function() {
        $('.carousel').slick({
            autoplay: true,
            dots: true,
            arrows: false,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear'
        });
    });
    </script>
</body>

</html>