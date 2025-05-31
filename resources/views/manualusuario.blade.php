<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual De Usuario</title>

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
    <!------------------------------------------------- CONTENEDOR PRINCIPAL -------------------------------------------------->
    <div class="vista-manual">
        <div class="contenedor-uno">
            <div class="titulo-manual">Manual de Usuario</div>

            <div class="informacion-manual">
                <p id="texto-manual">
                    El manual de usuario contiene toda la información necesaria para entender cómo usar este sistema. 
                    Aquí encontrarás una guía paso a paso sobre cómo acceder a las distintas funcionalidades, incluyendo cómo registrarte, 
                    iniciar sesión y navegar por las diferentes secciones del sitio. 
                    <span id="puntos">...</span>
                    <span id="texto-completo" style="display: none;">
                        También ofrece detalles sobre cómo personalizar tu experiencia y resolver problemas básicos.
                        Este documento está diseñado para usuarios de todos los niveles, desde principiantes hasta avanzados. Contiene 
                        explicaciones claras, imágenes ilustrativas y ejemplos prácticos que te ayudarán a comprender cómo aprovechar al
                        máximo las herramientas ofrecidas por el sistema. Además, se incluyen enlaces a recursos adicionales, como videos 
                        tutoriales y documentación técnica, para aquellos que deseen explorar más a fondo.
                    </span>
                    <a href="javascript:void(0);" id="enlace-leer-mas" onclick="mostrarTexto()">Leer más...</a>
                </p>
            </div>

            <img src="/sitioweb/public/imagenes/manualito.jpeg" alt="manual" class="img-manual" id="imagen-manual"><br>

            <button onclick="window.open('{{ route('pdf-viewer') }}', '_blank');" class="btn-manualpdf" alt="ver manual">
                <i class="fas fa-file-pdf iconpdf"></i> Ver PDF
            </button>
        </div>

        <!-- Contenedor Dos: Video -->
        <div class="contenedor-dos">
            <div class="titulo-manual">
                <i class="fas fa-video icon"></i> Vídeo Manual de Usuario
            </div>

            <video id="videoTutorial" controls class="video-tutorial">
                <source src="<?php echo asset('videos/tutoria.mp4'); ?>" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>

            <a href="<?php echo asset('videos/tutorial.mp4'); ?>" download="Tutorial_Video.mp4" class="btn-secondary btn-descargar">
                <i class="fas fa-download icon"></i> Descargar Video
            </a>
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
        function mostrarTexto() {
            const puntos = document.getElementById("puntos");
            const textoCompleto = document.getElementById("texto-completo");
            const enlace = document.getElementById("enlace-leer-mas");
            const imagen = document.getElementById("imagen-manual");
            const btnPdf = document.querySelector(".btn-manualpdf"); // Botón PDF

            if (textoCompleto.style.display === "none") {
                // Mostrar el texto completo y ocultar la imagen
                textoCompleto.style.display = "inline";
                puntos.style.display = "none";
                enlace.innerHTML = "Leer menos...";
                imagen.style.display = "none"; // Oculta la imagen
                btnPdf.classList.add("ajustar-margen"); // Agrega margen superior al botón PDF
            } else {
                // Ocultar el texto completo y mostrar la imagen
                textoCompleto.style.display = "none";
                puntos.style.display = "inline";
                enlace.innerHTML = "Leer más...";
                imagen.style.display = "block"; // Muestra la imagen
                btnPdf.classList.remove("ajustar-margen"); // Quita margen superior del botón PDF
            }
        }

    </script>
</body>
</html>