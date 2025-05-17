<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: index.html");
    exit();
}

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el id_persona del administrador (asumiendo que la tabla usuarios tiene id_persona)
    $stmtUser = $conn->prepare("SELECT id_persona FROM usuarios WHERE username = :username");
    $stmtUser->execute([':username' => $_SESSION['username']]);
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        die("Usuario no encontrado.");
    }

    $id_persona = $userData['id_persona'];

    // Obtener los elementos de inventario asignados a este administrador
    $stmt = $conn->prepare("SELECT * FROM inventario WHERE id_persona = :id_persona ORDER BY id_inventario");
    $stmt->execute([':id_persona' => $id_persona]);
    $inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Asignado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h2>Inventario asignado a <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        
        <?php if (count($inventario) > 0): ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Serial</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventario as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id_inventario']) ?></td>
                            <td><?= htmlspecialchars($item['marca']) ?></td>
                            <td><?= htmlspecialchars($item['modelo']) ?></td>
                            <td><?= htmlspecialchars($item['serial']) ?></td>
                            <td><?= htmlspecialchars($item['categoria']) ?></td>
                            <td><?= htmlspecialchars($item['estado']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes elementos de inventario asignados.</p>
        <?php endif; ?>

        <p><a href="panel_admin.php">← Volver al panel</a></p>
    </div>
</body>
</html>
