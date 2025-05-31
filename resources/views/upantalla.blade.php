<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="logos">
        <div class="left-logos">
            <a href="https://www.sep.gob.mx" target="_blank"><img src="{{ asset('imagenes/sep.png') }}" alt="Imagen 1" class="sep"></a>
            <a href="https://www.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/tecnm.png') }}" alt="Imagen 2" class="tecnm"></a>
            <a href="https://leon.tecnm.mx" target="_blank"><img src="{{ asset('imagenes/itl.png') }}" alt="Imagen 3" class="itl"></a>
        </div>
        <a href="{{ route('iniciarsesion') }}"><img src="{{ asset('imagenes/ao.png') }}" alt="Imagen 4" class="logo"></a>
    </div>
        
    <div class="menu-bar">
        <div class="hamburger-menu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        
        <div class="datos">
            <span class="datos_usuario">{{ session('nombre') }} {{ session('apellido') }}</span>
            <div class="user-info">
                <!-- Mostrar la foto de perfil, si existe -->
                @if(session('foto_perfil'))
                    <a href="{{ route('uactperfil') }}">
                        <img src="{{ asset('storage/' . session('foto_perfil')) }}" class="user-photo" id="user-photo">
                    </a>
                @else
                    <!-- Si no tiene foto de perfil, mostrar una imagen por defecto o el logo -->
                    <a href="{{ route('uactperfil') }}">
                        <img src="{{ asset('imagenes/perfil/foto.jpg') }}" class="user-photo" id="user-photo">
                    </a>
                @endif

                <button class="logout-btn" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt logout-icon"></i>
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <a href="{{ route('ubienvenida') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('archivos') }}"><i class="fas fa-folder-open"></i> Gestión De Archivos</a>
        <a href="{{ route('notas') }}"><i class="fas fa-tasks"></i> Bloc De Notas</a>
        <a href="{{ route('recordatorios') }}"><i class="fas fa-bell"></i> Recordatorios</a>
        <a href="{{ route('uactperfil') }}"><i class="fas fa-user"></i> Perfil</a>
        <a href="{{ route('upantalla') }}"><i class="fas fa-fill-drip"></i> Pantalla</a>
    </div>

    <div class="main-content" id="mainContent">
        <?php
            $veces = 10;
            for ($i = 0; $i < $veces; $i++) {
                echo "<p>Bienvenido a Academic Organizer</p>";
            }
        ?>
    </div>

    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Información de Contacto</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-phone"></i> Teléfono: +34 123456789</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> Email: info@example.com</a></li>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Dirección: Calle Ejemplo, 123</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Enlaces</h3>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Redes Sociales</h3>
                <ul>
                    <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 ACADEMIC ORGANIZER. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!----------------------------------------------------------- JS ------------------------------------------------------------->
    <script src="{{ asset('js/menus.js') }}"></script>
</body>
</html>