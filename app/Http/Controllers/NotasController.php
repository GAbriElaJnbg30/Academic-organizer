<?php
namespace App\Http\Controllers;

use App\Models\Notas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Carpeta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Archivo;


class NotasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre_nota' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        
        $idNota = $request->input('id_nota');

        if ($idNota) {
            // Actualizar nota existente
            $nota = Notas::where('id_nota', $idNota)
                ->where('id_usuario', $user->id_usuario)
                ->first();

            if ($nota) {
                $nota->contenido_html = $request->input('contenido_html');
                $nota->fecha_modificacion = now();
                $nota->save();
            }

        } else {
            // Crear nueva nota (mismo flujo que ya tienes)
            $nombreNota = $request->input('nombre_nota');

            $notaExistente = Notas::where('id_usuario', $user->id_usuario)
                ->where('nombre_nota', $nombreNota)
                ->first();

            if ($notaExistente) {
                $i = 1;
                while (Notas::where('id_usuario', $user->id_usuario)
                        ->where('nombre_nota', $nombreNota . " ({$i})")
                        ->exists()) {
                    $i++;
                }
                $nombreNota = $nombreNota . " ({$i})";
            }

            $nota = Notas::create([
                'nombre_nota' => $nombreNota,
                'tipo' => 'texto',
                'ruta_nota' => '/archivos/' . $nombreNota . '.txt',
                'id_usuario' => $user->id_usuario,
                'fecha_modificacion' => now(),
                'contenido_html' => $request->input('contenido_html'),
                'id_carpeta' => $request->input('carpeta_destino'),
            ]);
        }

        return redirect()->route('notas');
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('iniciarsesion');
        }

        // Obtener las notas del usuario
        $notas = Notas::where('id_usuario', $user->id_usuario)->get();

        // Obtener las carpetas y subcarpetas del usuario
        $carpetas = Carpeta::with('subcarpetas')
                        ->where('id_usuario', $user->id_usuario)
                        ->get();

        return view('notas', compact('notas', 'carpetas'));
    }

    public function bloc($id = null)
    {

        $contenido = '';
        $nota = null;

        if ($id) {
            $nota = Notas::find($id);
            if ($nota) {
                $contenido = $nota->contenido_html;
            }
        }

        //dd($contenido);

        return view('bloc', compact('contenido', 'nota'));
    }

    // ----------------------- Editar Nota ------------------------
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $nota = Notas::where('id_nota', $id)->where('id_usuario', $user->id_usuario)->first();

        if (!$nota) {
            return redirect()->route('notas')->with('message', 'Nota no encontrada.');
        }

        $nota->contenido_html = $request->input('contenido_html');
        $nota->fecha_modificacion = now();
        $nota->save();

        return redirect()->route('notas')->with('message', 'Nota actualizada.');
    }




    // ----------------------- Eliminar Notas ------------------------
    public function eliminarMultiples(Request $request)
    {
        $user = Auth::user();
        $ids = explode(',', $request->input('ids')); // Convertir el string en un array
    
        // Verificar si los ids están vacíos
        if (empty($ids)) {
            return redirect()->route('notas')->with('message', 'No se seleccionaron notas para eliminar.');
        }
    
        // Eliminar las notas seleccionadas por id_nota
        Notas::whereIn('id_nota', $ids)
            ->where('id_usuario', $user->id_usuario)
            ->delete();
    
        // Redirigir al usuario de vuelta con un mensaje de éxito
        return redirect()->route('notas')->with('message', 'Notas eliminadas correctamente.');
    }


    public function descargar(Request $request)
    {
        $ids = $request->input('notas', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No se seleccionaron notas.');
        }

        $notas = Notas::whereIn('id_nota', $ids)->get();

        if ($notas->count() === 1) {
            $nota = $notas->first();

            $pdf = Pdf::loadHTML($nota->contenido_html);
            return $pdf->download($nota->nombre_nota . '.pdf');
        } else {
            // Para múltiples PDFs, se debe crear un ZIP
            $zipFile = storage_path('app/public/notas_' . time() . '.zip');
            $zip = new \ZipArchive;

            if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
                foreach ($notas as $nota) {
                    $pdf = Pdf::loadHTML($nota->contenido_html)->output();
                    $zip->addFromString($nota->nombre_nota . '.pdf', $pdf);
                }
                $zip->close();
            }

            return response()->download($zipFile)->deleteFileAfterSend(true);
        }
    }


    // ------------------------------- guadar en ruta archivos -------------------------------
   public function saveAsPdf(Request $request)
{
    $ids = $request->input('notas', []);
    $idCarpeta = $request->input('id_carpeta');

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No se seleccionaron notas.');
    }

    // Validar carpeta seleccionada
    $carpeta = Carpeta::find($idCarpeta);
    if (!$carpeta) {
        return redirect()->back()->with('error', 'La carpeta seleccionada no existe.');
    }

    $notas = Notas::whereIn('id_nota', $ids)->get();

    // Usar el nombre de la carpeta seleccionada
    $carpetaNombre = $carpeta->nombre_carpeta;
    $destinationPath = storage_path("app/public/archivos/{$carpetaNombre}");

    // Crear la carpeta si no existe
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }

    foreach ($notas as $nota) {
        $pdf = Pdf::loadHTML($nota->contenido_html);
        $nombreArchivo = $nota->nombre_nota . '.pdf';
        $rutaCompleta = $destinationPath . '/' . $nombreArchivo;
        $rutaRelativa = "archivos/{$carpetaNombre}/" . $nombreArchivo;

        // Guardar el PDF
        $pdf->save($rutaCompleta);

        // Guardar en la base de datos
        Archivo::create([
            'nombre_archivo' => $nombreArchivo,
            'tipo_archivo' => 'application/pdf',
            'tamaño_archivo' => filesize($rutaCompleta),
            'id_carpeta' => $idCarpeta,
            'ruta' => $rutaRelativa,
        ]);
    }

    return redirect()->back()->with('success', 'PDF(s) generado(s) y guardado(s) correctamente.');
}

