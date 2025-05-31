<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;      // <- ¡Agregado!
use Illuminate\Support\Facades\Log;
use App\Models\Carpeta;
use Illuminate\Support\Facades\Storage;
use ZipArchive;



class ArchivoController extends Controller
{
    public function subir(Request $request)
{
    // Depuración opcional de consultas SQL
    DB::listen(function ($query) {
        Log::debug('SQL ejecutada:', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
        ]);
    });

    $idCarpetaSeleccionada = $request->input('id_carpeta');
    Log::info('ID Carpeta recibida:', ['id_carpeta' => $idCarpetaSeleccionada]);

    // Verificar si los archivos llegan correctamente
    Log::info('Archivos recibidos:', ['archivos' => $request->file('archivos')]);

    $request->validate([
        'archivos.*' => 'required|file|max:20480',
        'id_carpeta' => 'required|exists:Carpeta,id_carpeta',
    ]);

    // Asegúrate de que la carpeta no esté vacía
    if ($request->hasFile('archivos') && count($request->file('archivos')) > 0) {
        foreach ($request->file('archivos') as $archivo) {
            $nombreOriginal = $archivo->getClientOriginalName();
            $ruta = $archivo->storeAs('archivos', $nombreOriginal, 'public');

            //$ruta = $archivo->storeAs('archivos', 'public');
            Log::info('Ruta del archivo almacenado:', ['ruta' => $ruta]);

            // Aquí es donde estamos creando el registro en la base de datos
            $nuevoArchivo = Archivo::create([
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'tipo_archivo' => $archivo->getClientMimeType(),
                'tamaño_archivo' => $archivo->getSize(),
                'id_carpeta' => $idCarpetaSeleccionada,
                'ruta' => $ruta,  // Guardamos la ruta en la base de datos
            ]);

            // Verificar si el archivo se guardó correctamente en la BD
            Log::info('Archivo guardado en la base de datos:', ['archivo_id' => $nuevoArchivo->id_archivo]);
        }
    } else {
        Log::error('No se recibieron archivos');
    }

    return back()->with('success', 'Archivos subidos y registrados correctamente.');
}


// ----------------------------- Eliminar Archivos ---------------------------------
public function eliminarSeleccionados(Request $request)
{
    // Eliminar subcarpetas seleccionadas
    if ($request->has('subcarpetasSeleccionadas')) {
        foreach ($request->subcarpetasSeleccionadas as $id) {
            Carpeta::where('id_carpeta', $id)->delete();
        }
    }

    // Eliminar archivos seleccionados
    if ($request->has('archivosSeleccionados')) {
        foreach ($request->archivosSeleccionados as $nombreArchivo) {
            Archivo::where('nombre_archivo', $nombreArchivo)->delete();
        }
    }

    return redirect()->back()->with('success', 'Elementos eliminados correctamente');
}


// ----------------------- Descargar ZIP  -----------------------------------
public function descargarZip(Request $request)
{
    $carpetas = $request->input('carpetas');
    $archivos = $request->input('archivos');

    if (empty($carpetas) && empty($archivos)) {
        return response()->json(['error' => 'No se proporcionaron carpetas ni archivos.'], 400);
    }

    $idsCarpetas = $carpetas ? explode(',', $carpetas) : [];
    $nombresArchivos = $archivos ? explode(',', $archivos) : [];

    // Buscar las carpetas
    $carpetasConArchivos = \App\Models\Carpeta::whereIn('id_carpeta', $idsCarpetas)->get();

    // Buscar los archivos individuales
    $archivosIndividuos = \App\Models\Archivo::whereIn('nombre_archivo', $nombresArchivos)->get();

    if ($carpetasConArchivos->isEmpty() && $archivosIndividuos->isEmpty()) {
        return response()->json(['error' => 'No se encontraron carpetas ni archivos.'], 404);
    }

    // Nombre del ZIP (único usando timestamp)
    $nombreZip = 'descarga_' . time() . '.zip';
    $rutaZip = storage_path('app/public/zip/' . $nombreZip);

    $zip = new ZipArchive;
    if ($zip->open($rutaZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        // Agregar carpetas
        foreach ($carpetasConArchivos as $carpeta) {
            $this->agregarCarpetaAlZip($zip, $carpeta);
        }

        // Agregar archivos individuales
        foreach ($archivosIndividuos as $archivo) {
            $rutaArchivo = storage_path('app/public/archivos/' . $archivo->nombre_archivo);
            if (file_exists($rutaArchivo)) {
                $zip->addFile($rutaArchivo, $archivo->nombre_archivo);
            }
        }

        $zip->close();

        return response()->download($rutaZip)->deleteFileAfterSend(true);
    } else {
        return response()->json(['error' => 'No se pudo crear el archivo ZIP.'], 500);
    }
}


private function agregarCarpetaAlZip($zip, $carpeta, $rutaRelativa = '')
{
    $nombreCarpeta = $carpeta->nombre_carpeta;
    $rutaZipActual = trim($rutaRelativa . '/' . $nombreCarpeta, '/');

    // Asegurarse de que la carpeta esté incluida en el ZIP
    $zip->addEmptyDir($rutaZipActual);

    // Archivos de la carpeta actual
    $archivos = \App\Models\Archivo::where('id_carpeta', $carpeta->id_carpeta)->get();
    $tieneArchivos = false;

    foreach ($archivos as $archivo) {
        $rutaFisica = storage_path('app/public/' . $archivo->ruta);
        $rutaEnZip = $rutaZipActual . '/' . $archivo->nombre_archivo;

        if (\File::exists($rutaFisica)) {
            $zip->addFile($rutaFisica, $rutaEnZip);
            \Log::info("✅ Archivo añadido al ZIP: " . $rutaEnZip);
            $tieneArchivos = true;
        } else {
            \Log::warning("❌ Archivo no encontrado para ZIP: " . $rutaFisica);
        }
    }

    // Subcarpetas (recursivamente)
    $subcarpetas = \App\Models\Carpeta::where('parent_id', $carpeta->id_carpeta)->get();
    foreach ($subcarpetas as $subcarpeta) {
        $this->agregarCarpetaAlZip($zip, $subcarpeta, $rutaZipActual);
    }

    // Si no tiene archivos ni subcarpetas, aún así se ha creado la carpeta vacía
    if (!$tieneArchivos && $subcarpetas->isEmpty()) {
        \Log::info("✅ Carpeta vacía añadida al ZIP: " . $rutaZipActual);
    }
}



















public function testZip()
{
    $zip = new ZipArchive();
    $rutaZip = storage_path('app/test_prueba.zip');
    //dd($rutaZip); // te mostrará la ruta exacta


    // Eliminar si ya existe
    if (file_exists($rutaZip)) {
        unlink($rutaZip);
    }

    // Crear ZIP
    if ($zip->open($rutaZip, ZipArchive::CREATE) === TRUE) {
        $zip->addFromString('archivo_prueba.txt', 'Contenido de prueba para verificar ZIP.');
        $cerrado = $zip->close();

        if ($cerrado) {
            Log::info("ZIP creado correctamente: $rutaZip");
            return response()->download($rutaZip);
        } else {
            Log::error("Error al cerrar el archivo ZIP.");
            return response()->json(['error' => 'Error al cerrar el archivo ZIP.'], 500);
        }
    } else {
        Log::error("No se pudo abrir o crear el ZIP.");
        return response()->json(['error' => 'No se pudo abrir el ZIP.'], 500);
    }
}







}