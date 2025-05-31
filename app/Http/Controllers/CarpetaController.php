<?php

namespace App\Http\Controllers;

use App\Models\Carpeta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Archivo; 
use ZipArchive;
use Illuminate\Support\Facades\Log;


class CarpetaController extends Controller
{
    // --------------------------------------- Función para mostrar la lista de carpetas --------------------------------------------
    public function crearCarpeta(Request $request)
    {
        // Obtener el parent_id de la carpeta (puede ser null si es carpeta principal)
        $parentId = $request->input('parent_id');
        $nombreCarpeta = $request->nombre_carpeta;

        // Verificar si la carpeta ya existe en el mismo nivel (mismo parent_id)
        $existeCarpeta = Carpeta::where('id_usuario', auth()->id())
            ->where('nombre_carpeta', $nombreCarpeta)
            ->where('parent_id', $parentId) // Asegurarse de que está en el mismo nivel
            ->exists();


            if ($existeCarpeta) {
                // Si la carpeta ya existe, agregar un sufijo numérico al nombre
                $i = 1;
                while (Carpeta::where('id_usuario', auth()->id())
                        ->where('nombre_carpeta', $nombreCarpeta . " ({$i})")
                        ->where('parent_id', $parentId) // Asegurarse de que está en el mismo nivel
                        ->exists()) {
                    $i++;  // Incrementa el número hasta que no exista una carpeta con ese nombre
                }
                $nombreCarpeta = $nombreCarpeta . " ({$i})";  // Crear el nombre con el sufijo
            }

            // Validar el nombre de la carpeta
            $request->validate([
                'nombre_carpeta' => 'required|string|max:255|:Carpeta,nombre_carpeta',
                'parent_id' => 'nullable|exists:Carpeta,id_carpeta', // Validar que el ID de la carpeta principal exista
            ], [
                // Mensaje personalizado si el nombre de la carpeta ya existe
                'nombre_carpeta' => 'La carpeta "' . $request->nombre_carpeta . '" ya existe.',
            ]);

            // Determinar si es una carpeta principal o una subcarpeta
            $parentId = $request->input('parent_id');

            // Crear la nueva carpeta en la base de datos
            Carpeta::create([
                'nombre_carpeta' => $nombreCarpeta,
                'fecha_creacion' => now(),
                'id_usuario' => auth()->id(),
                'parent_id' => $parentId, // parent_id será null si no se especifica, lo que significa que es una carpeta principal
            ]);

        
            // Redirigir a la vista adecuada
            if ($parentId) {
                // Si es subcarpeta, redirigir al contenido de la carpeta padre
                return redirect()->route('verContenido', ['id' => $parentId])
                    ->with('success', 'Subcarpeta creada exitosamente.');
            } else {
                // Si es carpeta principal, redirigir a la lista de carpetas
                return redirect()->route('archivos')
                    ->with('success', 'Carpeta principal creada exitosamente.');
            }
    }    

    public function mostrarCarpetas()
    {
        $usuario = auth()->user();

        if (!$usuario) {
            return redirect()->route('iniciarsesion');
        }
        
        $idUsuario = auth()->user()->id_usuario;
    
        // Carpetas principales (sin padre)
        $carpetas = Carpeta::where('id_usuario', $idUsuario)
            ->whereNull('parent_id')
            ->get();
    
        // Subcarpetas agrupadas por parent_id
        $subcarpetasRaw = Carpeta::where('id_usuario', $idUsuario)
            ->whereNotNull('parent_id')
            ->get();
    
        $subcarpetas = [];
        foreach ($subcarpetasRaw as $sub) {
            $subcarpetas[$sub->parent_id][] = $sub;
        }
    
        return view('archivos', compact('carpetas', 'subcarpetas'));
    }
    

    // Método para actualizar el nombre de la carpeta
    public function actualizarCarpeta(Request $request)
    {
        //dd($request->all());
        // Validar los datos
        $request->validate([
            'id_carpeta' => 'required|exists:Carpeta,id_carpeta',
            'nuevo_nombre' => 'required|string|max:255|unique:Carpeta,nombre_carpeta',
        ], [
            // Mensaje personalizado si el nombre de la carpeta ya existe
            'nuevo_nombre.unique' => 'La carpeta "' . $request->nuevo_nombre . '" ya existe.',
        ]);

        // Buscar la carpeta y actualizar el nombre
        $carpeta = Carpeta::findOrFail($request->input('id_carpeta'));
        $carpeta->nombre_carpeta = $request->input('nuevo_nombre');
        $carpeta->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('archivos')->with('success', 'Nombre de la carpeta actualizado exitosamente.');
    }

    // -------------------------------- Ver contenido de carpetas ----------------------------------
    public function verContenido($id)
    {
        $usuario = auth()->user();

        if (!$usuario) {
            return redirect()->route('iniciarsesion');
        }

        $idUsuario = auth()->user()->id_usuario;
    
        // Carpetas principales (sin padre)
        $carpetas = Carpeta::where('id_usuario', $idUsuario)
            ->whereNull('parent_id')
            ->get();
            
        // Subcarpetas agrupadas por parent_id
        $subcarpetasRaw = Carpeta::where('id_usuario', $idUsuario)
            ->whereNotNull('parent_id')
            ->get();

        //$notas = Notas::where('id_carpeta', $id)->where('id_usuario', $idUsuario)->get();

        $subcarpetas = [];
        foreach ($subcarpetasRaw as $sub) {
            $subcarpetas[$sub->parent_id][] = $sub;
        }

        // Buscar la carpeta por ID
        $carpeta = Carpeta::findOrFail($id);

        // Suponiendo que tienes una relación en el modelo Carpeta para archivos o subcarpetas
        $archivos = $carpeta->archivos; // Relación con los archivos
        $subcarpetas = $carpeta->subcarpetas; // Relación con subcarpetas, si aplica

        // Retornar una vista con el contenido
        //return view('carpetas.contenido', compact('carpeta', 'archivos', 'subcarpetas'));
        return view('carpetas.contenido', [
            'carpetas' => $carpetas,
            'archivos' => $archivos,
            'subcarpetas' => $subcarpetas,
            'carpetaPadre' => $carpeta, // esta línea es clave
        ]);
        
    }


