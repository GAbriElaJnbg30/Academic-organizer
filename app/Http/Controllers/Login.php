<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $credentials = $request->only('nombre_usuario', 'contraseña');

        // Buscar el usuario por nombre de usuario
        $user = Usuario::where('nombre_usuario', $credentials['nombre_usuario'])->first();

        // Verificar si el usuario existe
        if (!$user) {
            // Si el usuario no existe, no mantener el valor de nombre_usuario
            return back()->withErrors(['nombre_usuario' => 'Nombre de usuario incorrecto o no existe'])
                        ->withInput(['contraseña' => $credentials['contraseña']]); // Mantener solo la contraseña
        }

        // Verificar la contraseña
        if (!Hash::check($credentials['contraseña'], $user->contraseña)) {
            // Si la contraseña es incorrecta, mantener el valor de nombre_usuario y no el de la contraseña
            return back()->withErrors(['contraseña' => 'Contraseña incorrecta'])
                        ->withInput(['nombre_usuario' => $credentials['nombre_usuario']]); // Mantener solo el nombre_usuario
        }

        // Iniciar sesión si las credenciales son correctas
        Auth::login($user);

        // Supongamos que `foto_perfil` es una columna en tu tabla de usuarios
        $fotoPerfil = $user->foto_perfil ? 'perfil/' . $user->foto_perfil : null;

        // Pasar nombre y apellido a la sesión para mostrar en la vista
        session()->put('nombre', $user->nombre);
        session()->put('apellido', $user->apellido);
        session()->put('nombre_usuario', $user->nombre_usuario);
        session()->put('correo_electronico', $user->correo_electronico);
        session()->put('fecha_nacimiento', $user->fecha_nacimiento);
        session()->put('genero', $user->genero);
        session()->put('telefono', $user->telefono);
        session()->put('rol', $user->rol);
        session()->put('foto_perfil', $user->foto_perfil);

        // Agregar mensaje flash de autenticación exitosa
        //session()->flash('success', 'Autenticado exitosamente');

        // Redirigir dependiendo del rol y pasar el rol a la vista
        if ($user->rol == 1) {
            return redirect()->route('abienvenida');
        } elseif ($user->rol == 2) {
            return redirect()->route('ubienvenida');
        }
    }


    /* ---------------------------------------- cerrar sesión --------------------------------------- */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if (!auth()->check()) {
            return redirect()->route('iniciarsesion');
        }
        
        //return redirect->route('iniciarsesion'); // O la ruta que uses para tu inicio de sesión
        return redirect()->route('iniciarsesion');

    }


    // ---------------------------------------- Actualizar Foto de Perfil --------------------------------------------
    public function actualizarPerfil(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'nombre' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u', // Solo letras, espacios, y caracteres con acento
            'apellido' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u', // Solo letras, espacios, y caracteres con acento
            'nombre_usuario' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]+$/u|unique:Usuario',
            'correo_electronico' => 'nullable|string|max:255|regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:Usuario',
            'contraseña' => 'nullable|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/|confirmed', // Confirmed asegura que coincida con contraseña_confirmation
            'fecha_nacimiento' => 'nullable|date_format:Y-m-d|before_or_equal:today|before_or_equal:' . now()->subYears(12)->toDateString(),
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'telefono' => 'nullable|min:8|max:15|regex:/^[0-9]{8,15}$/|unique:Usuario',
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
            'fecha_nacimiento.before_or_equal' => 'Debes tener al menos 12 años.',
            'genero.in' => 'Seleccione un género válido.',
            'telefono.regex' => 'El número de teléfono debe contener solo dígitos.',
            'telefono.min' => 'El número de teléfono debe tener al menos 8 dígitos.',
            'telefono.max' => 'El número de teléfono no puede exceder los 15 dígitos.',
            'telefono.unique' => 'Este número de teléfono ya está registrado.',
            'foto_perfil.image' => 'El archivo debe ser una imagen.',
            'foto_perfil.mimes' => 'El formato de la imagen no es válido. Los formatos permitidos son: jpeg, png, jpg, gif, svg.',
        ]);
    
        // Obtener al usuario autenticado
        $user = Auth::user();

        // Verificar si el nombre_usuario ya existe y pertenece a otro usuario
        if ($request->has('nombre_usuario') && $request->nombre_usuario) {
            $nombreUsuarioExiste = Usuario::where('nombre_usuario', $request->nombre_usuario)
                ->where('id_usuario', '!=', $user->id) // Asegurarse de que no sea el usuario actual
                ->exists();

            if ($nombreUsuarioExiste) {
                return redirect()->back()->withErrors(['nombre_usuario' => 'El nombre de usuario ya existe.'])->withInput();
            }
        }
    
        // Si el usuario sube una nueva foto, guardarla
        if ($request->hasFile('foto_perfil') && $request->file('foto_perfil')->isValid()) {
            // Guardar la nueva foto en la carpeta `public/storage/perfil`
            $fotoPath = $request->file('foto_perfil')->store('perfil', 'public');
            $user->foto_perfil = $fotoPath; // Actualizar la ruta de la foto en el usuario
        }
    
        // Actualizar otros campos si se proporcionan
        if ($request->has('nombre') && $request->nombre) {
            $user->nombre = $request->nombre;
        }
    
        if ($request->has('apellido') && $request->apellido) {
            $user->apellido = $request->apellido;
        }
    
        if ($request->has('nombre_usuario') && $request->nombre_usuario) {
            $user->nombre_usuario = $request->nombre_usuario;
        }
    
        if ($request->has('correo_electronico') && $request->correo_electronico) {
            $user->correo_electronico = $request->correo_electronico;
        }
    
        if ($request->has('contraseña') && $request->contraseña) {
            $user->contraseña = bcrypt($request->contraseña); // Asegúrate de cifrar la contraseña
        }
    
        if ($request->has('fecha_nacimiento') && $request->fecha_nacimiento) {
            $user->fecha_nacimiento = $request->fecha_nacimiento;
        }
    
        if ($request->has('genero') && $request->genero) {
            $user->genero = $request->genero;
        }
    
        if ($request->has('telefono') && $request->telefono) {
            $user->telefono = $request->telefono;
        }
    
        // Guardar los cambios en la base de datos
        $user->save();
    
        // Actualizar la sesión para reflejar el cambio de datos
        session(['foto_perfil' => $user->foto_perfil]);
        session()->put('nombre', $user->nombre);
        session()->put('apellido', $user->apellido);
        session()->put('nombre_usuario', $user->nombre_usuario);
        session()->put('correo_electronico', $user->correo_electronico);
        session()->put('fecha_nacimiento', $user->fecha_nacimiento);
        session()->put('genero', $user->genero);
        session()->put('telefono', $user->telefono);
        session()->put('rol', $user->rol);
    
        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }



    
    
}