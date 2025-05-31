@php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
@endphp

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilosmanual.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>

<body>
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

    <div class="menu-bar">
        <div class="hamburger-menu" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>

        @auth
        <div class="datos">
            <span class="datos_usuario">{{ session('nombre') }} {{ session('apellido') }}</span>
            <div class="user-info">
                <!-- Mostrar la foto de perfil, si existe -->
                @if(session('foto_perfil'))
                <a href="{{ route('actperfil') }}">
                    <img src="{{ asset('storage/' . session('foto_perfil')) }}" class="user-photo" id="user-photo">
                </a>
                @else
                <!-- Si no tiene foto de perfil, mostrar una imagen por defecto o el logo -->
                <a href="{{ route('actperfil') }}">
                    <img src="{{ asset('imagenes/perfil/foto.jpg') }}" class="user-photo" id="user-photo">
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmarCierreSesion()">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt logout-icon"></i>
                        Cerrar Sesión
                    </button>
                </form>

                <script>
                function confirmarCierreSesion() {
                    return confirm('¿Estás seguro de que deseas cerrar sesión?');
                }
                </script>

            </div>
        </div>
    </div>

    <div class="side-menu" id="sideMenu">
        <a href="{{ route('abienvenida') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('crud') }}"><i class="fas fa-user-edit"></i> CRUD</a>
        <a href="{{ route('actividades') }}"><i class="fas fa-file-alt"></i> Reporte De Actividades</a>
        <a href="{{ route('actperfil') }}"><i class="fas fa-user"></i> Perfil</a>
    </div>

    <div class="main-content" id="mainContent">
        <div class="vista-manual">
            <!-- Contenedor Uno: Bienvenida y PDF -->
            <div class="contenedor-uno">
                <!-- Bienvenida -->
                <div class="titulo-manual">¡Hola, <span class="nombre-usuario"> {{ session('nombre') }}</span>! 👋</div>

                <div class="subtitulo-manual">Nos alegra verte aquí</div>

                <!-- Imagen de avatar o usuario -->
                <div class="avatar-usuario">
                    @if(session('foto_perfil'))
                    <img src="{{ asset('storage/' . session('foto_perfil')) }}" alt="Foto de perfil del usuario">
                    @else
                    <img src="{{ asset('imagenes/perfil/foto.jpg') }}" alt="Foto de perfil del usuario"
                        class="user-photo" id="user-photo">
                    @endif
                </div>

                <!-- Mensaje motivacional -->
                <div class="mensaje-bienvenida">
                    "El conocimiento es poder. Sigue aprendiendo y alcanzando tus metas."
                </div>

                <!-- Botón para abrir el PDF -->
                <button onclick="window.open('{{ route('pdf-viewer') }}', '_blank');" class="btn-manualpdf">
                    <i class="fas fa-file-pdf iconpdf"></i> Ver Manual en PDF
                </button>
            </div>

            <!-- Contenedor Dos: Video -->
            <div class="contenedor-dos">
                <div class="titulo-manual">
                    <i class="fas fa-video icon"></i> Vídeo Manual de Usuario
                </div>

                <video id="videoTutorial" controls class="video-tutorial">
                    <source src="<?php echo asset('videos/ros.mp4'); ?>" type="video/mp4">
                    Tu navegador no soporta el elemento de video.
                </video>

                <a href="<?php echo asset('videos/rose.mp4'); ?>" download="Tutorial_Video.mp4"
                    class="btn-secondary btn-descargar">
                    <i class="fas fa-download icon"></i> Descargar Video
                </a>
            </div>
        </div>
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
    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth

</body>

</html>