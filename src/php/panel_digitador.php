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
    <link rel="stylesheet" href="src/css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> (Digitador)</h2>

        <a href="src\php\perfil_digitador.php" class="btn">Ver y editar perfil</a>
        <a href="src\php\registrar_inventario.php" class="btn">Registrar Inventario</a>
        <a href="src\php\ver_inventario.php" class="btn">Ver Inventario</a>

        <form method="post" action="src\php\logout.php" style="display:inline;">
            <input type="submit" class="btn logout" value="Cerrar Sesión">
        </form>
    </div>
</body>
</html>

