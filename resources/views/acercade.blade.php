<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca De</title>

    <!----------------------------------- ICONO PESTAÑA -------------------------------------->
    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <!---------------------------------------- CSS ------------------------------------------->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilos2.css') }}" rel="stylesheet" type="text/css">
    <!----------------------------------- FONTS ICONOS --------------------------------------->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-------------------------------------------- LOGOS -----------------------------------------> 
    <div class="logos">
        <div class="left-logos">
            <a href="https://www.sep.gob.mx" target="_blank"><img src="{{ asset('imagenes/sep.png') }}" alt="Imagen 1" class="sep"></a>
            <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/tecnm.png') }}" alt="Imagen 2" class="tecnm"></a>
            <a href="https://leon.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/itl.png') }}" alt="Imagen 3" class="itl"></a>
        </div>
        <a href="{{ route('iniciarsesion') }}"><img src="{{ asset('imagenes/ao.png') }}" alt="Imagen 4" class="logo"></a>
    </div>

    <!--------------------------------------------- MENÚ ----------------------------------------->
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
                    echo "<li><a href=\"$link\">$item</a></li>";
                }
                ?>
            </ul>
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>
    <!----------------------------------------------------------- JS ------------------------------------------------------------->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/script2.js') }}"></script>
    <!------------------------------------------------- CONTENEDOR PRINCIPAL -------------------------------------------------->
    <div class="contenedor-acercade">
        <div class="cont-carrusel">
            <div class="carrusel">
                <!-- Imágenes originales -->
                <div class="slider-section">
                    <img src="{{ asset('imagenes/carrusel/ao_1.jpeg') }}" alt="Imagen 1">
                </div>
                <div class="slider-section">
                    <img src="{{ asset('imagenes/carrusel/ao_2.jpeg') }}" alt="Imagen 2">
                </div>
                <div class="slider-section">
                    <img src="{{ asset('imagenes/carrusel/ao_3.jpeg') }}" alt="Imagen 3">
                </div>
            </div>
            <button class="btn-left">‹</button>
            <button class="btn-right">›</button>
        </div>

        <div class="contenedor-texto-acecade text-center">
            <h1>¡Conoce ACADEMIC ORGANIZER!</h1>
            <p>Si quieres información para conocer acerca de nuestro sitio web, da clic sobre las imágenes.</p>
        </div>


        <div class="container">
            <div class="card" onclick="this.classList.toggle('flipped')">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>¿Qué es Academic Organizer?</h2>
                        <img src="{{ asset('imagenes/que.webp') }}" alt="Icono Uso" class="card-icon">
                    </div>
                    <div class="card-back">
                        <p><strong>Academic Organizer</strong> es una herramienta organizativa diseñada específicamente para estudiantes de licenciatura. Su objetivo es proporcionar un espacio centralizado y accesible que les permita gestionar sus notas, documentos, tareas y recordatorios de manera eficiente.</p>
                    </div>
                </div>
            </div>

            <div class="card" onclick="this.classList.toggle('flipped')">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>¿Para qué sirve?</h2>
                        <img src="{{ asset('imagenes/paraque.webp') }}" alt="Icono Uso" class="card-icon">
                    </div>
                    <div class="card-back">
                        <p><strong>Academic Organizer</strong> sirve para optimizar la gestión del tiempo y la organización académica de los estudiantes. Facilita el acceso y almacenamiento de información relevante, permitiendo una planificación más estructurada de las actividades escolares. Con esta herramienta, los estudiantes pueden mantener un mejor control de sus tareas y compromisos, asegurando que no pasen por alto fechas importantes y optimizando su rendimiento académico.</p>
                    </div>
                </div>
            </div>

            <div class="card" onclick="this.classList.toggle('flipped')">
                <div class="card-inner">
                    <div class="card-front">
                        <h2>Beneficios</h2>
                        <img src="{{ asset('imagenes/beneficios.webp') }}" alt="Icono Uso" class="card-icon">
                    </div>
                    <div class="card-back">
                        <ul>
                            <li><strong>Gestión de carpetas personalizadas: </strong> Los usuarios pueden crear, modificar y organizar carpetas según sus necesidades académicas.</li><br>
                            <li><strong>Bloc de notas digital: </strong>Herramienta para tomar y guardar notas directamente en la plataforma.</li><br>
                            <li><strong>Recordatorios automáticos: </strong>Notificaciones integradas para recordar tareas y fechas importantes.</li><br>
                            <li><strong>Registro y Perfiles de usuario: </strong>Cada estudiante puede registrarse y personalizar su perfil para un uso más intuitivo.</li><br>
                            <li><strong>Compartir información: </strong>Posibilidad de compartir notas o carpetas con otros usuarios para facilitar el trabajo en equipo.</li><br>
                            <li><strong>Diseño accesible e intuitivo</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-------------------------------------------------------- FOOTER ----------------------------------------------------------->
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

    <script>
       
    </script>
    
</body>
</html>