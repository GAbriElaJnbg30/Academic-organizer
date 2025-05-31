<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Academic Organizer</title>

    <!----------------------------------- ICONO PESTAÑA -------------------------------------->
    <link rel="shortcut icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    <!---------------------------------------- CSS ------------------------------------------->
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" type="text/css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #007bff, #6a11cb, #ff7b54, #ff4b8a, #d6249f, #007bff);
            background-size: 400% 400%;
            animation: gradientAnimation 8s ease infinite;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            text-align: center;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 80%;
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        .eslogan {
            font-size: 1.2rem;
            color: black;
            margin-bottom: 30px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .message {
            font-size: 1.2rem;
            color: #256d85;
            font-weight: bold;
            background: #e6f7fa;
            padding: 20px;
            border: 1px solid #256d85;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(135deg, #fd5949, #d6249f);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
        }

        .button:hover {
            background: linear-gradient(135deg, #d6249f, #285AEB);
            transform: translateY(-2px);
        }

        .footer {
            font-size: 0.95rem;
            margin-top: 20px;
            color:rgb(255, 255, 255);
            background-color: rgba(0, 0, 0, 0.85);
        }

        @media screen and (max-width: 768px) {
            body {
                background-size: 400% 400%; /* Ajusta el fondo para pantallas más pequeñas */
            }

            .container {
                padding: 20px;
                max-width: 60%;
            }

            .logo {
                width: 100px; /* Reduce el tamaño del logo */
            }

            .eslogan {
                font-size: 1rem;
            }

            .message {
                font-size: 1rem;
                padding: 15px;
            }

            .button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <!----------------------------------- LOGO ----------------------------------->
        <img src="{{ asset('imagenes/ao.png') }}" alt="Logo Academic Organizer" class="logo">

        <!---------------------------- ESLOGAN DEL SITIO ---------------------------->
        <p class="eslogan">EL ÉXITO ACADÉMICO ESTÁ A SÓLO UN CLIC</p>

        <!---------------------------- MENSAJE DESTACADO ---------------------------->
        <div class="message">
            <!-- Mensajes de sesión -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @else
            ¡Registro exitoso!
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!------------------------------ BOTÓN IR AL LOGIN --------------------------->
        <a href="{{ route('iniciarsesion') }}" class="button">Ir al Login</a>

        <!----------------------------------- FOOTER -------------------------------->
        <div class="footer">
            &copy; 2025 Academic Organizer. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
