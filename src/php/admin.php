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

        <form action="src\php\logout.php" method="POST">
            <button type="submit" class="btn btn-logout">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>

