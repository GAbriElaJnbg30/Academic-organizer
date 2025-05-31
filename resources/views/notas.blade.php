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
    <title>Bloc De Notas</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/notas.css') }}" rel="stylesheet" type="text/css">
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

    <div class="main-content" id="mainContent">
        <div class="container">
            <!-- Top Bar -->
            <div class="top-bar">
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('bloc') }}" class="btn btn-create">
                        <i class="fas fa-plus"></i> New
                    </a>

                    <button class="btn btn-delete-selected">
                        <i class="fas fa-trash"></i> Delete
                    </button>

                    <form id="download-form" method="POST" action="{{ route('notas.descargar') }}">
                        @csrf
                        <button type="button" class="btn btn-download" onclick="descargarNotas()">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </form>

                    <button class="btn btn-save" id="savePdfBtn">
                        <i class="fas fa-folder"></i> Save in Folder
                    </button>





                </div>

                <!-- Search Bar -->
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search notes...">
                </div>

                <!-- Filters -->
                <div class="filters">
                    <div class="filter-group">
                        <span class="filter-label">Sort by:</span>
                        <select class="filter-select" id="sortSelect">
                            <option value="" disabled selected hidden>Sort notes...</option>
                            <option value="defecto">Por defecto</option>
                            <option value="name">Name</option>
                            <option value="date">Date</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <span class="filter-label">View:</span>
                        <div class="view-options">
                            <button class="view-btn active" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <button class="view-btn" title="Grid View">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selection Bar -->
            <div class="selection-bar" style="display: flex; align-items: center; gap: 1rem;">
                <button class="btn btn-select-all">
                    <i class="fas fa-check-square"></i> Select
                </button>
                <span>Selected files: <strong>0</strong></span>
            </div>

            <!-- Notes Container -->
            <div class="notes-container">
                @isset($notas)
                @if($notas->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-sticky-note"></i>
                    <h3>No notes yet</h3>
                    <p>Click the "+ New" button to create your first note.</p>
                </div>
                @else
                @foreach($notas as $nota)
                <div class="note-card"
                    ondblclick="window.location.href='{{ route('bloc', ['id' => $nota->id_nota]) }}'">


                    <input type="checkbox" class="note-checkbox" name="notas[]" value="{{ $nota->id_nota }}"
                        style="display: none;" />

                    <div class="note-left">
                        <i class="fas fa-file-signature note-icon"></i>
                        <h4 class="note-title">{{ $nota->nombre_nota }}</h4>
                    </div>
                    <div class="note-right">
                        <p>Última modificación: {{ $nota->fecha_modificacion }}</p>
                    </div>
                </div>

                @endforeach
                @endif
                @else
                <p>Error: No se encontraron notas.</p>
                @endisset
            </div>


            <!-- Formulario oculto para eliminar notas -->
            <form id="deleteNotesForm" action="{{ route('notas.eliminarMultiples') }}" method="POST"
                style="display: none;">
                @csrf
                <input type="hidden" name="ids" id="selectedIds" />
            </form>

            <form id="pdfForm" action="{{ route('saveAsPdf') }}" method="POST" style="display:none;">
                @csrf
            </form>


            <!-- Modal para Selección de Carpeta -->
            <div id="modalMover" class="modal" style="display:none;">
                <div class="modal-content">
                    <h4>Seleccionar carpeta destino</h4>
                    <form id="formMoverCarpetas" method="POST" action="{{ route('notas.mover') }}">
                        @csrf
                        <input type="hidden" name="notas[]" id="notasSeleccionadas">
                        <input type="hidden" id="inputCarpetaId" name="carpeta_destino_oculto">

                        <label for="carpeta_destino">Mover a:</label>
                        <select name="id_carpeta" required>
                            @foreach($carpetas as $carpeta)
                            <option value="{{ $carpeta->id_carpeta }}">{{ $carpeta->nombre_carpeta }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('carpeta_destino'))
                        <div class="error text-red-500">{{ $errors->first('carpeta_destino') }}</div>
                        @endif
                        <br><br>
                        <button type="submit" class="guardar">Guardar</button>
                        <button type="button" class="cancelar" onclick="cerrarModal()">Cancelar</button>
                    </form>
                </div>
            </div>


            <form id="pdfForm" method="POST" action="{{ route('notas.guardar.pdf') }}">
                @csrf
                <input type="hidden" name="id_carpeta" id="inputCarpetaId">
            </form>




        </div>
    </div>

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

    <script>
    // Mostrar el modal cuando se haga clic en el botón "Guardar como PDF"
    // Función para mostrar el modal de selección de carpeta
    document.getElementById('savePdfBtn').addEventListener('click', function() {
        // Obtener todas las notas seleccionadas
        const selectedNotes = document.querySelectorAll('.note-checkbox:checked');

        // Si no se seleccionaron notas, mostrar un mensaje
        if (selectedNotes.length === 0) {
            alert("Por favor, selecciona al menos una nota.");
            return;
        }

        // Crear un array con los ids de las notas seleccionadas
        const selectedNoteIds = Array.from(selectedNotes).map(checkbox => checkbox.value);

        // Agregar los ids de las notas al formulario oculto
        document.getElementById('notasSeleccionadas').value = JSON.stringify(selectedNoteIds);

        // Mostrar el modal
        document.getElementById('modalMover').style.display = 'flex';
    });

    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalMover').style.display = 'none';
    }


    // Función para cerrar el modal
    function cerrarModal() {
        document.getElementById('modalMover').style.display = 'none';
    }

    document.getElementById('formMoverCarpetas').addEventListener('submit', function(event) {
        // Limpiar inputs ocultos anteriores
        const oldInputs = document.querySelectorAll('.input-nota-hidden');
        oldInputs.forEach(input => input.remove());

        // Obtener notas seleccionadas
        const selectedNotes = document.querySelectorAll('.note-checkbox:checked');
        if (selectedNotes.length === 0) {
            alert('Por favor, selecciona al menos una nota.');
            event.preventDefault();
            return;
        }

        // Agregar inputs ocultos al formulario
        const form = this;
        selectedNotes.forEach(function(checkbox) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'notas[]';
            input.value = checkbox.value;
            input.classList.add('input-nota-hidden'); // para poder limpiar después
            form.appendChild(input);
        });
    });












    // ---------------------------- Check boxes de selección ----------------------------

    document.addEventListener('DOMContentLoaded', function() {
        const selectButton = document.querySelector('.btn-select-all');
        const deleteButton = document.querySelector('.btn-delete-selected');
        const checkboxes = document.querySelectorAll('.note-checkbox');
        const selectedCount = document.querySelector('.selection-bar strong');
        const container = document.querySelector('.container');
        const deleteNotesForm = document.getElementById('deleteNotesForm');
        const selectedIdsInput = document.getElementById('selectedIds');
        let selectionMode = false;

        selectButton.addEventListener('click', function() {
            selectionMode = !selectionMode;

            checkboxes.forEach(cb => {
                cb.style.display = selectionMode ? 'inline-block' : 'none';
                cb.checked = false;
            });

            container.classList.toggle('selection-mode', selectionMode);
            //deleteButton.style.display = selectionMode ? 'inline-block' : 'none';

            updateSelectedCount();

            selectButton.innerHTML = selectionMode ?
                '<i class="fas fa-times"></i> Cancel' :
                '<i class="fas fa-check-square"></i> Select';
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedCount));

        function updateSelectedCount() {
            const count = Array.from(checkboxes).filter(cb => cb.checked).length;
            selectedCount.textContent = count;
        }

        deleteButton.addEventListener('click', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Por favor, seleccione primero las notas que desea eliminar.');
                return;
            }

            if (!confirm('¿Estás seguro de que deseas eliminar las notas seleccionadas?')) return;

            // Rellenar los IDs en el campo oculto del formulario
            selectedIdsInput.value = selectedIds.join(',');

            // Enviar el formulario
            deleteNotesForm.submit();
        });
    });

    // ------------------------ Ordenar por lo que elija ----------------------
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('sortSelect');
        const container = document.querySelector('.notes-container');
        const originalOrder = Array.from(container.children); // Guardar orden inicial

        // Aplicar orden guardado si existe
        const savedSort = localStorage.getItem('preferredSort');
        if (savedSort) {
            sortSelect.value = savedSort;
            sortNotes(savedSort);
        }

        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            localStorage.setItem('preferredSort', sortBy);
            sortNotes(sortBy);
        });

        function sortNotes(sortBy) {
            let sortedCards = [];

            if (sortBy === 'name') {
                sortedCards = Array.from(container.querySelectorAll('.note-card')).sort((a, b) => {
                    const nameA = a.querySelector('.note-title').textContent.toLowerCase();
                    const nameB = b.querySelector('.note-title').textContent.toLowerCase();
                    return nameA.localeCompare(nameB);
                });
            } else if (sortBy === 'date') {
                sortedCards = Array.from(container.querySelectorAll('.note-card')).sort((a, b) => {
                    const dateA = new Date(a.querySelector('p').textContent.replace(
                        'Última modificación: ', ''));
                    const dateB = new Date(b.querySelector('p').textContent.replace(
                        'Última modificación: ', ''));
                    return dateB - dateA; // Más reciente primero
                });
            } else if (sortBy === 'defecto') {
                sortedCards = originalOrder;
            }

            // Limpiar y volver a agregar las tarjetas en el orden adecuado
            container.innerHTML = '';
            sortedCards.forEach(card => container.appendChild(card));
        }
    });

    // ------------------------- Buscar Notas --------------------------------
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const cards = document.querySelectorAll('.note-card');

        cards.forEach(card => {
            const title = card.querySelector('.note-title').textContent.toLowerCase();
            if (title.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // ------------------------ Crera Nota en blanco -------------------------
    document.addEventListener("DOMContentLoaded", () => {
        const newBtn = document.querySelector(".btn-create");

        if (newBtn) {
            newBtn.addEventListener("click", () => {
                // Limpiar el contenido guardado en localStorage
                localStorage.removeItem("notaContenido");
                localStorage.removeItem("notaDrawing"); // si tienes dibujos también
            });
        }
    })

    // Simple JavaScript to toggle between list and grid views
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.view-btn');
        const container = document.querySelector('.container');

        // 1. Leer vista guardada
        const savedView = localStorage.getItem('preferredView');
        if (savedView === 'list') {
            container.classList.add('list-view');
            activateButton('List View');
        } else {
            container.classList.remove('list-view');
            activateButton('Grid View');
        }

        // 2. Asignar eventos a los botones
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const isListView = this.title === 'List View';

                // Cambiar clase
                container.classList.toggle('list-view', isListView);

                // Guardar preferencia
                localStorage.setItem('preferredView', isListView ? 'list' : 'grid');

                // Activar botón
                activateButton(this.title);
            });
        });

        // Función para marcar el botón activo
        function activateButton(title) {
            viewButtons.forEach(btn => {
                btn.classList.toggle('active', btn.title === title);
            });
        }
    });

    // ---------------------------------- Descargar las notas como PDF ----------------------------------
    function descargarNotas() {
        const checkboxes = document.querySelectorAll('.note-checkbox:checked');
        const form = document.getElementById('download-form');

        // Eliminar inputs previos
        form.querySelectorAll('input[name="notas[]"]').forEach(e => e.remove());

        if (checkboxes.length === 0) {
            alert('SELECCIONE NOTAS');
            return;
        }

        checkboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'notas[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        form.submit();
    }
    </script>
</body>

</html>