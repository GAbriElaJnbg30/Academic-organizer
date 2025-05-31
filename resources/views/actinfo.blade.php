<?php
// Aquí puedes agregar la lógica para obtener la información del usuario
$nombre = "Juan";
$apellido = "Pérez";
$estado_emoji = "😊";
$foto_url = "imagenes/icono.png"; // Reemplaza con la ruta real de la foto del usuario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida</title>

    <!----------------------------------- ICONO PESTAÑA -------------------------------------->
    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <!------------------------------------- CSS MENÚ ----------------------------------------->
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilos4.css') }}" rel="stylesheet" type="text/css">
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
        
    <!--------------------------------------------- No Se Mueve -------------------------------------->
    <div class="juntos">
        <!-------------------------------------------- MENÚ ------------------------------------------->
        <nav class="menu-bar">
            <div class="menu-hamburger" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>

            <!-- Contenedor de los logos pequeños -->
            <div class="small-logos">
                <img src="{{ asset('imagenes/itl.png') }}" alt="Logo ITL"></a>
                <img src="{{ asset('imagenes/logo_solo.png') }}" alt="Logo AO"></a>
            </div>

            <div class="user-info">
                <div>
                    <span class="estado" id="estado-emoji"><?php echo $estado_emoji; ?></span>
                </div>
                <span class="datos_usuario"><?php echo $nombre . ' ' . $apellido; ?></span>
                <a href="{{ route('actperfil') }}"><img src="{{ asset('imagenes/logo_solo.png') }}" class="user-photo" id="user-photo"></a>

                <button class="logout-btn" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </button>
            </div>
        </nav>

        <div id="menu-items" class="menu-items">
            <a href="{{ route('abienvenida') }}">Inicio</a>
            <a href="crud.php">CRUD</a>
            <a href="roles-y-permisos.php">Roles y permisos</a>
            <a href="reporte-de-actividades.php">Reporte de actividades</a>
            <a href="{{ route('actinfo') }}">Actualizar Perfil</a>
            <a href="{{ route('pantalla') }}">Pantalla</a>
        </div>
    </div>


    <div class="contenido-bienvenida">
        <div class="bienvenida">
            @csrf
            <h2>Actualizar Perfil</h2>

            <h1>Frase desglosada en letras</h1>
            <ul>
                <?php
                $frase = "Holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"; // Frase a recorrer
                for ($i = 0; $i < strlen($frase); $i++) {
                    echo "<li>" . $frase[$i] . "</li>";
                }
                ?>
            </ul>
        </div>
    </div>


    <!-------------------------------------------------------- FOOTER ----------------------------------------------------------->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Información de Contacto</h3>
                <ul>
                    <li><a href="#">Teléfono: +34 123456789</a></li>
                    <li><a href="#">Email: info@example.com</a></li>
                    <li><a href="#">Dirección: Calle Ejemplo, 123</a></li>
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
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 ACADEMIC ORGANIZER. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menuItems = document.getElementById('menu-items');
            menuItems.classList.toggle('show');
        }

        function cerrarSesion() {
            // Aquí puedes agregar la lógica para cerrar sesión
            alert('Cerrando sesión...');
            // Redirigir al usuario a la página de inicio de sesión o realizar otras acciones necesarias
        }

        document.addEventListener("DOMContentLoaded", function () {
            const menuBar = document.querySelector(".menu-bar");

            window.addEventListener("scroll", function () {
                // Si el usuario ha hecho scroll suficiente para que el menú esté sticky
                if (window.scrollY > menuBar.offsetTop) {
                    menuBar.classList.add("sticky");
                } else {
                    menuBar.classList.remove("sticky");
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const userPhoto = document.getElementById('user-photo');
            const userMenu = userPhoto.closest('.user-menu');

            userPhoto.addEventListener('click', function () {
                userMenu.classList.toggle('active');
            });

            // Cerrar el menú si se hace clic fuera de él
            document.addEventListener('click', function (event) {
                if (!userMenu.contains(event.target)) {
                    userMenu.classList.remove('active');
                }
            });
        });

    </script>
</body>
</html>