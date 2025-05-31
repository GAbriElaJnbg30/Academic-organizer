<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Carpeta;
use App\Models\Notas;
use App\Models\Recordatorio;
use App\Models\Archivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class ActividadesController extends Controller
{
  
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tablaActual = $request->input('tabla') ?? 'usuarios';

        // Usuarios
        $usuarios = Usuario::when($search, function ($query, $search) {
            return $query->where('id_usuario', 'like', "%$search%")
                        ->orWhere('nombre', 'like', "%$search%")
                        ->orWhere('apellido', 'like', "%$search%")
                        ->orWhere('nombre_usuario', 'like', "%$search%")
                        ->orWhere('correo_electronico', 'like', "%$search%")
                        ->orWhere('fecha_nacimiento', 'like', "%$search%")
                        ->orWhere('genero', 'like', "%$search%")
                        ->orWhere('telefono', 'like', "%$search%")
                        ->orWhere('rol', 'like', "%$search%")
                        ->orWhere('foto_perfil', 'like', "%$search%");
        })->paginate(10);

        // Carpetas
        $carpetas = Carpeta::when($search, function ($query, $search) {
            return $query->where('id_carpeta', 'like', "%$search%")
                        ->orWhere('nombre_carpeta', 'like', "%$search%")
                        ->orWhere('fecha_creacion', 'like', "%$search%")
                        ->orWhere('id_usuario', 'like', "%$search%")
                        ->orWhere('parent_id', 'like', "%$search%");
        })->paginate(10);

        // Archivos
        $archivos = Archivo::when($search, function ($query, $search) {
            return $query->where('id_archivo', 'like', "%$search%")
                        ->orWhere('nombre_archivo', 'like', "%$search%")
                        ->orWhere('tipo_archivo', 'like', "%$search%")
                        ->orWhere('tamaño_archivo', 'like', "%$search%")
                        ->orWhere('ruta', 'like', "%$search%")
                        ->orWhere('fecha_subida', 'like', "%$search%")
                        ->orWhere('id_carpeta', 'like', "%$search%");
        })->paginate(10);

        // Notas
        $notas = Notas::when($search, function ($query, $search) {
            return $query->where('id_nota', 'like', "%$search%")
                        ->orWhere('nombre_nota', 'like', "%$search%")
                        ->orWhere('tipo', 'like', "%$search%")
                        ->orWhere('ruta_nota', 'like', "%$search%")
                        ->orWhere('fecha_modificacion', 'like', "%$search%")
                        ->orWhere('id_usuario', 'like', "%$search%")
                        ->orWhere('id_carpetaN', 'like', "%$search%");
        })->paginate(10);

        // Recordatorios
        $recordatorios = Recordatorio::when($search, function ($query, $search) {
            return $query->where('id_recordatorio', 'like', "%$search%")
                        ->orWhere('titulo', 'like', "%$search%")
                        ->orWhere('fecha', 'like', "%$search%")
                        ->orWhere('hora', 'like', "%$search%")
                        ->orWhere('descripcion', 'like', "%$search%")
                        ->orWhere('recordatorio_activado', 'like', "%$search%")
                        ->orWhere('creado_en', 'like', "%$search%")
                        ->orWhere('actualizado_en', 'like', "%$search%")
                        ->orWhere('id_usuario', 'like', "%$search%");
        })->paginate(10);

        return view('actividades', compact(
            'usuarios',
            'carpetas',
            'archivos',
            'notas',
            'recordatorios',
            'tablaActual'
        ));
    }

    public function buscar(Request $request)
{
    $tabla = $request->input('tabla');
    $search = $request->input('search');

    switch ($tabla) {
        case 'usuarios':
            $usuarios = Usuario::where('id_usuario', 'like', "%$search%")
                ->orWhere('nombre', 'like', "%$search%")
                ->orWhere('apellido', 'like', "%$search%")
                ->orWhere('nombre_usuario', 'like', "%$search%")
                ->orWhere('correo_electronico', 'like', "%$search%")
                ->orWhere('fecha_nacimiento', 'like', "%$search%")
                ->orWhere('genero', 'like', "%$search%")
                ->orWhere('telefono', 'like', "%$search%")
                ->orWhere('rol', 'like', "%$search%")
                ->orWhere('foto_perfil', 'like', "%$search%")
                ->paginate(10);
            return view('actividades', compact('usuarios'))->with('tablaActual', 'usuarios');

        case 'carpetas':
            $carpetas = Carpeta::where('id_carpeta', 'like', "%$search%")
                ->orWhere('nombre_carpeta', 'like', "%$search%")
                ->orWhere('fecha_creacion', 'like', "%$search%")
                ->orWhere('id_usuario', 'like', "%$search%")
                ->orWhere('parent_id', 'like', "%$search%")
                ->paginate(10);
            return view('actividades', compact('carpetas'))->with('tablaActual', 'carpetas');

        case 'archivos':
            $archivos = Archivo::where('id_archivo', 'like', "%$search%")
                ->orWhere('nombre_archivo', 'like', "%$search%")
                ->orWhere('tipo_archivo', 'like', "%$search%")
                ->orWhere('tamaño_archivo', 'like', "%$search%")
                ->orWhere('ruta', 'like', "%$search%")
                ->orWhere('fecha_subida', 'like', "%$search%")
                ->orWhere('id_carpeta', 'like', "%$search%")
                ->paginate(10);
            return view('actividades', compact('archivos'))->with('tablaActual', 'archivos');

        case 'notas':
            $notas = Notas::where('id_nota', 'like', "%$search%")
                ->orWhere('nombre_nota', 'like', "%$search%")
                ->orWhere('tipo', 'like', "%$search%")
                ->orWhere('ruta_nota', 'like', "%$search%")
                ->orWhere('fecha_modificacion', 'like', "%$search%")
                ->orWhere('id_usuario', 'like', "%$search%")
                ->orWhere('id_carpetaN', 'like', "%$search%")
                ->paginate(10);
            return view('actividades', compact('notas'))->with('tablaActual', 'notas');

        case 'recordatorios':
            $recordatorios = Recordatorio::where('id_recordatorio', 'like', "%$search%")
                ->orWhere('titulo', 'like', "%$search%")
                ->orWhere('fecha', 'like', "%$search%")
                ->orWhere('hora', 'like', "%$search%")
                ->orWhere('descripcion', 'like', "%$search%")
                ->orWhere('recordatorio_activado', 'like', "%$search%")
                ->orWhere('creado_en', 'like', "%$search%")
                ->orWhere('actualizado_en', 'like', "%$search%")
                ->orWhere('id_usuario', 'like', "%$search%")
                ->paginate(10);
            return view('actividades', compact('recordatorios'))->with('tablaActual', 'recordatorios');

        default:
            return redirect()->back();
    }
}


