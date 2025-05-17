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
    <link rel="stylesheet" href="src\css\estilos.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <?php if ($rol == 'administrador'): ?>
            <p>Has iniciado sesión como <strong>administrador</strong>.</p>
        <form action="registrar.html">
            <input type="submit" value="Registrar nuevo usuario">
        </form>
        <form action="src\php\ver_inventario_admin.php" method="get" style="margin-top:10px;">
            <input type="submit" value="Ver mi inventario asignado">
        </form>
        <?php else: ?>
            <p>Has iniciado sesión como <strong>digitador</strong>.</p>
        <?php endif; ?>


        <form action="src\php\logout.php" method="POST">
            <input type="submit" value="Cerrar sesión">
        </form>
    </div>
</body>
</html>