<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Organizer</title>

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
    <div class="contprincipal">
        <!--------------------------------------------- CONTENEDOR IZQUIERDA -------------------------------------------------->
        <div class="contizquierda">
            <!------------------------------------------- IMAGEN LOGO -------------------------------------------------------->
            <div class="imagenlogo">
                <img src="{{ asset('imagenes/ao.png') }}" alt="Imagen 5" class="logo">
            </div>

            <!-------------------------------------------- TEXTO LOGO -------------------------------------------------------->
            <div class="textologo">
                <p class="eslogan1">EL ÉXITO ACADÉMICO ESTÁ A SÓLO UN CLIC</p>
                <p class="eslogan2">ORGANIZA, ESTUDIA Y BRILLA</p>
            </div>
        </div>

        <!---------------------------------------------- CONTENEDOR LOGIN -------------------------------------------------->
        <div class="contlogin">
            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf
                <h2>Iniciar Sesión</h2>

                <div class="form-group">
                    <div class="input-container">
                        <i class="fa fa-user-circle icon"></i>
                        <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingrese su usuario" 
                        value="{{ old('nombre_usuario', '') }}" 
                        class="{{ $errors->has('nombre_usuario') ? 'error' : '' }}" required>  
                    </div>
                    <!-- Mostrar error si el campo nombre_usuario tiene errores -->
                    @if($errors->has('nombre_usuario'))
                        <div class="alert alert-danger">
                            {{ $errors->first('nombre_usuario') }}
                        </div>
                    @endif
                </div>
                
                <div class="form-group">
                    <div class="input-container">
                        <i class="fa fa-lock icon"></i>
                        <input type="password" id="contraseña" name="contraseña" placeholder="Ingrese su contraseña" class="{{ $errors->has('contraseña') ? 'error' : '' }}" required maxlength="15">
                        <!-- Ícono de visibilidad de la contraseña -->
                        <i class="fa fa-eye-slash" id="togglePassword" style="cursor: pointer; position: absolute; right: 3.2rem; top: 55%; transform: translateY(-50%); font-size: 13px; color:#05016e"></i>
                    </div>
                    <!-- Mostrar error si el campo contraseña tiene errores -->
                    @if($errors->has('contraseña'))
                        <div class="alert alert-danger">
                            {{ $errors->first('contraseña') }}
                        </div>
                    @endif
                </div>
                
                <button class="botonlogin" type="submit">Iniciar sesión</button>
                
                <div class="form-footer">
                    <p><a href="{{ route('olvidoc') }}">¿Olvidó su contraseña?</a></p>
                    <p><span>¿No tiene una cuenta? <a href="{{ route('registro') }}">Regístrese</a></span></p>
                </div>
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