public function generarPDF($tablaActual)
{
    // Obtener los datos según la tabla seleccionada
    switch ($tablaActual) {
        case 'usuarios':
            $datos = Usuario::select([
                'id_usuario',
                'nombre',
                'apellido',
                'nombre_usuario',
                'correo_electronico',
                'fecha_nacimiento',
                'genero',
                'telefono',
                'rol'
            ])->get();
            break;
        case 'carpetas':
            $datos = Carpeta::all();
            break;
        case 'archivos':
            $datos = Archivo::all();
            break;
        case 'notas':
            $datos = Notas::select('id_nota', 'nombre_nota', 'tipo', 'ruta_nota', 'id_usuario', 'fecha_modificacion', 'id_carpetaN')->get();
            break;
        case 'recordatorios':
            $datos = Recordatorio::all();
            break;
        default:
            abort(404);
    }

    // Generar el PDF directamente desde los datos obtenidos
    //$pdf = Pdf::loadView('generar.pdf', compact('datos','tablaActual'));  // Aquí 'pdf.generic' es una vista general
    $fechaHora = Carbon::now()->format('d/m/Y H:i');
    $pdf = Pdf::loadView('pdf', compact('datos', 'tablaActual', 'fechaHora'))
          ->setPaper('a4', 'landscape');
    // En lugar de hacer una descarga, muestra el PDF en una ventana emergente
    return $pdf->stream("{$tablaActual}.pdf");
}



}