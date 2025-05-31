<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordatoriosController extends Controller
{
    // -------------------------------------- Crear recordatorios ---------------------------------------
    public function store(Request $request)
    {
        // Verificar si el checkbox está marcado y convertirlo a un valor booleano
        $request->merge([
            'recordatorio_activado' => $request->has('recordatorio_activado')
        ]);

        // Validar los datos del formulario
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'descripcion' => 'nullable|string',
            'recordatorio_activado' => 'required|boolean',
        ]);

        // Crear el recordatorio con los datos validados
        $recordatorio = new Recordatorio([
            'titulo' => $validated['titulo'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'descripcion' => $validated['descripcion'],
            'recordatorio_activado' => $validated['recordatorio_activado'],
            'id_usuario' => Auth::id(), // El ID del usuario autenticado
        ]);

        try {
            // Intentar guardar el recordatorio en la base de datos
            $recordatorio->save();
        } catch (QueryException $e) {
            // Si ocurre un error en la base de datos, capturarlo y mostrar el mensaje
            dd('Error al guardar el recordatorio: ' . $e->getMessage());
        }

        // Redirigir al usuario a la lista de recordatorios con un mensaje de éxito
        return redirect()->route('recordatorios')->with('success', 'Recordatorio guardado exitosamente.');
    }

    // -------------------------------------- MOstrar recordatorios ---------------------------------------
    public function index()
    {
        // Obtener los recordatorios del usuario autenticado
        $recordatorios = Recordatorio::where('id_usuario', Auth::id())->get();

        // Verificar si se obtuvieron recordatorios
        if ($recordatorios->isEmpty()) {
            // Si no hay recordatorios, puedes pasar un valor adicional para mostrar un mensaje
            return view('recordatorios')->with('recordatorios', $recordatorios);
        }

        // Pasar los recordatorios a la vista
        return view('recordatorios', compact('recordatorios'));
    }

    // -------------------------------------- Eliminar recordatorios ---------------------------------------
    public function eliminarSeleccionados(Request $request)
    {
        $ids = explode(',', $request->input('selectedIds'));
        Recordatorio::whereIn('id_recordatorio', $ids)->where('id_usuario', Auth::id())->delete();

        return redirect()->route('recordatorios')->with('success', 'Recordatorios eliminados correctamente.');
    }

    // -------------------------------------- Abrir recordatorio ---------------------------------------
    public function edit($id)
    {
        $recordatorio = Recordatorio::findOrFail($id);
        return view('actualizarrecordatorio', compact('recordatorio'));
    }


    public function update(Request $request, $id)
    {
        // Convertir el valor del checkbox a booleano
        $request->merge([
            'recordatorio_activado' => $request->has('recordatorio_activado')
        ]);

        // Validar datos
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'descripcion' => 'nullable|string',
            'recordatorio_activado' => 'required|boolean',
        ]);

        // Buscar el recordatorio
        $recordatorio = Recordatorio::where('id_usuario', Auth::id())
                        ->findOrFail($id);

        // Actualizar los campos
        $recordatorio->update($validated);

        return redirect()->route('recordatorios')->with('success', 'Recordatorio actualizado correctamente.');
    }
    
}