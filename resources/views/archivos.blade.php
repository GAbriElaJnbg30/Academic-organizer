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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Gestión De Archivos</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilosgestion.css') }}" rel="stylesheet" type="text/css">
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
        <a href="{{ route('ubienvenida') }}"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('archivos') }}"><i class="fas fa-folder-open"></i> Gestión De Archivos</a>
        <a href="{{ route('notas') }}"><i class="fas fa-tasks"></i> Bloc De Notas</a>
        <a href="{{ route('recordatorios') }}"><i class="fas fa-bell"></i> Recordatorios</a>
        <a href="{{ route('uactperfil') }}"><i class="fas fa-user"></i> Perfil</a>
    </div>

    <!----------------------------------------------- CONTENIDO ------------------------------------------------>
    <div class="main-content" id="mainContent">
        <div class="principal-gestion">
            <!-- Inicio -->
            <aside class="sidebar">
                <h2><i class="fas fa-folder"></i> Organizer</h2>
                <nav>
                    <ul>
                        <li><a href="{{ route('archivos') }}"><i class="fas fa-home"></i> Inicio</a></li>
                        <li>
                            <!-- Contenedor para alinear "Mis Carpetas" y el toggle -->




                        </li>
                    </ul>

                    <!-- Lista de carpetas principales oculta inicialmente -->
                    <ul class="lista-carpetas" id="listaCarpetas">
                        @foreach($carpetas as $carpeta)
                        <li>
                            <div class="mis-carpetas-header">
                                <a href="{{ route('verContenido', ['id' => $carpeta->id_carpeta]) }}">
                                    <i class="fas fa-folder"></i> {{ $carpeta->nombre_carpeta }}
                                </a>
                                <button class="toggle-btn" data-id="{{ $carpeta->id_carpeta }}">
                                    <i class="fas fa-caret-down"></i>
                                </button>
                            </div>

                            @if(isset($subcarpetas[$carpeta->id_carpeta]))
                            <ul class="subcarpetas hide" id="subcarpetas-{{ $carpeta->id_carpeta }}">
                                @foreach($subcarpetas[$carpeta->id_carpeta] as $sub)
                                <li>
                                    <a href="{{ route('verContenido', ['id' => $sub->id_carpeta]) }}">
                                        <i class="fas fa-folder"></i> {{ $sub->nombre_carpeta }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach

                    </ul>

                </nav>
            </aside>




            <main class="main-content-dos">
                <div class="action-row">
                    <!-- Barra de búsqueda -->
                    <div class="search-bar">
                        <input type="text" id="busquedaCarpeta" placeholder="Buscar archivo o carpeta">
                        <button type="button" id="btnBuscarCarpeta"><i class="fas fa-search"></i></button>
                    </div>

                    <!-- Botones de crear y subir -->
                    <div class="file-actions">
                        <button type="button" id="crear-carpeta-btn"><i class="fas fa-folder-plus"></i> Crear
                            Carpeta</button>
                        <button type="button" id="subir-archivo-btn" class="btn">
                            <i class="fas fa-file-upload"></i> Subir Archivo
                        </button>
                    </div>
                </div>

                <!-- Input de archivo, oculto inicialmente -->
                <form action="{{ route('archivos.subir') }}" method="POST" enctype="multipart/form-data"
                    id="formSubirArchivos">
                    @csrf
                    <input type="hidden" name="id_carpeta" id="id-carpeta">
                    <input type="file" name="archivos[]" id="archivo" multiple style="display: none;">
                </form>




                <div class="action-row">
                    <!-- Selector de barra y mosaico -->
                    <div class="view-options">
                        <div class="custom-select">
                            <div class="selected-option">
                                <i class="fas fa-th-large"></i>
                                <span>Mosaico</span>
                                <i class="fas fa-chevron-down arrow"></i>
                            </div>
                            <ul class="options">
                                <li class="option" data-value="lista">
                                    <i class="fas fa-list"></i>
                                    <span> Lista</span>
                                </li>
                                <li class="option active" data-value="mosaico">
                                    <i class="fas fa-th-large"></i>
                                    <span> Mosaico</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="file-selection">
                        <button class="btn-seleccionar-archivos">
                            <i class="fas fa-check-square"></i>Seleccionar Archivos
                        </button>
                        <span id="selected-count">0</span> seleccionados
                    </div>

                    <div class="action-icons">
                        <button type="button" id="btnCompartirZip" title="Compartir">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        <button type="button" id="btn-descargar-zip" title="Descargar">
                            <i class="fas fa-download"></i>
                        </button>


                        <button type="button" title="Eliminar" onclick="enviarFormularioEliminar()">
                            <i class="fas fa-trash-alt"></i>
                        </button>


                        <button type="button" id="btnMoverCarpetas" title="Mover"><i
                                class="fas fa-exchange-alt"></i></button>

                        <button type="button" title="Editar nombre" id="btn-abrir-editar"><i
                                class="fas fa-edit"></i></button>

                    </div>
                </div>

                <!---------------------------------- Muestra los Archivos ------------------------------>
                <!-- Contenedor para las carpetas -->
                <div class="file-display">
                    <h3><i class="fas fa-folder-open"></i> Mis Archivos</h3>
                    <!-- div id="mensaje-seleccion" style="display:none;" -- > <-- /div -->

                    <div class="contenedor-carpetas mosaico">
                        <!-- Mostrar el mensaje solo si no hay carpetas -->
                        @if($carpetas->isEmpty())
                        <div id="mensajeSinCarpetas" class="mensaje-sin-carpetas">
                            Aún no hay carpetas creadas.
                        </div>
                        @else
                        <div class="vista-mosaico">
                            <!-- Vista en mosaico -->
                            @foreach($carpetas as $carpeta)
                            @if(is_null($carpeta->parent_id))
                            <div class="carpeta mosaico" data-id_carpeta="{{ $carpeta->id_carpeta }}">

                                <input type="checkbox" class="checkbox-carpeta" name="carpetasSeleccionadas[]"
                                    value="{{ $carpeta->id_carpeta }}" style="display:none;">

                                <div class="icono-carpeta mosaico">
                                    <i class="fas fa-folder-open icono-folder"></i>
                                </div>
                                <div class="nombre-carpeta mosaico">
                                    {{ $carpeta->nombre_carpeta }}
                                </div>
                                <div class="fecha-creacion mosaico" style="display: none;">
                                    {{ $carpeta->fecha_creacion }}
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <div class="vista-lista">
                            <!-- Vista en lista -->
                            <div class="lista-carpetas">
                                @foreach($carpetas as $carpeta)
                                @if(is_null($carpeta->parent_id))
                                <div class="fila-carpeta" data-id_carpeta="{{ $carpeta->id_carpeta }}">
                                    <input type="checkbox" class="checkbox-carpeta lista" name="carpetasSeleccionadas[]"
                                    value="{{ $carpeta->id_carpeta }}" style="display:none;">
                                    <div class="icono-carpeta lista">
                                        <i class="fas fa-folder-open"></i> <!-- Icono de carpeta -->
                                    </div>
                                    <div class="detalles-carpeta">
                                        <div class="nombre-carpeta lista">
                                            {{ $carpeta->nombre_carpeta }}
                                        </div>
                                        <div class="fecha-creacion">
                                            {{ $carpeta->fecha_creacion }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                </div>


            </main>
        </div>

        <!-- Modal para Crear Carpeta -->
        <div id="modal-crear" class="modal" style="display: none;"
            data-errors-crear="{{ $errors->any() ? 'true' : 'false' }}">
            <div class="modal-content">
                <h2>Crear Nueva Carpeta</h2>
                <form method="POST" action="{{ route('carpetas.crear') }}">
                    @csrf
                    <div class="input-group">
                        <i class="fas fa-folder"></i>
                        <input type="text" id="folder-name" name="nombre_carpeta" placeholder="Nombre de la carpeta"
                            value="{{ old('nombre_carpeta') }}">
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('nombre_carpeta') }}
                    </div>
                    @endif
                    <div class="modal-buttons">
                        <button id="crear-btn" class="guardar">Crear</button>
                        <button id="cancelar-btn" class="cancelar">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulario oculto para eliminar -->
        <form id="formEliminarCarpetas" action="{{ route('carpetas.eliminar') }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <div id="contenedorInputsSeleccionados"></div>
        </form>

        <!-- Input oculto para seleccionar archivos -->
        <input type="file" id="archivo" name="archivos[]" style="display: none;" multiple>

        <!-- Modal para Editar Nombre -->
        <div id="modal-editar-nombre" class="modal" style="display: none;"
            data-errors-editar="{{ $errors->any() ? 'true' : 'false' }}">
            <div class="modal-content">
                <h2>Editar Nombre de la Carpeta</h2>
                <form action="{{ route('carpeta.actualizar') }}" method="POST" id="form-editar-nombre">
                    @csrf
                    @method('PUT')
                    <!-- PUT para actualización -->
                    <input type="hidden" id="id-carpeta" name="id_carpeta"> <!-- Campo oculto para el ID -->
                    <div class="input-group">
                        <i class="fas fa-folder"></i>
                        <input type="text" id="nuevo-nombre" name="nuevo_nombre"
                            placeholder="Nuevo nombre de la carpeta" value="{{ old('nuevo_nombre') }}" required>
                    </div>
                    @if ($errors->has('nuevo_nombre'))
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('nuevo_nombre') }}
                    </div>
                    @endif
                    <div class="modal-buttons">
                        <button type="submit" id="actualizar-btn" class="guardar">Actualizar</button>
                        <button type="button" id="cancelar-editar-btn" class="cancelar">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para Mover -->
        <div id="modalMover" class="modal" style="display:none;">
            <div class="modal-content">
                <h4>Seleccionar carpeta destino</h4>
                <form id="formMoverCarpetas" method="POST" action="{{ route('carpetas.mover') }}">
                    @csrf
                    <input type="hidden" name="carpetas_seleccionadas" id="carpetasSeleccionadasInput">
                    <label for="carpeta_destino">Mover a:</label>
                    <select name="carpeta_destino" required>
                        <option value="">Selecciona una carpeta</option>
                        @foreach($carpetas as $carpeta)
                        <option value="{{ $carpeta->id_carpeta }}">{{ $carpeta->nombre_carpeta }}</option>
                        @foreach($carpeta->subcarpetas as $subcarpeta)
                        <!-- Iterar subcarpetas -->
                        <option value="{{ $subcarpeta->id_carpeta }}">- {{ $subcarpeta->nombre_carpeta }}</option>
                        @endforeach
                        @endforeach
                    </select>
                    @if ($errors->has('carpeta_destino'))
                    <div class="error text-red-500">{{ $errors->first('carpeta_destino') }}</div>
                    @endif
                    <br><br>
                    <button type="submit" class="guardar">Mover</button>
                    <button type="button" class="cancelar" onclick="cerrarModal()">Cancelar</button>
                </form>
            </div>
        </div>

    </div>

    <!------------------------------------------------- FOOTER ------------------------------------------------>
    <footer class="footer" id="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Información de Contacto</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> Teléfono: +34 123456789</li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        Email:
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=academicorganizersoporte@gmail.com"
                            target="_blank" rel="noopener noreferrer">
                            academicorganizersoporte@gmail.com
                        </a>
                    </li>
                    <li><i class="fas fa-map-marker-alt"></i> Dirección: Calle Ejemplo, 123</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Enlaces</h3>
                <ul>
                    <li><a href="{{ route('iniciarsesion') }}" target="_blank">Inicio</a></li>
                    <li><a href="{{ route('acercade') }}" target="_blank">Acerca de</a></li>
                    <li><a href="{{ route('manualusuario') }}" target="_blank">Manual Usuario</a></li>
                    <li><a href="{{ route('contacto') }}" target="_blank">Contacto</a></li>
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

    <!----------------------------------------------------------- JS ------------------------------------------------------------->
    <script src="{{ asset('js/menus.js') }}"></script>
    <script src="{{ asset('js/carpetas.js') }}"></script>
    <script src="{{ asset('js/archivosCrud.js') }}"></script>
    <script src="{{ asset('js/toggleBtn.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // Toggle Botón
    document.addEventListener('DOMContentLoaded', function() {
        const botones = document.querySelectorAll('.toggle-btn');

        botones.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const lista = document.getElementById(`subcarpetas-${id}`);

                if (lista) {
                    lista.classList.toggle('hide');
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-caret-down');
                    icon.classList.toggle('fa-caret-right');
                }
            });
        });
    });


    // ------------------------------- descargar carpeta -----------------------------------------
    // Verificación de que jQuery se cargó correctamente
    console.log("jQuery cargado y funcionando");

    $(document).on('click', '#btn-descargar-zip', function() {
        let seleccionadas = [];

        // Obtenemos las carpetas seleccionadas
        $('.checkbox-carpeta:checked').each(function() {
            seleccionadas.push($(this).val());
        });

        console.log("IDs seleccionadas: ", seleccionadas);

        // Verificamos si se seleccionaron carpetas
        if (seleccionadas.length === 0) {
            alert('Por favor selecciona al menos una carpeta.');
            return;
        }

        // Construimos la URL correctamente
        let url = "{{ url('/descargar-zip') }}?carpetas=" + seleccionadas.join(',');

        // Redirigimos al usuario a la URL generada para descargar el ZIP
        window.location.href = url;
    });


    // -------------------------- Compartir ------------------------------------
    document.getElementById("btnCompartirZip").addEventListener("click", function() {
        const gmailUrl =
            "https://mail.google.com/mail/?view=cm&fs=1&to=&su=Asunto&body=Hola,%20aquí%20te%20comparto%20el%20archivo%20ZIP.";
        window.open(gmailUrl, '_blank');
    });

    // ---------------------------- Filtrar Búsqueda -----------------------------
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('busquedaCarpeta');
        const botonBusqueda = document.getElementById('btnBuscarCarpeta');

        function filtrarCarpetas() {
            const texto = inputBusqueda.value.toLowerCase();

            const carpetas = document.querySelectorAll('.carpeta.mosaico, .fila-carpeta');

            carpetas.forEach(carpeta => {
                const nombre = carpeta.querySelector('.nombre-carpeta')?.textContent.toLowerCase();

                if (!texto || (nombre && nombre.includes(texto))) {
                    carpeta.style.display = '';
                } else {
                    carpeta.style.display = 'none';
                }
            });
        }

        // Evento al hacer clic en el botón
        botonBusqueda.addEventListener('click', filtrarCarpetas);

        // También puedes hacer que se filtre automáticamente al escribir
        inputBusqueda.addEventListener('input', filtrarCarpetas);
    });


    // ------------------------------ Mover --------------------------------------------
    document.addEventListener("DOMContentLoaded", function() {
        @if($errors -> has('carpeta_destino'))
        const modal = document.getElementById("modalMover");
        modal.style.position = "fixed";
        modal.style.top = "0";
        modal.style.left = "0";
        modal.style.width = "100%";
        modal.style.height = "100%";
        modal.style.display = "flex";
        modal.style.justifyContent = "center";
        modal.style.alignItems = "center";
        modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
        modal.style.zIndex = "1000";
        @endif
    });

    document.getElementById("btnMoverCarpetas").addEventListener("click", function() {
        const checkboxes = document.querySelectorAll(".checkbox-carpeta:checked");
        let carpetasSeleccionadas = [];

        checkboxes.forEach(cb => {
            carpetasSeleccionadas.push(cb.value);
        });

        if (carpetasSeleccionadas.length === 0) {
            alert("Selecciona al menos una carpeta.");
            return;
        }

        document.getElementById("carpetasSeleccionadasInput").value = JSON.stringify(carpetasSeleccionadas);

        const modal = document.getElementById("modalMover");
        modal.style.position = "fixed";
        modal.style.top = "0";
        modal.style.left = "0";
        modal.style.width = "100%";
        modal.style.height = "100%";
        modal.style.display = "flex";
        modal.style.justifyContent = "center";
        modal.style.alignItems = "center";
        modal.style.backgroundColor = "rgba(0, 0, 0, 0.5)";
        modal.style.zIndex = "1000";
    });

    function cerrarModal() {
        document.getElementById("modalMover").style.display = "none";
    }
    </script>



</body>

</html>