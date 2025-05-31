<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

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
    <div class="contprinregistro">
        <div class="contregistro">

            <form action="{{ route('registro') }}" method="POST" class="registro-form">
                @csrf
                <h2>Registro</h2>

                <!-- Cero fila -->
                <div class="input-row">
                    <div class="input-registro {{ $errors->has('nombre') ? 'error' : '' }}">
                        <i class="fa fa-user icon"></i>
                        <input type="text" id="nombre" value="{{ old('nombre') }}" name="nombre" placeholder="Ingrese su(s) nombre(s)" required>
                        @error('nombre')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>
                    
                    <div class="input-registro {{ $errors->has('apellido') ? 'error' : '' }}">
                        <i class="fa fa-user icon"></i>
                        <input type="text" id="apellido" value="{{ old('apellido') }}" name="apellido" placeholder="Ingrese su(s) apellido(s)" required>
                        @error('apellido')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>
                </div>

                <!-- Primera fila -->
                <div class="input-row">
                    <div class="input-registro {{ $errors->has('nombre_usuario') ? 'error' : '' }}">
                        <i class="fa fa-user-circle icon"></i>
                        <input type="text" id="nombre_usuario" value="{{ old('nombre_usuario') }}" name="nombre_usuario" placeholder="Ingrese su usuario" required>
                            @error('nombre_usuario')
                                <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                            @enderror
                    </div>

                    <div class="input-registro {{ $errors->has('correo_electronico') ? 'error' : '' }}">
                        <i class="fa fa-envelope icon"></i>
                        <input type="email" id="correo_electronico" value="{{ old('correo_electronico') }}" name="correo_electronico" placeholder="Ingrese su correo" required>
                            @error('correo_electronico')
                                <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                            @enderror
                    </div>
                </div>

                <!-- Segunda fila -->
                <div class="input-row">
                    <div class="input-registro {{ $errors->has('contraseña') ? 'error' : '' }}">
                        <i class="fa fa-lock icon"></i>
                        <input type="password" id="contraseña" value="{{ old('contraseña') }}" name="contraseña" placeholder="Ingrese su contraseña" required maxlength="15">
                        <i class="fa fa-eye-slash" id="togglePassword" style="cursor: pointer; right: 0.5rem; transform: translateY(-50%); font-size: 13px; color:#05016e"></i>
                        @error('contraseña')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>

                    <div class="input-registro {{ $errors->has('contraseña_confirmation') ? 'error' : '' }}">
                        <i class="fa fa-lock icon"></i>
                        <input type="password" id="contraseña_confirmation" value="{{ old('contraseña_confirmation') }}" name="contraseña_confirmation" placeholder="Confirmar contraseña" required maxlength="15">
                        <i class="fa fa-eye-slash" id="toggleConfirmPassword" style="cursor: pointer; right: 0.5rem; transform: translateY(-50%); font-size: 13px; color:#05016e"></i>
                        @error('contraseña_confirmation')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>
                </div>

                <!-- Tercera fila -->
                <div class="input-row">
                    <div class="input-registro {{ $errors->has('fecha_nacimiento') ? 'error' : '' }}">
                        <i class="fa fa-birthday-cake icon"></i>
                        <input type="date" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" name="fecha_nacimiento" placeholder="Ingrese su fecha de nacimiento" required>
                        @error('fecha_nacimiento')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>

                    <div class="input-registro {{ $errors->has('genero') ? 'error' : '' }}">
                        <i class="fa fa-venus-mars icon"></i>
                        <select id="genero" value="{{ old('genero') }}" name="genero" required>
                            <option value="" disabled {{ old('genero') == null ? 'selected' : '' }}>Seleccione su género</option>
                            <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('genero') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('genero')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>
                </div>

                <!-- Cuarta fila (Fecha de nacimiento) -->
                <div class="input-row">
                    <div class="input-registro {{ $errors->has('telefono') ? 'error' : '' }}">
                        <i class="fa fa-phone icon"></i>
                        <input type="tel" id="telefono" value="{{ old('telefono') }}" name="telefono" placeholder="Ingrese su teléfono" required maxlength="15">
                        @error('telefono')
                            <div class="error">{{ $message }}</div> <!-- Mostrar mensaje de error debajo del campo -->
                        @enderror
                    </div>
                    <div class="input-registro">
                    </div>
                </div>

                <div class="botones">
                    <button type="submit" class="btn crear-cuenta">Crear cuenta</button>
                    <button type="button" class="btn cancelar" onclick="cancelarFormulario()">Cancelar</button>
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