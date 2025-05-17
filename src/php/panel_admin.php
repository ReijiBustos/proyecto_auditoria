<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$rol = $_SESSION['rol'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 15px;    
            border-radius: 6px;
            width: 100%;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
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
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <?php if ($rol == 'administrador'): ?>
            <p>Has iniciado sesión como <strong>administrador</strong>.</p>
            <form action="registrar.html">
                <input type="submit" value="Registrar nuevo usuario">
            </form>
            <form action="ver_inventario_admin.php" method="get" style="margin-top:10px;">
                <input type="submit" value="Ver mi inventario asignado">
            </form>
        <?php else: ?>
            <p>Has iniciado sesión como <strong>digitador</strong>.</p>
        <?php endif; ?>


        <form action="logout.php" method="POST">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>
</body>

</html>