public function mover(Request $request)
{
    // Depuración opcional de consultas SQL
    DB::listen(function ($query) {
        Log::debug('SQL ejecutada:', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
        ]);
    });

    // Obtener el ID de la carpeta seleccionada
    $idCarpetaSeleccionada = $request->input('id_carpeta');
    
    Log::info('ID Carpeta recibida:', ['id_carpeta' => $idCarpetaSeleccionada]);

    // Obtener los IDs de las notas seleccionadas
    $notasSeleccionadas = $request->input('notas');
    Log::info('Notas seleccionadas:', ['notas' => $notasSeleccionadas]);

    // Verificar que se han seleccionado notas
    if (empty($notasSeleccionadas)) {
        Log::error('No se seleccionaron notas');
        return back()->with('error', 'Por favor selecciona al menos una nota.');
    }

    // Verificar si el ID de carpeta es válido
    $carpeta = Carpeta::find($idCarpetaSeleccionada);
    if (!$carpeta) {
        Log::error('Carpeta no encontrada');
        return back()->with('error', 'Carpeta no válida.');
    }

    // Recuperar las notas seleccionadas y asociarlas con la nueva carpeta
    foreach ($notasSeleccionadas as $notaId) {
        $nota = Notas::find($notaId);

        if ($nota) {
            // Generar el PDF a partir del contenido HTML de la nota
            $pdf = Pdf::loadHTML($nota->contenido_html); // Utiliza el contenido HTML de la nota
            $nombreArchivo = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nota->nombre_nota) . '.pdf';
            $pdfPath = storage_path('app/public/archivos/') . $nombreArchivo;
            $pdf->save($pdfPath); // Guarda el archivo PDF en el almacenamiento local

            // Guardar el archivo en la base de datos (guardamos el archivo PDF)
            $nuevoArchivo = Archivo::create([
                'nombre_archivo' => $nombreArchivo, // El nombre del archivo PDF
                'tipo_archivo' => 'application/pdf',  // Tipo de archivo PDF
                'tamaño_archivo' => filesize($pdfPath),  // Tamaño del archivo PDF
                'id_carpeta' => $idCarpetaSeleccionada,
                'ruta' => 'archivos/' . $nombreArchivo,  // Ruta del archivo guardado
            ]);

            //Log::info('Archivo PDF guardado en la base de datos:', ['nota_id' => $nuevoArchivo->id_archivo]);

            // Actualizar la carpeta de la nota en la base de datos
            $nota->id_carpetaN = $idCarpetaSeleccionada;
            $nota->save();
            Log::info('Nota movida correctamente:', ['nota_id' => $notaId]);
        } else {
            Log::warning('Nota no encontrada', ['nota_id' => $notaId]);
        }
    }

    return back()->with('success', 'Notas movidas y PDFs generados correctamente.');
}




}