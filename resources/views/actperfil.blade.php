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
    <title>Actualizar Perfil</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilos4.css') }}" rel="stylesheet" type="text/css">
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
                    <button class="logout-btn">
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
        <div class="contperfil">
            <h2 class="tituloact">Actualizar Perfil</h2>
            <form action="{{ route('actualizarPerfil') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Foto de Perfil -->
                <div class="form-group">
                    <label for="foto_perfil">Foto de Perfil Actual:</label>
                    <div class="current-photo">
                        @if(session('foto_perfil'))
                        <img src="{{ asset('storage/' . session('foto_perfil')) }}" alt="Foto de perfil actual"
                            class="profile-photo">
                        @else
                        <img src="{{ asset('imagenes/perfil/foto.jpg') }}" alt="Foto de perfil por defecto"
                            class="profile-photo">
                        @endif
                    </div>
                    <label for="foto_perfil">Actualizar Foto de Perfil:</label>
                    <div class="input-icon">
                        <i class="fas fa-camera icon"></i>
                        <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" disabled>
                        <button type="button" class="update-btn" id="update_foto_perfil" title="Actualizar">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                    @error('foto_perfil')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Agrupación de campos en dos columnas -->
                <div class="form-row">
                    <!-- Nombre -->
                    <div class="form-group half-width">
                        <label for="nombre">Nombre:</label>
                        <div class="input-icon">
                            <i class="fas fa-user icon"></i>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', session('nombre')) }}"
                                disabled>
                            <button type="button" class="update-btn" id="update_nombre" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @if ($errors->has('nombre'))
                        <span class="text-danger">{{ $errors->first('nombre') }}</span>
                        @endif
                    </div>

                    <!-- Apellido -->
                    <div class="form-group half-width">
                        <label for="apellido">Apellido:</label>
                        <div class="input-icon">
                            <i class="fas fa-user icon"></i>
                            <input type="text" name="apellido" id="apellido"
                                value="{{ old('apellido', session('apellido')) }}" disabled>
                            <button type="button" class="update-btn" id="update_apellido" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @if ($errors->has('apellido'))
                        <span class="text-danger">{{ $errors->first('apellido') }}</span>
                        @endif
                    </div>

                    <!-- Usuario -->
                    <div class="form-group half-width">
                        <label for="nombre_usuario">Usuario:</label>
                        <div class="input-icon">
                            <i class="fas fa-user-circle icon"></i>
                            <input type="text" name="nombre_usuario" id="nombre_usuario"
                                value="{{ old('nombre_usuario', session('nombre_usuario')) }}" disabled>
                            <button type="button" class="update-btn" id="update_nombre_usuario" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @if ($errors->has('nombre_usuario'))
                        <span class="text-danger">{{ $errors->first('nombre_usuario') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <!-- Correo Electrónico -->
                    <div class="form-group half-width">
                        <label for="correo_electronico">Correo Electrónico:</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email" name="correo_electronico" id="correo_electronico"
                                value="{{ old('correo_electronico', session('correo_electronico')) }}" disabled>
                            <button type="button" class="update-btn" id="update_correo_electronico" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @error('correo_electronico')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group half-width">
                        <label for="contraseña">Contraseña:</label>
                        <div class="input-icon">
                            <i class="fas fa-lock icon"></i>
                            <input type="password" name="contraseña" id="contraseña" disabled>
                            <button type="button" class="update-btn" id="update_contraseña" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Ícono de visibilidad dentro del input -->
                            <i class="fas fa-eye-slash visibility-icon" id="eye-icon"
                                onclick="togglePasswordVisibility('contraseña')"></i>
                        </div>
                        @error('contraseña')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div class="form-group half-width">
                        <label for="contraseña_confirmation">Confirmar Contraseña:</label>
                        <div class="input-icon">
                            <i class="fas fa-lock icon"></i>
                            <input type="password" name="contraseña_confirmation" id="contraseña_confirmation" disabled>
                            <button type="button" class="update-btn" id="update_confirmar_contraseña"
                                title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Ícono de visibilidad dentro del input -->
                            <i class="fas fa-eye-slash visibility-icon" id="eye-icon-confirm"
                                onclick="togglePasswordVisibility('contraseña_confirmation')"></i>
                        </div>
                        @error('contraseña_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="form-row">
                    <!-- Fecha de Nacimiento -->
                    <div class="form-group half-width">
                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                        <div class="input-icon">
                            <i class="fas fa-calendar icon"></i>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento', session('fecha_nacimiento')) }}" disabled>
                            <button type="button" class="update-btn" id="update_fecha_nacimiento" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @error('fecha_nacimiento')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Género -->
                    <div class="form-group half-width">
                        <label for="genero">Género:</label>
                        <div class="input-icon">
                            <i class="fas fa-venus-mars icon"></i>
                            <select name="genero" id="genero" disabled>
                                <option value="Masculino"
                                    {{ old('genero', session('genero')) == 'Masculino' ? 'selected' : '' }}>Masculino
                                </option>
                                <option value="Femenino"
                                    {{ old('genero', session('genero')) == 'Femenino' ? 'selected' : '' }}>Femenino
                                </option>
                                <option value="Otro" {{ old('genero', session('genero')) == 'Otro' ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <button type="button" class="update-btn" id="update_genero" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @error('genero')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="form-group half-width">
                        <label for="telefono">Teléfono:</label>
                        <div class="input-icon">
                            <i class="fas fa-phone icon"></i>
                            <input type="text" name="telefono" id="telefono"
                                value="{{ old('telefono', session('telefono')) }}" disabled>
                            <button type="button" class="update-btn" id="update_telefono" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        <!-- Mostrar el error debajo del campo -->
                        @error('telefono')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Contenedor para centrar el botón -->
                <div class="boton-centrado">
                    <button type="submit">Actualizar</button>
                </div>
            </form>

            @if (session('success'))
            <p class="success-message">{{ session('success') }}</p>
            @endif
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
    <script src="{{ asset('js/actperfilu.js') }}"></script>

    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth

</body>

</html>