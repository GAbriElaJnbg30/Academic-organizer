<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Cuenta</title>

    <!----------------------------------- ICONO PESTAÑA -------------------------------------->
    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <!---------------------------------------- CSS ------------------------------------------->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilosrecuperarcont.css') }}" rel="stylesheet" type="text/css">
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
    <div class="container">
        <h2>Recuperar Cuenta</h2>
        <div class="input-container">
            <form action="{{ route('recuperar.cuenta') }}" method="POST">
                @csrf
                <div class="input-container">
                    <input type="email" name="email" placeholder="Ingrese su correo electrónico" id="email" value="{{ old('email') }}" required>
                    <i class="fas fa-envelope icon-email"></i> <!-- Icono de correo -->
                </div>

                <!-- Mostrar mensaje de error si el correo no está registrado -->
                @if ($errors->has('email'))
                    <div class="error-message">
                        <p>{{ $errors->first('email') }}</p>
                    </div>
                @endif

                <!-- Mostrar mensaje de éxito -->
                @if (session('success'))
                    <div class="success-message">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <button type="submit">Enviar</button>
            </form>
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

</body>
</html>