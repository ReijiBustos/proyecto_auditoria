<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$usuario = $_SESSION['username'];
$rol = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 16px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-logout {
            background-color: #dc3545;
        }

        .btn-logout:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Bienvenido, <?php echo htmlspecialchars($usuario); ?>!</h2>
        <p>Rol: <strong><?php echo htmlspecialchars($rol); ?></strong></p>

        <?php if ($rol === 'administrador'): ?>
            <form action="registrar.html" method="GET">
                <button class="btn">Registrar nuevo usuario</button>
            </form>
        <?php endif; ?>

        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-logout">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>

