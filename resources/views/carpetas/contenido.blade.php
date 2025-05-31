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
    <link href="{{ asset('css/estiloarchivos.css') }}" rel="stylesheet" type="text/css">
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
            <aside class="sidebar">
                <h2><i class="fas fa-folder"></i> Organizer</h2>
                <nav>
                    <ul>
                        <li><a href="{{ route('archivos') }}"><i class="fas fa-home"></i> Inicio</a></li>
                    </ul>

                    <!-- Lista de carpetas principales -->

                </nav>
            </aside>







            <main class="main-content-dos">
                <div class="action-row">
                    <div class="search-bar">
                        <input type="text" id="busquedaContenido" placeholder="Buscar archivo o carpeta">
                        <button type="button"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="file-actions" style="display: flex; align-items: center; gap: 10px;">
                        <!-- Botón de crear carpeta -->
                        <button type="button" id="crear-carpeta-btn">
                            <i class="fas fa-folder-plus"></i> Crear Carpeta
                        </button>

                        <!-- Formulario de subida de archivos -->
                        <form action="{{ route('archivo.subir') }}" method="POST" enctype="multipart/form-data"
                            style="display: flex; align-items: center; gap: 10px; margin: 0;">
                            @csrf

                            <!-- Input oculto -->
                            <input type="hidden" name="id_carpeta" value="{{ $carpetaPadre->id_carpeta }}">

                            <!-- Botón estilizado para seleccionar archivo -->

                            <button type="button" class="btn btn-primary">
                                <label for="input-archivo" class="btn btn-primary" style="margin: 0; cursor: pointer;">
                                    <i class="fas fa-file"></i> Elegir Archivo
                                </label>
                                <input type="file" name="archivos[]" id="input-archivo" multiple required
                                    style="display: none;">
                            </button>



                            <!-- Botón para subir -->
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-upload"></i> Subir Archivo
                            </button>
                        </form>

                    </div>

                </div>

                <div class="action-row">
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
                        <button type="button" title="Descargar" id="descargar-zip"><i
                                class="fas fa-download"></i></button>

                        <button type="button" title="Eliminar" onclick="eliminarSeleccionados()">
                            <i class="fas fa-trash-alt"></i>
                        </button>

                        <button type="button" id="btnMoverCarpetas" title="Mover"><i
                                class="fas fa-exchange-alt"></i></button>

                        <!-- Botón de edición fuera del ciclo -->
                        <button type="button" title="Editar nombre" id="btn-abrir-editar"><i
                                class="fas fa-edit"></i></button>

                    </div>
                </div>

                <!---------------------------------- Muestra los Archivos ------------------------------>
                <!-- Contenedor para las carpetas -->
                <div class="file-display">
                    <!-- Botón de volver -->
                    <button type="button" class="btn-volver" onclick="window.history.back()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>

                    <h3><i class="fas fa-folder-open"></i> Contenido de la Carpeta: {{ $carpetaPadre->nombre_carpeta }}
                    </h3>
                    <div id="mensaje-seleccion" style="display:none;"></div>

                    <div class="contenedor-carpetas mosaico">
                        <!-- Mensajes de éxito o error -->
                        @if (session('success'))
                        <p>{{ session('success') }}</p>
                        @endif

                        <!-- Mensaje si no hay subcarpetas ni archivos -->
                        @if($archivos->isEmpty() && $subcarpetas->isEmpty())
                        <p>No hay contenido en esta carpeta.</p>
                        @else
                        <!-- Mostrar subcarpetas -->
                        <h3>Subcarpetas</h3>
                        <div class="vista-mosaico">
                            @foreach($subcarpetas as $subcarpeta)
                            <div class="carpeta mosaico" data-id_carpeta="{{ $subcarpeta->id_carpeta }}">
                                <input type="checkbox" class="checkbox-carpeta" name="subcarpetasSeleccionadas[]"
                                    value="{{ $subcarpeta->id_carpeta }}" style="display:none;">
                                <div class="icono-carpeta mosaico"
                                    data-href="{{ route('carpeta.contenido', ['id' => $subcarpeta->id_carpeta]) }}">
                                    <i class="fas fa-folder-open icono-folder"></i>
                                </div>
                                <div class="nombre-carpeta mosaico"
                                    data-href="{{ route('carpeta.contenido', ['id' => $subcarpeta->id_carpeta]) }}">
                                    {{ $subcarpeta->nombre_carpeta }}
                                </div>

                            </div>
                            @endforeach
                        </div>

                        <div class="vista-lista">
                            <div class="lista-carpetas">
                                @foreach($subcarpetas as $subcarpeta)
                                <div class="fila-carpeta" data-id_carpeta="{{ $subcarpeta->id_carpeta }}">
                                    <input type="checkbox" class="checkbox-carpeta" name="subcarpetasSeleccionadas[]"
                                        value="{{ $subcarpeta->id_carpeta }}" style=" display:none;">
                                    <a href="{{ route('carpeta.contenido', ['id' => $subcarpeta->id_carpeta]) }}">
                                        <div class="detalles-carpeta">
                                            <div class="icono-carpeta lista">
                                                <i class="fas fa-folder-open"></i>
                                            </div>
                                            <div class="nombre-carpeta lista">
                                                {{ $subcarpeta->nombre_carpeta }}
                                            </div>

                                        </div>
                                    </a>
                                    <div class="fecha-creacion">
                                        {{ $subcarpeta->fecha_creacion }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Mostrar archivos -->
                        <h3>Archivos</h3>

                        <!-- Vista en mosaico -->
                        <div class="vista-mosaico">
                            <div class="contenedor-archivos">
                                @foreach($archivos as $archivo)
                                @php
                                $ext = strtolower(pathinfo($archivo->nombre_archivo, PATHINFO_EXTENSION));
                                $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                $icon = match(true) {
                                in_array($ext, $imgExts) => 'fa-file-image',
                                $ext === 'pdf' => 'fa-file-pdf',
                                in_array($ext, ['doc', 'docx']) => 'fa-file-word',
                                in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel',
                                in_array($ext, ['ppt', 'pptx']) => 'fa-file-powerpoint',
                                in_array($ext, ['zip', 'rar']) => 'fa-file-archive',
                                default => 'fa-file'
                                };
                                @endphp
                                <div class="archivo-item mosaico">
                                    <input type="checkbox" class="checkbox-carpeta" name="archivosSeleccionados[]"
                                        value="{{ $archivo->nombre_archivo }}" style=" display:none;">
                                    <i class="fas {{ $icon }} archivo-icono"></i>
                                    <p class="archivo-nombre">{{ $archivo->nombre_archivo }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Vista en lista -->
                        <div class="vista-lista">
                            <div class="lista-archivos">
                                @foreach($archivos as $archivo)
                                @php
                                $ext = strtolower(pathinfo($archivo->nombre_archivo, PATHINFO_EXTENSION));
                                $imgExts = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                                $icon = match(true) {
                                in_array($ext, $imgExts) => 'fa-file-image',
                                $ext === 'pdf' => 'fa-file-pdf',
                                in_array($ext, ['doc', 'docx']) => 'fa-file-word',
                                in_array($ext, ['xls', 'xlsx']) => 'fa-file-excel',
                                in_array($ext, ['ppt', 'pptx']) => 'fa-file-powerpoint',
                                in_array($ext, ['zip', 'rar']) => 'fa-file-archive',
                                default => 'fa-file'
                                };
                                @endphp
                                <div class="fila-archivo">
                                    <input type="checkbox" class="checkbox-carpeta" name="archivosSeleccionados[]"
                                        value="{{ $archivo->nombre_archivo }}" style=" display:none;">
                                    <div class="icono-archivo lista">
                                        <i class="fas {{ $icon }}"></i>
                                    </div>
                                    <div class="nombre-archivo lista">
                                        {{ $archivo->nombre_archivo }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        

                            @endif
                    </div>
                </div>

            </main>
        </div>

        <!-- Formulario para subir archivos -->

        <!-- Formulario oculto de eliminar este ya no sirve, el de abajo si -->
        <form id="formEliminarSubcarpetas" action="{{ route('subcarpetas.eliminar') }}" method="POST"
            style="display: none;">
            @csrf
            @method('DELETE')
            <div id="contenedorInputsSubcarpetas"></div>
        </form>

        <form id="formEliminarSeleccionados" method="POST" action="{{ route('eliminarSeleccionados') }}">
            @csrf
            <div id="contenedorInputsEliminar"></div>
        </form>



        <!-- Modal para Crear Carpeta -->
        <div id="modal-crear" class="modal" style="display: none;"
            data-errors-crear="{{ $errors->any() && old('nombre_carpeta') ? 'true' : 'false' }}">
            <div class="modal-content">
                <h2>Crear Nueva Subcarpeta</h2>
                <form action="{{ route('carpeta.crear') }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <i class="fas fa-folder"></i>
                        <input type="text" name="nombre_carpeta" id="nombre_carpeta" placeholder="Nombre de la carpeta"
                            value="{{ old('nombre_carpeta') }}">
                        <input type="hidden" name="parent_id" value="{{ $carpetaPadre->id_carpeta }}">
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger mt-2">
                        {{ $errors->first('nombre_carpeta') }}
                    </div>
                    @endif
                    <div class="modal-buttons">
                        <button type="submit" id="crear-btn" class="guardar">Crear</button>
                        <button type="button" id="cancelar-btn" class="cancelar">Cancelar</button>
                    </div>

                </form>
            </div>
        </div>


        <!-- Modal de edición de nombre -->
        <div id="miModal" class="modal" style="display: none;"
            data-errors-editar="{{ $errors->any() ? 'true' : 'false' }}">
            <div class="modal-content">
                <h2>Editar Nombre de la Carpeta</h2>
                <form action="{{ route('carpeta.actualizar') }}" method="POST" id="form-editar">
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
    <script src="{{ asset('js/carpetas.js') }}"></script>
    <script src="{{ asset('js/toggleBtn.js') }}"></script>

    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth
    
    <script>
    // ------------------------------------------ MOVER ----------------------------------------------
    // Función para recolectar los IDs seleccionados
    function recolectarSeleccionados() {
        let carpetasSeleccionadas = [];
        let archivosSeleccionados = [];

        // Recolectar las carpetas seleccionadas
        document.querySelectorAll('.checkbox-carpeta[name="subcarpetasSeleccionadas[]"]:checked').forEach(function(
            checkbox) {
            carpetasSeleccionadas.push(checkbox.value);
        });

        // Recolectar los archivos seleccionados
        document.querySelectorAll('.checkbox-carpeta[name="archivosSeleccionados[]"]:checked').forEach(function(
            checkbox) {
            archivosSeleccionados.push(checkbox.value);
        });

        // Unir las carpetas y archivos seleccionados en un solo array
        let todosSeleccionados = carpetasSeleccionadas.concat(archivosSeleccionados);

        // Verificar si se seleccionó al menos una cosa
        if (todosSeleccionados.length === 0) {
            alert("Selecciona al menos una carpeta o archivo.");
            return;
        }

        // Poner los valores en el input oculto del formulario
        document.getElementById('carpetasSeleccionadasInput').value = JSON.stringify(todosSeleccionados);

        // Mostrar el modal con estilos centrados
        const modal = document.getElementById('modalMover');
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
    }

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalMover').style.display = 'none';
    }

    // Evento para abrir el modal cuando se hace clic en el botón "Mover"
    document.getElementById('btnMoverCarpetas').addEventListener('click', recolectarSeleccionados);





    // ---------------------- Buscar archivo o carpeta ---------------------------
    document.addEventListener("DOMContentLoaded", function() {
        const inputBusqueda = document.getElementById("busquedaContenido");

        inputBusqueda.addEventListener("input", function() {
            const valorBusqueda = inputBusqueda.value.toLowerCase();

            const subcarpetasMosaico = document.querySelectorAll(".vista-mosaico .carpeta.mosaico");
            const subcarpetasLista = document.querySelectorAll(".vista-lista .fila-carpeta");

            const archivosMosaico = document.querySelectorAll(".vista-mosaico .archivo-item.mosaico");
            const archivosLista = document.querySelectorAll(".vista-lista .fila-archivo");

            const mostrarCoincidencia = (elemento, texto) => {
                const nombre = texto.toLowerCase();
                elemento.style.display = nombre.includes(valorBusqueda) ? "flex" : "none";
            };

            subcarpetasMosaico.forEach((elem) => {
                const nombre = elem.querySelector(".nombre-carpeta").textContent;
                mostrarCoincidencia(elem, nombre);
            });

            subcarpetasLista.forEach((elem) => {
                const nombre = elem.querySelector(".nombre-carpeta").textContent;
                mostrarCoincidencia(elem, nombre);
            });

            archivosMosaico.forEach((elem) => {
                const nombre = elem.querySelector(".archivo-nombre").textContent;
                mostrarCoincidencia(elem, nombre);
            });

            archivosLista.forEach((elem) => {
                const nombre = elem.querySelector(".nombre-archivo").textContent;
                mostrarCoincidencia(elem, nombre);
            });
        });
    });


    // -------------------------- Compartir ------------------------------------
    document.getElementById("btnCompartirZip").addEventListener("click", function() {
        const gmailUrl =
            "https://mail.google.com/mail/?view=cm&fs=1&to=&su=Asunto&body=Hola,%20aquí%20te%20comparto%20el%20archivo%20ZIP.";
        window.open(gmailUrl, '_blank');
    });


    // -------------------------- DESCRAGR ZIP -----------------------------------
    document.getElementById('descargar-zip').addEventListener('click', function() {
    var carpetasSeleccionadas = [];
    var archivosSeleccionados = [];

    document.querySelectorAll('.checkbox-carpeta:checked').forEach(function(checkbox) {
        var value = checkbox.value;
        if (isNaN(value)) { 
            // Si el value no es un número, es un archivo (porque carpetas tienen id_carpeta numérico)
            archivosSeleccionados.push(value);
        } else {
            // Es una carpeta (los IDs de carpeta son números)
            carpetasSeleccionadas.push(value);
        }
    });

    if (carpetasSeleccionadas.length === 0 && archivosSeleccionados.length === 0) {
        alert("Por favor, selecciona al menos una carpeta o archivo.");
        return;
    }

    // Crear el formulario
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("descargarZip") }}'; // Ruta correcta a tu backend
    form.enctype = 'multipart/form-data';

    // Agregar el token CSRF al formulario
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);

    // Crear un campo oculto para las carpetas seleccionadas
    if (carpetasSeleccionadas.length > 0) {
        var inputCarpetas = document.createElement('input');
        inputCarpetas.type = 'hidden';
        inputCarpetas.name = 'carpetas';
        inputCarpetas.value = carpetasSeleccionadas.join(',');
        form.appendChild(inputCarpetas);
    }

    // Crear un campo oculto para los archivos seleccionados
    if (archivosSeleccionados.length > 0) {
        var inputArchivos = document.createElement('input');
        inputArchivos.type = 'hidden';
        inputArchivos.name = 'archivos';
        inputArchivos.value = archivosSeleccionados.join(',');
        form.appendChild(inputArchivos);
    }

    // Enviar el formulario
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});








    // Botón que abre el selector de archivos
    document.getElementById('btn-subir-archivo').addEventListener('click', function() {
        const carpetaId = document.getElementById('id-subcarpeta-seleccionada').value;
        if (!carpetaId) {
            alert("Selecciona una subcarpeta antes de subir un archivo.");
            return;
        }
        document.getElementById('input-archivo').click();
    });

    // Enviar automáticamente el formulario al seleccionar un archivo
    document.getElementById('input-archivo').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('form-subir-archivo').submit();
        }
    });
    </script>


</body>

</html>
</script>

</body>

</html>