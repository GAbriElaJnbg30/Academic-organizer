<?php
// actualizar_estado.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la actualización del estado
    // (Este es solo un ejemplo, deberías implementar la lógica real aquí)
    echo "Estado actualizado con éxito!";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Estado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        label, input, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        input[type="submit"], button {
            width: auto;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <h2>Actualizar Estado</h2>
        
        <label for="estado">Estado:</label>
        <textarea id="estado" name="estado" rows="3" placeholder="Me siento..."></textarea>
        
        <label for="emoji">Emoji:</label>
        <input type="text" id="emoji" name="emoji" placeholder="Ingresa un emoji">
        
        <input type="submit" value="Actualizar">
        <button type="button" onclick="window.location.href='actualizar_perfil.php'">Cancelar</button>
    </form>
</body>
</html>