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
    <title>Reporte De Actividades</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/actividades.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <div class="container">
            <!-- Top Bar -->
            <div class="top-bar">
                <!-- Action Buttons -->

                <!-- Search Table -->
                <div class="search-table">
                    <select class="filter-select" id="sortSelect">
                        <option value="" disabled selected hidden>Table</option>
                        <option value="usuarios">Usuarios</option>
                        <option value="carpetas">Carpetas</option>
                        <option value="archivos">Archivos</option>
                        <option value="notas">Notas</option>
                        <option value="recordatorios">Recordatorios</option>
                    </select>
                </div>

                <!-- Search Bar -->
                <div class="search-bar">
                    <form method="GET" action="{{ route('buscar') }}">
                        <i class="fas fa-search"></i>
                        <input type="hidden" name="tabla" value="{{ $tablaActual }}"> <!-- Esto es clave -->
                        <input type="text" id="searchInput" name="search" placeholder="Buscar..."
                            value="{{ request('search') }}">
                        <button type="submit">Buscar</button>
                        <a href="{{ route('buscar', ['tabla' => $tablaActual]) }}" class="clear-search-btn">Borrar</a>
                    </form>
                </div>

                <!-- View PDF -->
                <div class="btn-pdf">
                    <a href="{{ route('pdf', ['tabla' => $tablaActual]) }}" target="_blank">
                        <button type="button">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    </a>
                </div>


            </div>

            <!-- Table -->
            <div class="table-container">
                <table class="custom-table">
                    @if ($tablaActual === 'usuarios')
                    <!-- Usuarios -->
                    <table class="custom-table">
                        <thead id="usuarios-head">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Nombre Usuario</th>
                                <th>Correo Electrónico</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Género</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody id="usuarios-body" style="{{ $tablaActual === 'usuarios' ? '' : 'display: none;' }}">
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id_usuario }}</td>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->nombre_usuario }}</td>
                                <td>{{ $usuario->correo_electronico }}</td>
                                <td>{{ $usuario->fecha_nacimiento }}</td>
                                <td>{{ $usuario->genero }}</td>
                                <td>{{ $usuario->telefono }}</td>
                                <td>{{ $usuario->rol }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="pagination">
                        {{ $usuarios->appends(['tabla' => 'usuarios'])->links('vendor.pagination.default') }}
                    </div>
                    @endif




                    @if ($tablaActual === 'carpetas')
                    <!-- Carpetas -->
                    <table class="custom-table">
                        <thead id="carpetas-head" style="display: none;">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Carpeta</th>
                                <th>Fecha Creación</th>
                                <th>ID Usuario</th>
                                <th>Parent ID</th>
                            </tr>
                        </thead>
                        <tbody id="carpetas-body" style="{{ $tablaActual === 'carpetas' ? '' : 'display: none;' }}">
                            @foreach($carpetas as $carpeta)
                            <tr>
                                <td>{{ $carpeta->id_carpeta }}</td>
                                <td>{{ $carpeta->nombre_carpeta }}</td>
                                <td>{{ $carpeta->fecha_creacion }}</td>
                                <td>{{ $carpeta->id_usuario }}</td>
                                <td>{{ $carpeta->parent_id }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="pagination">
                        {{ $carpetas->appends(['tabla' => 'carpetas'])->links('vendor.pagination.default') }}
                    </div>

                    @endif

                    @if ($tablaActual === 'archivos')
                    <!-- Archivos -->
                    <table class="custom-table">
                        <thead id="archivos-head" style="display: none;">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Archivo</th>
                                <th>Tipo Archivo</th>
                                <th>Tamaño</th>
                                <th>Ruta</th>
                                <th>Fecha de Cargado</th>
                                <th>ID Carpeta</th>
                            </tr>
                        </thead>
                        <tbody id="archivos-body" style="display: none;">
                            @foreach($archivos as $archivo)
                            <tr>
                                <td>{{ $archivo->id_archivo }}</td>
                                <td>{{ $archivo->nombre_archivo }}</td>
                                <td>{{ $archivo->tipo_archivo }}</td>
                                <td>{{ $archivo->tamaño_archivo }}</td>
                                <td>{{ $archivo->ruta }}</td>
                                <td>{{ $archivo->fecha_subida }}</td>
                                <td>{{ $archivo->id_carpeta }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="pagination">
                        {{ $archivos->appends(['tabla' => 'archivos'])->links('vendor.pagination.default') }}
                    </div>
                    @endif

                    @if ($tablaActual === 'notas')
                    <!-- Notas -->
                    <table class="custom-table">
                        <thead id="notas-head" style="display: none;">
                            <tr>
                                <th>ID</th>
                                <th>Nombre Nota</th>
                                <th>Tipo Nota</th>
                                <th>Ruta</th>
                                <th>Fecha de Modificación</th>
                                <th>ID Usuario</th>
                                <th>ID Carpeta</th>
                            </tr>
                        </thead>
                        <tbody id="notas-body" style="display: none;">
                            @foreach($notas as $nota)
                            <tr>
                                <td>{{ $nota->id_nota }}</td>
                                <td>{{ $nota->nombre_nota }}</td>
                                <td>{{ $nota->tipo }}</td>
                                <td>{{ $nota->ruta_nota }}</td>
                                <td>{{ $nota->fecha_modificacion }}</td>
                                <td>{{ $nota->id_usuario }}</td>
                                <td>{{ $nota->id_carpetaN }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="pagination">
                        {{ $notas->appends(['tabla' => 'notas'])->links('vendor.pagination.default') }}
                    </div>
                    @endif

                    @if ($tablaActual === 'recordatorios')
                    <table class="custom-table">
                        <thead id="recordatorios-head">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Descripción</th>
                                <th>Recordatorio Activado</th>
                                <th>Creado en</th>
                                <th>Actualizado en</th>
                                <th>ID Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="recordatorios-body">
                            @foreach($recordatorios as $recordatorio)
                            <tr>
                                <td>{{ $recordatorio->id_recordatorio }}</td>
                                <td>{{ $recordatorio->titulo }}</td>
                                <td>{{ $recordatorio->fecha }}</td>
                                <td>{{ $recordatorio->hora }}</td>
                                <td>{{ $recordatorio->descripcion }}</td>
                                <td>{{ $recordatorio->recordatorio_activado }}</td>
                                <td>{{ $recordatorio->creado_en }}</td>
                                <td>{{ $recordatorio->actualizado_en }}</td>
                                <td>{{ $recordatorio->id_usuario }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación debajo de la tabla -->
                    <div class="pagination">
                        {{ $recordatorios->appends(['tabla' => 'recordatorios'])->links('vendor.pagination.default') }}
                    </div>
                    @endif

                </table>




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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="{{ asset('js/archivos.js') }}"></script>

    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth

    <script>
    // Pasa la URL generada por asset() como variable JavaScript
    var logoUrl = "{{ asset('imagenes/ao.png') }}";

    document.getElementById('sortSelect').addEventListener('change', function() {
        const tabla = this.value;
        window.location.href = `?tabla=${tabla}`;
    });


    // Filtros
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('sortSelect');
        const tablas = ['usuarios', 'carpetas', 'archivos', 'notas', 'recordatorios'];

        // Mostrar tabla según selección
        function mostrarTabla(tablaSeleccionada) {
            tablas.forEach(tabla => {
                const head = document.getElementById(`${tabla}-head`);
                const body = document.getElementById(`${tabla}-body`);
                if (head && body) {
                    if (tabla === tablaSeleccionada) {
                        head.style.display = '';
                        body.style.display = '';
                    } else {
                        head.style.display = 'none';
                        body.style.display = 'none';
                    }
                }
            });
        }

        // Cargar selección guardada o usar 'usuarios' por defecto
        let tablaGuardada = localStorage.getItem('tablaSeleccionada');
        if (!tablaGuardada || !tablas.includes(tablaGuardada)) {
            tablaGuardada = 'usuarios'; // Forzar por defecto
            localStorage.setItem('tablaSeleccionada', tablaGuardada);
        }

        select.value = tablaGuardada;
        mostrarTabla(tablaGuardada);

        // Guardar nueva selección
        select.addEventListener('change', function() {
            const selected = this.value;
            localStorage.setItem('tablaSeleccionada', selected);
            mostrarTabla(selected);
        });
    });

    // PDF
    // document.querySelector('.btn-pdf a').addEventListener('click', function(e) {
    //    e.preventDefault(); // Prevenir el comportamiento por defecto del enlace
    //  const tabla = '{{ $tablaActual }}'; // O el valor de la tabla actual
    //const url = `{{ route('pdf', ['tabla' => '__tabla__']) }}`.replace('__tabla__', tabla);

    // Abrir el enlace en una nueva pestaña
    //window.open(url, '_blank');
    //});



    // Paginación
    document.addEventListener('DOMContentLoaded', function() {
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        const currentPageSpan = document.getElementById('currentPage');

        function changePage(url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('usuarios-body').innerHTML = data.match(
                        /<tbody>(.*?)<\/tbody>/s)[1];
                    currentPageSpan.textContent = data.match(/<span id="currentPage">(\d+)<\/span>/)[1];
                    prevButton.setAttribute('data-url', data.match(
                        /<button id="prevPage".*?data-url="(.*?)"/)[1]);
                    nextButton.setAttribute('data-url', data.match(
                        /<button id="nextPage".*?data-url="(.*?)"/)[1]);
                    prevButton.disabled = !data.includes('data-url', prevButton.getAttribute('data-url'));
                    nextButton.disabled = !data.includes('data-url', nextButton.getAttribute('data-url'));
                });
        }

        prevButton.addEventListener('click', function() {
            if (prevButton.getAttribute('data-url')) {
                changePage(prevButton.getAttribute('data-url'));
            }
        });

        nextButton.addEventListener('click', function() {
            if (nextButton.getAttribute('data-url')) {
                changePage(nextButton.getAttribute('data-url'));
            }
        });
    });
    </script>
</body>

</html>