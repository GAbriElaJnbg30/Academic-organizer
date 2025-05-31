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
    <title>Recordatorios</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <link href="{{ asset('css/estilos3m.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/recordatorios.css') }}" rel="stylesheet" type="text/css">
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

    <div class="main-content" id="mainContent">
        <div class="container">
            <!-- Top Bar -->
            <div class="top-bar">
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('newrecordatorio') }}" class="btn btn-create">
                        <i class="fas fa-plus"></i> New
                    </a>

                    <form id="deleteNotesForm" action="{{ route('recordatorios.eliminar') }}" method="POST">
                        @csrf
                        <!-- Este input se llenará con los IDs de recordatorios seleccionados -->
                        <input type="hidden" name="selectedIds" id="selectedIds">

                        <button type="button" class="btn btn-delete-selected">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>

                </div>

                <!-- Search Bar -->
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search reminders...">
                </div>

                <!-- Filters -->
                <div class="filters">
                    <div class="filter-group">
                        <span class="filter-label">Sort by:</span>
                        <select class="filter-select" id="sortSelect">
                            <option value="" disabled selected hidden>Sort reminders...</option>
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

            <!-- Reminders Container -->
            <div class="reminders-container">
                @isset($recordatorios)
                @if($recordatorios->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>No reminders yet</h3>
                    <p>Click the "+ New" button to create your first reminder.</p>
                </div>
                @else
                @foreach($recordatorios as $recordatorio)
                <div class="reminder">
                    <input type="checkbox" class="reminder-checkbox" name="recordatorios[]"
                        value="{{ $recordatorio->id_recordatorio }}" style="display: none;" />

                    <div class="reminder-left">
                        <i class="fas fa-bell reminder-icon"></i>



                        <h4 class="reminder-title">{{ $recordatorio->titulo }}</h4>
                        <div class="left-dos">
                            @if($recordatorio->recordatorio_activado)
                            <p class="activado"><strong>Activado</strong></p>
                            @else
                            <p class="noactivado"><strong>No Activado</strong></p>
                            @endif
                        </div>
                    </div>

                    <div class="reminder-right">
                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($recordatorio->fecha)->format('d-m-Y') }}
                            <strong>Hora:</strong> {{ \Carbon\Carbon::parse($recordatorio->hora)->format('H:i') }}
                        </p>
                    </div>
                </div>
                @endforeach
                @endif
                @else
                <p>Error: No se encontraron notas.</p>
                @endisset
            </div>


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

    @else
    <script>
    window.location.href = "{{ route('iniciarsesion') }}";
    </script>
    @endauth

    <script>
    // ------------------------------------------ Vista Lista - Mosaico --------------------------------------------------
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

    // ------------------------------------------ Checkbox de selección -------------------------------------------

    document.addEventListener('DOMContentLoaded', function() {
        const selectButton = document.querySelector('.btn-select-all');
        const deleteButton = document.querySelector('.btn-delete-selected'); // <- esta línea te faltaba
        const checkboxes = document.querySelectorAll('.reminder-checkbox');
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

            updateSelectedCount();

            selectButton.innerHTML = selectionMode ?
                '<i class="fas fa-times"></i> Cancelar' :
                '<i class="fas fa-check-square"></i> Seleccionar';
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedCount));

        function updateSelectedCount() {
            const count = Array.from(checkboxes).filter(cb => cb.checked).length;
            selectedCount.textContent = count;
        }

        deleteButton.addEventListener('click', function() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb
                    .value); // asegúrate de que cada checkbox tenga su value con el ID del recordatorio

            if (selectedIds.length === 0) {
                alert('Debes primero seleccionar recordatorios.');
                return;
            }

            if (!confirm('¿Estás seguro de que deseas eliminar los recordatorios seleccionados?'))
                return;

            selectedIdsInput.value = selectedIds.join(',');
            deleteNotesForm.submit();
        });
    });

    // ------------------------ Ordenar por lo que elija ----------------------
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('sortSelect');
        const remindersContainer = document.querySelector('.reminders-container');
        const reminders = Array.from(remindersContainer.querySelectorAll('.reminder'));

        // Restaurar valor guardado
        const savedSort = localStorage.getItem('sortOption');
        if (savedSort) {
            sortSelect.value = savedSort;
            sortReminders(savedSort);
        }

        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            localStorage.setItem('sortOption', sortBy);
            sortReminders(sortBy);
        });

        function sortReminders(sortBy) {
            const sortedReminders = [...reminders].sort((a, b) => {
                if (sortBy === 'name') {
                    const aName = a.querySelector('h4').textContent.toLowerCase();
                    const bName = b.querySelector('h4').textContent.toLowerCase();
                    return aName.localeCompare(bName);
                } else if (sortBy === 'date') {
                    const aText = a.querySelector('.reminder-right p').textContent;
                    const bText = b.querySelector('.reminder-right p').textContent;

                    const aDateMatch = aText.match(
                        /Fecha:\s*(\d{2})-(\d{2})-(\d{4})\s*Hora:\s*(\d{2}):(\d{2})/);
                    const bDateMatch = bText.match(
                        /Fecha:\s*(\d{2})-(\d{2})-(\d{4})\s*Hora:\s*(\d{2}):(\d{2})/);

                    const aDate = new Date(
                        `${aDateMatch[3]}-${aDateMatch[2]}-${aDateMatch[1]}T${aDateMatch[4]}:${aDateMatch[5]}`
                    );
                    const bDate = new Date(
                        `${bDateMatch[3]}-${bDateMatch[2]}-${bDateMatch[1]}T${bDateMatch[4]}:${bDateMatch[5]}`
                    );

                    return aDate - bDate;
                } else {
                    return 0;
                }
            });

            remindersContainer.innerHTML = '';
            sortedReminders.forEach(reminder => remindersContainer.appendChild(reminder));
        }
    });

    // ---------------------------------------- Buscar recordatorios -----------------------------------------
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const cards = document.querySelectorAll('.reminder');

        cards.forEach(card => {
            const title = card.querySelector('.reminder-title').textContent.toLowerCase();
            if (title.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // ---------------------------------------- Abrir para editar -----------------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        const reminders = document.querySelectorAll('.reminder');

        reminders.forEach(reminder => {
            reminder.addEventListener('dblclick', function() {
                const checkbox = reminder.querySelector('.reminder-checkbox');
                const id = checkbox.value;
                window.location.href = `{{ url('recordatorio') }}/${id}/editar`;
            });
        });
    });

    // --------------------------------- Permiso del usuario para recordatorios ----------------------------------
    if ('Notification' in window && navigator.serviceWorker) {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                console.log("Permission granted for notifications");
            } else {
                console.log("Permission denied for notifications");
            }
        });
    }
    </script>
</body>

</html>