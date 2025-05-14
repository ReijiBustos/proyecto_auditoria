<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'digitador') {
    header("Location: index.html");
    exit();
}

$username = $_SESSION['username'];
$mensaje = '';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos actuales del usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nuevo_email = $_POST['email'];
        $nuevo_nombre = $_POST['nombre'];
        $nueva_contrasena = $_POST['password'];

        if (!empty($nueva_contrasena)) {
            $password_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE usuarios SET email = :email, nombre = :nombre, password = :password WHERE username = :username");
            $update->bindParam(':password', $password_hash);
        } else {
            $update = $conn->prepare("UPDATE usuarios SET email = :email, nombre = :nombre WHERE username = :username");
        }

        $update->bindParam(':email', $nuevo_email);
        $update->bindParam(':nombre', $nuevo_nombre);
        $update->bindParam(':username', $username);
        $update->execute();

        $mensaje = "Perfil actualizado correctamente.";

        // Actualizar datos para mostrarlos de nuevo
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    $mensaje = "Error de conexión: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Digitador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h2>Editar Perfil</h2>

        <?php if ($mensaje): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['username']) ?>" required>
            </div>

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Nueva contraseña (dejar vacío si no deseas cambiarla)</label>
                <input type="password" name="password">
            </div>

            <input type="submit" class="btn" value="Guardar Cambios">
        </form>

        <div class="volver">
            <a href="panel_digitador.php">← Volver al panel</a>
        </div>
    </div>
</body>
</html>