    // ---------------------------- Eliminar carpetas Padre con su contenido ---------------------------------------
    public function eliminar(Request $request)
    {
        $ids = $request->input('carpetasSeleccionadas', []);

        foreach ($ids as $id) {
            $carpeta = Carpeta::find($id);
            if ($carpeta) {
                $this->eliminarRecursivamente($carpeta);
            }
        }

        return redirect()->back()->with('success', 'Las carpetas han sido eliminadas correctamente.');
    }

    private function eliminarRecursivamente($carpeta)
    {
        // Elimina archivos asociados a esta carpeta
        foreach ($carpeta->archivos as $archivo) {
            // Verifica que la ruta no sea null antes de intentar eliminar
            if (!is_null($archivo->ruta) && Storage::exists($archivo->ruta)) {
                Storage::delete($archivo->ruta);
            }
    
            $archivo->delete();
        }
    
        // Elimina subcarpetas recursivamente
        foreach ($carpeta->subcarpetas as $subcarpeta) {
            $this->eliminarRecursivamente($subcarpeta);
        }
    
        $carpeta->delete();
    }
    


    // ------------------------- Eliminar Subcarpetas ----------------------------
    public function eliminarSubcarpetas(Request $request)
    {
        $ids = $request->input('subcarpetasSeleccionadas', []);

        foreach ($ids as $id) {
            $carpeta = Carpeta::find($id);
            if ($carpeta) {
                // Elimina archivos y subcarpetas asociadas si lo deseas
                $carpeta->delete(); // Esto borra la subcarpeta
            }
        }

        return redirect()->back()->with('success', 'Subcarpetas eliminadas correctamente.');
    }


    // ----------------------- actualizar subcarpetas ---------------------------------------
    public function actualizar(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'id_carpeta' => 'required|exists:subcarpetas,id_carpeta',  // Asegúrate de que la subcarpeta existe
            //'nuevo_nombre' => 'required|string|max:255', // Asegúrate de que el nuevo nombre sea válido
            'nuevo_nombre' => 'required|string|max:255|unique:Carpeta,nombre_carpeta,' . $request->id_carpeta . ',id_carpeta',

        ]);

        // Encontrar la subcarpeta por su id
        $subcarpeta = Subcarpeta::find($request->id_carpeta);

        // Actualizar el nombre
        $subcarpeta->nombre_carpeta = $request->nuevo_nombre;
        $subcarpeta->save(); // Guardar los cambios

        $carpeta = Carpeta::findOrFail($request->id_carpeta);
        $carpeta->nombre_carpeta = $request->nuevo_nombre;
        $carpeta->save();

        // Redirigir a la carpeta actual
        return redirect($request->redirect_url)->with('success', 'Nombre actualizado correctamente.');
    }
    
    // --------------------------------- MOVER ARCHIVOS --------------------------------------
    public function moverCarpetas(Request $request)
    {
            $request->validate([
                'carpetas_seleccionadas' => 'required',
                'carpeta_destino' => 'required|exists:Carpeta,id_carpeta'
            ]);
        
            $ids = json_decode($request->carpetas_seleccionadas, true);
            $destino = $request->carpeta_destino;
        
            // No permitir mover a sí misma
            if (in_array($destino, $ids)) {
                return redirect()->back()->withErrors([
                    'carpeta_destino' => 'No puedes seleccionar la misma carpeta como destino.'
                ]);
            }
        
            foreach ($ids as $idCarpeta) {
                $carpeta = Carpeta::find($idCarpeta);
                if ($carpeta) {
                    $nombreOriginal = $carpeta->nombre_carpeta;
                    $nombreNuevo = $nombreOriginal;
                    $contador = 1;

                    // Evitar duplicado en carpeta destino
                    while (Carpeta::where('parent_id', $destino)
                        ->where('nombre_carpeta', $nombreNuevo)
                        ->exists()) {
                        $nombreNuevo = $nombreOriginal . ' (' . $contador . ')';
                        $contador++;
                    }

                    $carpeta->nombre_carpeta = $nombreNuevo;
                    $carpeta->parent_id = $destino;
                    $carpeta->save();

                    // Mover contenido relacionado recursivamente
                    $this->moverContenidoRecursivo($carpeta->id_carpeta, $carpeta->id_carpeta);
                }
            }

            return redirect()->route('archivos')->with('success', 'Carpetas y archivos movidos correctamente.');
    }

    private function moverContenidoRecursivo($carpetaId, $nuevoParentId)
    {
        $carpeta = Carpeta::with('subcarpetas', 'archivos')->find($carpetaId);
        if (!$carpeta) return;

        // Mover subcarpetas
        foreach ($carpeta->subcarpetas as $subcarpeta) {
            $subcarpeta->parent_id = $nuevoParentId;
            $subcarpeta->save();
            $this->moverContenidoRecursivo($subcarpeta->id_carpeta, $subcarpeta->id_carpeta);
        }

        // Mover archivos
        foreach ($carpeta->archivos as $archivo) {
            $archivo->id_carpeta = $nuevoParentId;
            $archivo->save();
        }
    }

}