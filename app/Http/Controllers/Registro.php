<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Mail\RecuperarCuentaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Hash;

class Registro extends Controller
{
    public function register(Request $request)
    {
       
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

        // Convertir nombre y apellido a formato correcto (primera letra mayúscula, el resto minúscula)
        $request->merge([
            'nombre' => ucwords(strtolower($request->nombre)),  // Convierte a minúsculas y luego capitaliza la primera letra
            'apellido' => ucwords(strtolower($request->apellido)),  // Lo mismo para apellido
        ]);

        // Subir la foto de perfil, si existe
        $foto_perfil = null;
        if ($request->hasFile('foto_perfil')) {
            $foto_perfil = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        }

        // Crear un nuevo usuario - Registro
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
            'foto_perfil' => $foto_perfil, // Guardamos la ruta de la imagen
        ], [
            
        ]);

        // Mensaje de éxito
        session()->flash('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
        
        // Redirige a la página de login o cualquier otra que desees
        return redirect()->route('mensajeregistro'); 
    }

    // --------------------------------------- get crud --------------------------------------------
    public function mostrarUsuarios()
    {
        // Obtener todos los usuarios de la base de datos
        //$usuarios = Usuario::all();

        // Retornar la vista con los datos
        //return view('crud', compact('usuarios'));

        $usuarios = Usuario::paginate(10); // Cambia '10' por el número de resultados por página que desees
        
        return view('crud', compact('usuarios'));
    }

    // --------------------------------------------- Buscar -------------------------------------------------------------
    public function index(Request $request)
    {
        // Obtener el valor de búsqueda
        $query = $request->input('buscar'); 
        
        // Obtener el parámetro de ordenación (valor por defecto 'id_usuario')
        $orderBy = $request->input('order_by', 'id_usuario');
    
        // Consultar usuarios
        $usuarios = Usuario::query(); 
    
        // Si se realiza una búsqueda, aplicar el filtro
        if ($query) {
            $usuarios->where('nombre', 'LIKE', "%$query%")
                    ->orWhere('apellido', 'LIKE', "%$query%")
                    ->orWhere('nombre_usuario', 'LIKE', "%$query%")
                    ->orWhere('correo_electronico', 'LIKE', "%$query%");
        }
    
        // Aplicar ordenación a los resultados
        $usuarios = $usuarios->orderBy($orderBy)->paginate(10); // 10 es el número de resultados por página
    
        // Pasar los valores de búsqueda y ordenación a la vista para mantener el estado
        return view('crud', compact('usuarios', 'query', 'orderBy'));
    }
    

    // ------------------------------------------ Elimina Usuario - CRUD ---------------------------------------------------
    public function eliminarUsuario($id_usuario)
    {
        // Buscar el usuario por ID
        $usuario = Usuario::findOrFail($id_usuario);

        // Eliminar al usuario
        $usuario->delete();

        // Mensaje de éxito
        session()->flash('success', 'Usuario eliminado exitosamente.');

        // Redirigir a la página CRUD
        return redirect()->route('crud');
    }

    // ---------------------------------- Actualizar Usuario -------------------------------
    public function editarUsuario($id_usuario)
    {
        // Obtener el usuario por ID
        $usuario = Usuario::findOrFail($id_usuario);
    
        // Retornar los datos del usuario a la vista
        return response()->json($usuario);
    }
    
    public function actualizar(Request $request)
    {
        // Inicializamos las reglas de validación
        $rules = [
            'id_usuario' => 'required|exists:Usuario,id_usuario',
            'nombre' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u',
            'apellido' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/u',
            'nombre_usuario' => 'nullable|string|max:255|regex:/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]+$/u',
            'correo_electronico' => 'nullable|string|max:255|regex:/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'contraseña' => 'nullable|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[0-9]/|regex:/[@$!%*?&]/|confirmed',
            'fecha_nacimiento' => 'nullable|date_format:Y-m-d|before_or_equal:today|before_or_equal:' . now()->subYears(12)->toDateString(),
            'genero' => 'nullable|in:Masculino,Femenino,Otro',
            'telefono' => 'nullable|min:8|max:15|regex:/^[0-9]{8,15}$/',
            'rol' => 'required|in:1,2',
        ];

        // Solo se aplica la validación de 'unique' si el campo 'nombre_usuario' o 'correo_electronico' ha sido modificado
        if ($request->filled('nombre_usuario') && $request->nombre_usuario != Usuario::find($request->id_usuario)->nombre_usuario) {
            $rules['nombre_usuario'] .= '|unique:Usuario';
        }
        if ($request->filled('correo_electronico') && $request->correo_electronico != Usuario::find($request->id_usuario)->correo_electronico) {
            $rules['correo_electronico'] .= '|unique:Usuario';
        }
        if ($request->filled('telefono') && $request->telefono != Usuario::find($request->id_usuario)->telefono) {
            $rules['telefono'] .= '|unique:Usuario';
        }
        
        // Mensajes de error personalizados
        $messages = [
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
        ];

        // Validamos los datos con las reglas dinámicas
        $validatedData = $request->validate($rules, $messages);

        // Encuentra al usuario por su ID
        $usuario = Usuario::findOrFail($request->id_usuario);

        // Solo se actualizan los campos si fueron modificados
        if ($request->filled('nombre')) {
            $usuario->nombre = $request->nombre;
        }
        if ($request->filled('apellido')) {
            $usuario->apellido = $request->apellido;
        }
        if ($request->filled('nombre_usuario')) {
            $usuario->nombre_usuario = $request->nombre_usuario;
        }
        if ($request->filled('correo_electronico')) {
            $usuario->correo_electronico = $request->correo_electronico;
        }
        if ($request->filled('telefono')) {
            $usuario->telefono = $request->telefono;
        }
        if ($request->filled('fecha_nacimiento')) {
            $usuario->fecha_nacimiento = $request->fecha_nacimiento;
        }
        if ($request->filled('genero')) {
            $usuario->genero = $request->genero;
        }
        if ($request->filled('rol')) {
            $usuario->rol = $request->rol;
        }

        // Si se ha proporcionado una nueva contraseña, se actualiza
        if ($request->filled('contraseña')) {
            $usuario->contraseña = bcrypt($request->contraseña);
        }

        // Guardamos los cambios
        $usuario->save();

        // Redirigimos con un mensaje de éxito
        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }


    // ----------------------------------------- Recuperar Contraseña -----------------------------------------------
    public function recuperarCuenta(Request $request)
    {
        // Validar el correo electrónico
        $validated = $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Por favor, ingrese su correo electrónico.',
            'email.email' => 'Por favor, ingrese un correo electrónico válido.',
        ]);
    
        // Buscar el usuario
        $usuario = Usuario::where('correo_electronico', $validated['email'])->first();
    
        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo electrónico no está registrado en el sistema.'])->withInput();
        }
    
        // Generar token y fecha de expiración
        $token = Str::random(64);
        $fechaExpiracion = Carbon::now()->addMinutes(60); // El token expira en 1 hora
    
        // Guardar en la base de datos
        $usuario->token_recuperacion = $token;
        $usuario->fecha_expiracion_token = $fechaExpiracion;
        $usuario->save();
    
        // Enviar correo con el enlace
        $enlace = route('formulario.recuperar', ['token' => $token]);
        Mail::send('emails.recuperar', ['enlace' => $enlace], function ($message) use ($usuario) {
            $message->to($usuario->correo_electronico);
            $message->subject('Recuperación de cuenta - Academic Organizer');
        });
    
        // Respuesta al usuario
        return back()->with('success', 'Se ha enviado un correo con las instrucciones para recuperar tu cuenta.');
    }

    public function mostrarFormularioRecuperar($token)
    {
        $usuario = Usuario::where('token_recuperacion', $token)
            ->where('fecha_expiracion_token', '>=', Carbon::now())
            ->first();

        if (!$usuario) {
            return redirect()->route('iniciarsesion')->withErrors('El enlace de recuperación ha expirado o es inválido.');
        }

        return view('auth.recuperar', compact('token'));
    }

    public function restablecerCuenta(Request $request)
    {
        
        // Validación de la contraseña con las reglas especificadas
        $validated = $request->validate([
            'token' => 'required',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',    // Al menos una letra mayúscula
                'regex:/[a-z]/',    // Al menos una letra minúscula
                'regex:/[0-9]/',    // Al menos un número
                'regex:/[@$!%*?&]/', // Al menos un carácter especial
                'confirmed'         // Confirmación de la contraseña
            ],
        ], [
            // Mensajes personalizados de validación
            'password.string' => 'La contraseña debe ser una cadena de caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = Usuario::where('token_recuperacion', $validated['token'])
            ->where('fecha_expiracion_token', '>=', Carbon::now())
            ->first();

            

        if (!$usuario) {
            return back()->withErrors('El token es inválido o ha expirado.');
        }


        $usuario->contraseña = bcrypt($validated['password']);
        $usuario->token_recuperacion = null;
        $usuario->fecha_expiracion_token = null;
        $usuario->save();

        //dd($request->all());

        return redirect()->route('iniciarsesion')->with('success', 'Contraseña restablecida exitosamente.');

    }

    



}
