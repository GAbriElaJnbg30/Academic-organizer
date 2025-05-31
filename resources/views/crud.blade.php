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
    <title>CRUD</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilosadmin.css') }}" rel="stylesheet" type="text/css">

    <!-- Cargar Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!------------------------------------------------------- CONTENIDO --------------------------------------------------------->
    <div class="main-content" id="mainContent">
        <div class="contcrud" id="contcrud">
            <!-- Fila de opciones: botón "+ Nuevo", barra de búsqueda y filtro -->
            <div class="options-row">
                <button class="btn-new" id="openModal">+ Nuevo</button>

                <div class="search-bar">
                    <form method="GET" action="{{ route('usuarios.index') }}">
                        <input type="text" name="buscar" placeholder="Buscar Usuario" id="search-input"
                            value="{{ request('buscar') }}">
                        <button type="submit" class="search-button" title="Buscar"><i
                                class="fas fa-search"></i></button>
                        <!-- Botón Resetear -->
                        <button type="button" class="reset-button" title="Limpiar" onclick="resetSearch()"><i
                                class="fas fa-eraser"></i></button>
                    </form>
                </div>

                <div class="filter">
                    <form method="GET" action="{{ route('usuarios.index') }}">
                        <label for="order-by">Ordenar por:</label>
                        <!-- Añadir el valor de búsqueda en un campo oculto para mantenerlo en la URL -->
                        <input type="hidden" name="buscar" value="{{ request('buscar') }}">
                        <select id="order-by" name="order_by" onchange="this.form.submit()" class="form-select">
                            <!-- Opciones de ordenación -->
                            <option value="id_usuario" {{ request('order_by') == 'id_usuario' ? 'selected' : '' }}>ID
                            </option>
                            <option value="nombre" {{ request('order_by') == 'nombre' ? 'selected' : '' }}>Nombre
                            </option>
                            <option value="apellido" {{ request('order_by') == 'apellido' ? 'selected' : '' }}>Apellido
                            </option>
                            <option value="nombre_usuario"
                                {{ request('order_by') == 'nombre_usuario' ? 'selected' : '' }}>Nombre de Usuario
                            </option>
                            <option value="correo_electronico"
                                {{ request('order_by') == 'correo_electronico' ? 'selected' : '' }}>Correo Electrónico
                            </option>
                            <option value="telefono" {{ request('order_by') == 'telefono' ? 'selected' : '' }}>Teléfono
                            </option>
                        </select>
                    </form>
                </div>


            </div>

            <!-- Lista de Usuarios -->
            <h2>Lista de Usuarios</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rol</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Nombre de Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Teléfono</th>
                        <!--<th>Foto de Perfil</th>-->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if($usuarios->isEmpty())
                    <tr>
                        <td colspan="10" style="text-align: center;">No se encontraron resultados para tu búsqueda.</td>
                    </tr>
                    @else
                    @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->apellido }}</td>
                        <td>{{ $usuario->nombre_usuario }}</td>
                        <td>{{ $usuario->correo_electronico }}</td>
                        <td>{{ $usuario->fecha_nacimiento }}</td>
                        <td>{{ $usuario->genero }}</td>
                        <td>{{ $usuario->telefono }}</td>
                        <td>
                            <!--------------------------------- Icono para Actualizar ------------------------------------->
                            <button onclick="showUpdateUserModal({
                                        id_usuario: '{{ $usuario->id_usuario }}',
                                        nombre: '{{ $usuario->nombre }}',
                                        apellido: '{{ $usuario->apellido }}',
                                        nombre_usuario: '{{ $usuario->nombre_usuario }}',
                                        correo_electronico: '{{ $usuario->correo_electronico }}',
                                        fecha_nacimiento: '{{ $usuario->fecha_nacimiento }}',
                                        genero: '{{ $usuario->genero }}',
                                        telefono: '{{ $usuario->telefono }}',
                                        rol: '{{ $usuario->rol }}'
                                    })" title="Actualizar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!----------------------------------- Icono para Eliminar ------------------------------------->
                            <form action="{{ route('usuarios.eliminar', $usuario->id_usuario) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar a {{ $usuario->nombre }} {{ $usuario->apellido }}?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar" class="delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>


            <div class="paginacion">
                <ul class="pagination">
                    @if ($usuarios->onFirstPage())
                    <li class="disabled">
                        <i class="fas fa-angle-double-left"></i>
                    </li>
                    @else
                    <li>
                        <a href="{{ $usuarios->previousPageUrl() }}">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    @endif

                    @foreach ($usuarios->getUrlRange(1, $usuarios->lastPage()) as $page => $url)
                    @if ($page == $usuarios->currentPage())
                    <li class="active"><a href="#">{{ $page }}</a></li>
                    @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                    @endforeach

                    @if ($usuarios->hasMorePages())
                    <li>
                        <a href="{{ $usuarios->nextPageUrl() }}">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                    @else
                    <li class="disabled">
                        <i class="fas fa-angle-double-right"></i>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!--------------------------------------------------- ALTA DE USUARIO ---------------------------------------------------->
        <div id="registrationModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Registro de Usuario</h2>
                <div id="generalError"></div>
                <form id="registrationForm" method="POST" action="{{ route('crud') }}">
                    @csrf
                    <div class="grid-container">
                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user"></i>
                                <label for="">Nombre:</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
                                    placeholder="Ingrese su(s) nombre(s)" required>
                            </div>
                            @error('nombre')
                            <div class="error">{{ $message }}</div>
                            @enderror
                            <span class="error" id="nombre-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user"></i>
                                <label for="">Apellido:</label>
                                <input type="text" id="apellido" name="apellido" placeholder="Ingrese su(s) apellido(s)"
                                    required>
                            </div>
                            <span class="error" id="apellido-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user-circle"></i>
                                <label for="">Usuario:</label>
                                <input type="text" id="nombre_usuario" name="nombre_usuario"
                                    placeholder="Nombre de usuario" required>
                            </div>
                            <span class="error" id="nombre_usuario-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <label for="">Correo:</label>
                                <input type="email" id="correo_electronico" name="correo_electronico"
                                    placeholder="ejemplo@correo.com" required>
                            </div>
                            <span class="error" id="correo_electronico-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <label for="">Contraseña:</label>
                                <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña"
                                    required maxlength="15">
                                <i class="fa fa-eye-slash" id="togglePassword"></i> <!-- Icono de visibilidad -->
                            </div>
                            <span class="error" id="contraseña-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <label for="">Confirmar contraseña:</label>
                                <input type="password" id="contraseña_confirmation" name="contraseña_confirmation"
                                    placeholder="Confirmar contraseña" required maxlength="15">
                                <i class="fa fa-eye-slash" id="togglePasswordConfirmation"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-calendar"></i>
                                <label for="">Fecha de Nacimiento:</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                            </div>
                            <span class="error" id="fecha_nacimiento-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-venus-mars"></i>
                                <label for="">Género:</label>
                                <select id="genero" name="genero" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <span class="error" id="genero-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-phone"></i>
                                <label for="">Teléfono:</label>
                                <input type="tel" id="telefono" name="telefono" placeholder="Número de teléfono"
                                    required maxlength="15">
                            </div>
                            <span class="error" id="telefono-error"></span>
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user-tag"></i> <!-- Ícono de rol -->
                                <label for="">Rol:</label>
                                <select id="rol" name="rol" required>
                                    <option value="" disabled selected>Seleccione un rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Usuario General</option>
                                </select>
                            </div>
                            <span class="error" id="rol-error"></span>
                        </div>

                    </div>
                    <button type="submit" class="btn">Crear Cuenta</button>
                </form>
            </div>
        </div>


        <!------------------------------------------ Actualizar Información del Usuario ----------------------------------------->
        <div id="updateUserModal" class="modal {{ $errors->any() ? 'open' : '' }}">
            <div class="modal-content">

                <span class="close" onclick="closeModalAndClearErrors()">&times;</span>

                <h2>Editar Usuario</h2>
                <form id="updateUserForm" method="POST" action="{{ route('actualizar') }}"
                    onsubmit="handleSubmit(event)">
                    @csrf
                    @method('PUT')

                    <div class="grid-container">
                        <!-- Campo oculto para el ID del usuario -->
                        <input type="hidden" id="edit-id_usuario" name="id_usuario"
                            value="{{ old('id_usuario', $usuario->id_usuario ?? '') }}">

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user"></i>
                                <label for="edit-nombre">Nombre:</label>
                                <input type="text" id="edit-nombre" name="nombre" value="{{ old('nombre') }}" required
                                    readonly>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-nombre')"></i>
                            </div>
                            @if ($errors->has('nombre'))
                            <div class="error">{{ $errors->first('nombre') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user"></i>
                                <label for="edit-apellido">Apellido:</label>
                                <input type="text" id="edit-apellido" name="apellido" value="{{ old('apellido') }}"
                                    required readonly>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-apellido')"></i>
                            </div>
                            @if ($errors->has('apellido'))
                            <div class="error">{{ $errors->first('apellido') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user-circle"></i>
                                <label for="edit-nombre_usuario">Nombre de Usuario:</label>
                                <input type="text" id="edit-nombre_usuario" name="nombre_usuario"
                                    value="{{ old('nombre_usuario') }}" required readonly>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-nombre_usuario')"></i>
                            </div>
                            @if ($errors->has('nombre_usuario'))
                            <div class="error">{{ $errors->first('nombre_usuario') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <label for="edit-correo">Correo Electrónico:</label>
                                <input type="email" id="edit-correo" name="correo_electronico"
                                    value="{{ old('correo_electronico') }}" required readonly>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-correo')"></i>
                            </div>
                            @if ($errors->has('correo_electronico'))
                            <div class="error">{{ $errors->first('correo_electronico') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <label for="edit-contraseña">Contraseña:</label>
                                <input type="password" id="edit-contraseña" name="contraseña"
                                    value="{{ old('contraseña') }}" placeholder="Contraseña" readonly maxlength="15">
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-contraseña')"></i>
                                <i class="fa fa-eye-slash visibility-icon" id="toggleEditPassword"
                                    onclick="togglePasswordVisibility('edit-contraseña', 'toggleEditPassword')"></i>
                                <!-- Ícono para visibilidad -->
                            </div>
                            @if ($errors->has('contraseña'))
                            <div class="error">{{ $errors->first('contraseña') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-lock"></i>
                                <label for="edit-contraseña_confirmation">Confirmar Contraseña:</label>
                                <input type="password" id="edit-contraseña_confirmation" name="contraseña_confirmation"
                                    value="{{ old('contraseña_confirmation') }}" placeholder="Confirmar contraseña"
                                    readonly maxlength="15">
                                <i class="fa fa-edit edit-icon"
                                    onclick="toggleReadonly('edit-contraseña_confirmation')"></i>
                                <i class="fa fa-eye-slash visibility-icon" id="toggleEditPasswordConfirmation"
                                    onclick="togglePasswordVisibility('edit-contraseña_confirmation', 'toggleEditPasswordConfirmation')"></i>
                                <!-- Ícono para visibilidad -->
                            </div>
                            @if ($errors->has('contraseña_confirmation'))
                            <div class="error">{{ $errors->first('contraseña_confirmation') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-calendar"></i>
                                <label for="edit-fecha_nacimiento">Fecha de Nacimiento:</label>
                                <input type="date" id="edit-fecha_nacimiento" name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento') }}" required readonly>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-fecha_nacimiento')"></i>
                            </div>
                            @if ($errors->has('fecha_nacimiento'))
                            <div class="error">{{ $errors->first('fecha_nacimiento') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-venus-mars"></i>
                                <label for="edit-genero">Género:</label>
                                <select id="edit-genero" name="genero" value="{{ old('genero') }}" required
                                    class="readonly">
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-genero', 'select')"></i>
                            </div>
                            @if ($errors->has('genero'))
                            <div class="error">{{ $errors->first('genero') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-phone"></i>
                                <label for="edit-telefono">Teléfono:</label>
                                <input type="text" id="edit-telefono" name="telefono" value="{{ old('telefono') }}"
                                    required readonly maxlength="15">
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-telefono')"></i>
                            </div>
                            @if ($errors->has('telefono'))
                            <div class="error">{{ $errors->first('telefono') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="input-icon">
                                <i class="fa fa-user-tag"></i> <!-- Ícono de rol -->
                                <label for="edit-rol">Rol:</label>
                                <select id="edit-rol" name="rol" value="{{ old('rol') }}" required class="readonly">
                                    <option value="{{ old('rol') }}">Selecciona un rol</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">UGeneral</option>
                                </select>
                                <i class="fa fa-edit edit-icon" onclick="toggleReadonly('edit-rol', 'select')"></i>
                            </div>
                            @if ($errors->has('rol'))
                            <div class="error">{{ $errors->first('rol') }}</div>
                            @endif
                        </div>

                    </div>
                    <button type="submit" class="btn">Actualizar</button>
                </form>
            </div>
        </div>

    </div>

    <!--------------------------------------------------------- FOOTER ----------------------------------------------------------->
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
    <script src="{{ asset('js/crud.js') }}"></script>
    <script>
    @else
    <script>
        window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth
    // ----------------------------------------- Manejar cuando hay errores
    ---------------------------------------------
    document.addEventListener('DOMContentLoaded', function() {
    @if($errors -> any())
    document.getElementById('updateUserModal').style.display = 'block';
    @endif
    });

    // Función para cerrar el modal y limpiar errores
    function closeModalAndClearErrors() {
    const modal = document.getElementById('updateUserModal');
    modal.style.display = 'none';

    // Limpia los errores visuales
    const errorElements = modal.querySelectorAll('.error');
    errorElements.forEach(errorElement => {
    errorElement.textContent = ''; // Elimina los mensajes de error
    });

    // Limpia cualquier borde de error en los campos
    const inputFields = modal.querySelectorAll('input, select');
    inputFields.forEach(field => {
    field.classList.remove('input-error'); // Clase opcional para resaltar errores
    });
    }


    /* -------------------- HABILITAR CAMPO EN ACTUALIZAR USUrio -------------------------- */
    function toggleReadonly(fieldId, type = 'input') {
    const field = document.getElementById(fieldId);

    if (type === 'input') {
    // Para campos input
    if (field.hasAttribute('readonly')) {
    field.removeAttribute('readonly'); // Habilita edición
    field.focus(); // Enfoca el campo
    } else {
    field.setAttribute('readonly', true); // Vuelve a ser solo lectura
    }
    } else if (type === 'select') {
    // Para selects
    if (field.classList.contains('readonly')) {
    field.classList.remove('readonly'); // Habilita interacción
    } else {
    field.classList.add('readonly'); // Bloquea interacción
    }
    }
    }
    </script>
</body>

</html>