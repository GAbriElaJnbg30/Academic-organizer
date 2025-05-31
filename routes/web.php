<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registro;
use App\Http\Controllers\Login;
use App\Http\Controllers\Alta;
use App\Http\Controllers\CarpetaController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\RecordatoriosController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('iniciarsesion');
})->name('iniciarsesion');

Route::get('/acerca-de', function () {
    return view('acercade');
})->name('acercade');

Route::get('/manual-de-usuario', function () {
    return view('manualusuario');
})->name('manualusuario');

Route::get('/contactenos', function () {
    return view('contacto');
})->name('contacto');

Route::get('/registro', function () {
    return view('registro');
})->name('registro');

Route::get('/olvido-contraseña', function () {
    return view('olvidoc');
})->name('olvidoc');

Route::get('/abienvenida', function () {
    return view('abienvenida');  // Vista para el administrador
})->name('abienvenida');

Route::get('/ubienvenida', function () {
    return view('ubienvenida');  // Vista para el usuario general
})->name('ubienvenida');

Route::get('/cuenta-creada-con-exito', function () {
    return view('mensajeregistro');  // Vista para el usuario general
})->name('mensajeregistro');

Route::get('/actualizar-estado', function () {
    return view('actualizarestado');  // Vista para el usuario general
})->name('actualizarestado');

Route::get('/actualizar-contraseña', function () {
    return view('cambiarcontrasena');  // Vista para el usuario general
})->name('cambiarcontrasena');

Route::get('/cambiar-fondo', function () {
    return view('upantalla');  // Vista para el usuario general
})->name('upantalla');

Route::get('/manual-de-usuario-pdf', function () {
    return view('pdf-viewer');
})->name('pdf-viewer');

Route::get('/actualizar-informacion', function () {
    return view('actinfo');
})->name('actinfo');

Route::get('/gestion-de-archivos', function () {
    return view('archivos');
})->name('archivos');

Route::get('/bloc-de-notas', function () {
    return view('notas');
})->name('notas');



Route::get('/actualizar-perfil', function () {
    return view('uactperfil');  // Vista para el usuario general
})->name('uactperfil');

Route::get('/actualizar-perfil-administrador', function () {
    return view('actperfil');  // Vista para el usuario administrador
})->name('actperfil');

Route::get('/crud', function () {
    return view('crud');  // Vista para el usuario administrador
})->name('crud');

Route::get('/reporte-de-actividades', function () {
    return view('actividades');  // Vista para el usuario administrador
})->name('actividades');

Route::get('/cambiar-fondo-administrador', function () {
    return view('apantalla');  // Vista para el usuario general
})->name('apantalla');

Route::get('/recordatorios', function () {
    return view('recordatorios');  
})->name('recordatorios');


Route::get('/alta-de-usuario', function () {
    return view('nuevo');  
})->name('nuevo');


Route::get('/bloc-de-notas-crear-nota', function () {
    return view('bloc');  
})->name('bloc');

Route::get('/new-recordatorio', function () {
    return view('newrecordatorio');  
})->name('newrecordatorio');

Route::get('/modificar-recordatorio', function () {
    return view('actualizarrecordatorio');  
})->name('actualizarrecordatorio');

Route::get('/reporte-actividades-pdf', function () {
    return view('pdf');
})->name('pdf');

// ----------------------------------------------------------- Registro ------------------------------------------------------------
Route::get('registro', function() {
    return view('registro');  // Asegúrate de tener la vista de registro
})->name('registro');

Route::post('registro', [Registro::class, 'register']);

// ------------------------------------------------------- Inicio Sesion ----------------------------------------------------------
Route::get('/login', [Login::class, 'login'])->name('login');
Route::post('/login', [Login::class, 'login']);

// -------------------------------------------------------- CRUD GET ALL ----------------------------------------------------------
Route::get('/crud', [Registro::class, 'mostrarUsuarios'])->name('crud');

// -------------------------------------------------------- CRUD ELIMINAR ---------------------------------------------------------
Route::delete('/usuarios/{id}', [Registro::class, 'eliminarUsuario'])->name('usuarios.eliminar');

// --------------------------------------------------------- CRUD CREAR -----------------------------------------------------------
Route::post('/crud', [Alta::class, 'register']);

// ------------------------------------------------------- CRUD ACTUALIZAR --------------------------------------------------------
Route::get('/usuarios/{id}/editar', [Registro::class, 'editarUsuario'])->name('usuarios.editar');
Route::put('/usuarios/actualizar', [Registro::class, 'actualizar'])->name('actualizar');

// --------------------------------------------------------- CRUD BUSCAR ---------------------------------------------------------
Route::get('/usuarios', [Registro::class, 'index'])->name('usuarios.index');

// ----------------------------------- Cerrar Sesión ---------------------------------
Route::post('/logout', [Login::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'no-back']], function () {
    // Tus rutas protegidas
});

// ------------------------------------- Actualizar Perfil --------------------------------------
Route::post('/actualizar-foto-perfil', [Login::class, 'actualizarPerfil'])->name('actualizarPerfil');
// Ruta para actualizar el perfil del administrador

// ------------------------------------- Recuperar Contraseña -------------------------------------
Route::get('/recuperar/{token}', [Registro::class, 'mostrarFormularioRecuperar'])->name('formulario.recuperar');
Route::post('/recuperar-cuenta', [Registro::class, 'recuperarCuenta'])->name('recuperar.cuenta');
Route::post('/restablecer-cuenta', [Registro::class, 'restablecerCuenta'])->name('restablecer.cuenta');

// -------------------------------------- Crear Carpeta -----------------------------------------
Route::post('/carpetas/crear', [CarpetaController::class, 'crearCarpeta'])->name('carpetas.crear');

