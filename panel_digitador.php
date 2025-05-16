<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'digitador') {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Digitador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .logout {
            background-color: #dc3545;
        }
        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> (Digitador)</h2>

        <a href="perfil_digitador.php" class="btn">Ver y editar perfil</a>
        <a href="registrar_inventario.php" class="btn">Registrar Inventario</a>
        <a href="ver_inventario.php" class="btn">Ver Inventario</a>

        <form method="post" action="logout.php" style="display:inline;">
            <input type="submit" class="btn logout" value="Cerrar Sesión">
        </form>
    </div>
</body>
</html>

