<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Hash;

class Alta extends Controller
{
    public function register(Request $request)
    {
        Log::info('Iniciando registro de usuario', $request->all());

        
            // Validar los datos
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u', // Solo letras, espacios, y caracteres con acento
                'apellido' => 'required|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u', // Solo letras, espacios, y caracteres con acento
                'nombre_usuario' => 'required|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]+$/u|unique:Usuario',
                'correo_electronico' => 'required|string|max:255|regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:Usuario',
                'contraseña' => 'required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/|confirmed', // Confirmed asegura que coincida con contraseña_confirmation
                'fecha_nacimiento' => 'required|date_format:Y-m-d|before_or_equal:today|before_or_equal:' . now()->subYears(12)->toDateString(),
                'genero' => 'required|in:Masculino,Femenino,Otro',
                'telefono' => 'required|min:8|max:15|regex:/^[0-9]{8,15}$/|unique:Usuario',
                'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Permite imágenes
            ], [
                'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
                'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
                'nombre_usuario.regex' => 'El usuario solo puede contener letras, números y "_".',
                'nombre_usuario.unique' => 'El nombre de usuario ya está en uso.',
                'correo_electronico.regex' => 'Dirección de correo electrónico no válido.',
                'correo_electronico.unique' => 'Este correo electrónico ya está registrado.',
                'contraseña.string' => 'La contraseña debe ser una cadena de caracteres.',
                'contraseña.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'contraseña.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
                'contraseña.confirmed' => 'Las contraseñas no coinciden.',
                'fecha_nacimiento.date_format' => 'El formato de la fecha debe ser AAAA-MM-DD.',
                'fecha_nacimiento.before_or_equal' => 'Debes tener al menos 12 años para registrarte.',
                'genero.in' => 'Seleccione un género válido.',
                'telefono.regex' => 'El número de teléfono debe contener solo dígitos.',
                'telefono.min' => 'El número de teléfono debe tener al menos 8 dígitos.',
                'telefono.max' => 'El número de teléfono no puede exceder los 15 dígitos.',
                'telefono.unique' => 'Este número de teléfono ya está registrado.',
                
            ]);

            Log::info('Validación exitosa', $validated);

            // Convertir nombre y apellido a formato correcto
            $request->merge([
                'nombre' => ucwords(strtolower($request->nombre)),
                'apellido' => ucwords(strtolower($request->apellido)),
            ]);

            // Crear un nuevo usuario
            $user = Usuario::create([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'nombre_usuario' => $validated['nombre_usuario'],
                'correo_electronico' => $validated['correo_electronico'],
                'contraseña' => Hash::make($validated['contraseña']),
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'genero' => $validated['genero'],
                'telefono' => $validated['telefono'],
                'rol' => 2, // Por defecto, asignamos el rol UGeneral
            ]);

            Log::info('Usuario creado exitosamente', ['user_id' => $user->id]);

            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => '¡Registro exitoso!.'
            ]);
        
    }


    
}