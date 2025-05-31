<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">


    <title>Manual de Usuario</title>
    <style>
        /* Asegura que el body y html ocupen toda la pantalla */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Elimina el scroll de la página principal */
        }

        /* Estilo para el iframe */
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
    <iframe src="{{ asset('documentos/manual_usuario.pdf') }}" title="Manual de Usuario PDF" scrolling="no"></iframe>
</body>
</html>