// -------------------------------------- MOstrar Carpetas --------------------------------------
Route::get('/gestion-de-archivos', [CarpetaController::class, 'mostrarCarpetas'])->name('archivos');
Route::get('/carpetas', [CarpetaController::class, 'mostrarCarpetas'])->name('carpetas');


// ------------------------------------ Actualizar Nombre Carpeta -------------------------------------------------
Route::put('/carpeta/actualizar', [CarpetaController::class, 'actualizarCarpeta'])->name('carpeta.actualizar');


// routes/web.php
Route::post('/subcarpeta/actualizar', [CarpetaController::class, 'actualizar'])->name('subcarpeta.actualizar');



// -------------------------------------- Abrir Carpeta -----------------------------------------
Route::get('/carpeta/{id}/contenido', [CarpetaController::class, 'verContenido'])->name('carpeta.contenido');

Route::get('/contenido/{id}', [CarpetaController::class, 'verContenido'])->name('verContenido');

Route::post('/carpeta/crear', [CarpetaController::class, 'crearCarpeta'])->name('carpeta.crear');

// ---------------------------------- Eliminar Carpetas padre con su contenido ----------------------------------------
Route::delete('/carpetas/eliminar', [CarpetaController::class, 'eliminar'])->name('carpetas.eliminar');

// ---------------------------------- Eliminar Subcarpetas -------------------------------------------
Route::delete('/subcarpetas/eliminar', [CarpetaController::class, 'eliminarSubcarpetas'])->name('subcarpetas.eliminar');


// -------------------------------------- Subir Archivos ---------------------------------------------
Route::post('/archivos/subir', [ArchivoController::class, 'subir'])->name('archivos.subir');

Route::post('/archivo/subir', [ArchivoController::class, 'subir'])->name('archivo.subir');

// ---------------------------------- Eliminar Archivos ----------------------------------------
Route::post('/eliminar-seleccionados', [ArchivoController::class, 'eliminarSeleccionados'])->name('eliminarSeleccionados');

// ----------------------------------- Descargar carpeta Padre --------------------------------
Route::get('/descargar-zip', [ArchivoController::class, 'descargarZip']);


Route::get('/test-zip', [ArchivoController::class, 'testZip']); // esto era solo del ejemplo
Route::post('/descargar-zip', [ArchivoController::class, 'descargarZip'])->name('descargarZip');

// ----------------------------------------- Mover ---------------------------------------------
Route::post('/carpetas/mover', [CarpetaController::class, 'moverCarpetas'])->name('carpetas.mover');
Route::post('/mover-carpetas', [CarpetaController::class, 'mover'])->name('mover.carpetas');


// ----------------------------------------- Crear Notas ---------------------------------------------
//Route::post('/notas', [NotasController::class, 'store'])->name('notas.store');
Route::post('/notas', [NotasController::class, 'store']);

//Route::get('/notas', [NotasController::class, 'index'])->name('notas')->middleware('auth');
Route::get('/bloc-de-notas', [NotasController::class, 'index'])->name('notas');

// abrir una nota para crearla
Route::get('/bloc', [NotasController::class, 'bloc'])->name('bloc');



// ------------------------------------- Editar nota -----------------------------------------
Route::get('/bloc/{id?}', [NotasController::class, 'bloc'])->name('bloc');



// ---------------------------- Eliminar Notas ---------------------------------
Route::post('/notas/eliminar-multiples', [NotasController::class, 'eliminarMultiples'])->name('notas.eliminarMultiples');








// ---------------------------------------- Guardar notas en carpetas -----------------------------------------
Route::get('/notas', [NotasController::class, 'index'])->name('notas');
Route::get('/bloc-de-notas', [NotasController::class, 'index'])->name('carpetas.guardar');


// ---------------------------------------- descargar notas como pdf -----------------------------------------
Route::post('/notas/descargar', [NotasController::class, 'descargar'])->name('notas.descargar');

// ---------------------------------------- guardar notas como pdf en ardhivos -----------------------------------------
Route::post('/notas/pdf', [NotasController::class, 'saveAsPdf'])->name('saveAsPdf');
Route::post('/notas/guardar/pdf', [NotasController::class, 'saveAsPdf'])->name('notas.guardar.pdf');
Route::post('/notas/mover', [NotasController::class, 'mover'])->name('notas.mover');

// ----------------------------------------- Crear Recordatorios -----------------------------------------
Route::post('/recordatorios', [RecordatoriosController::class, 'store'])->name('recordatorios.store');

Route::get('/recordatorios', [RecordatoriosController::class, 'index'])->name('recordatorios');

// ----------------------------------------- Eliminar recordatorios -----------------------------------------
Route::post('/recordatorios/eliminar', [RecordatoriosController::class, 'eliminarSeleccionados'])->name('recordatorios.eliminar');

// ----------------------------------------- Abrir el recordatorio ------------------------------------------
Route::get('/recordatorio/{id}/editar', [RecordatoriosController::class, 'edit'])->name('recordatorios.update');

Route::put('/recordatorio/{id}', [RecordatoriosController::class, 'update'])->name('recordatorios.update');













Route::post('/save-subscription', [NotificationController::class, 'saveSubscription']);



// ----------------------------------------- Reportes de Actividades -----------------------------------------
Route::get('/reporte-de-actividades', [ActividadesController::class, 'index'])->name('actividades');


// --------------------------------------- Buscar actividades -------------------------------------------
Route::get('/actividades', [ActividadesController::class, 'buscar'])->name('buscar');




Route::get('/pdf/{tabla}', [ActividadesController::class, 'generarPDF'])->name('pdf');













Route::middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


