<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de {{ ucfirst($tablaActual) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('imagenes/icono.png') }}">
    



    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 100px;
        }
        .info {
            text-align: right;
            font-size: 10px;
            margin-bottom: 10px;
        }
        h2 {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('imagenes/ao.png') }}" alt="Logo" class="logo">
        <h2>Reporte de la tabla: {{ ucfirst($tablaActual) }}</h2>
    </div>

    <div class="info">
        Fecha y hora de generación: {{ $fechaHora }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($datos->first()?->getAttributes() ?? [] as $campo => $valor)
                    <th>{{ ucfirst($campo) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($datos as $fila)
                <tr>
                    @foreach ($fila->getAttributes() as $valor)
                        <td>{{ $valor }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
