<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Actividad</title>
</head>
<body>
    <h1>{{ $recordatorio->titulo }}</h1>
    <p><strong>Fecha:</strong> {{ $recordatorio->fecha }}</p>
    <p><strong>Hora:</strong> {{ $recordatorio->hora }}</p>
    <p><strong>Descripción:</strong> {{ $recordatorio->descripcion }}</p>
</body>
</html>
