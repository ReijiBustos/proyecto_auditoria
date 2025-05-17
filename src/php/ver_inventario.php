<?php
session_start();
include 'src\php\db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'digitador') {
    header("Location: index.html");
    exit();
}

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT i.id_inventario, i.marca, i.modelo, i.serial, i.categoria, i.estado, p.username AS responsable
                          FROM inventario i
                          JOIN usuarios p ON i.id_persona = p.id_persona
                          ORDER BY i.id_inventario ASC");
    $inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario Tecnológico</title>
    <link rel="stylesheet" href="src/css/estilos.css">
    <script>
        function filtrarTabla() {
            const input = document.getElementById("buscar").value.toLowerCase();
            const filas = document.querySelectorAll("#tablaInventario tbody tr");

            filas.forEach(fila => {
                const textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(input) ? "" : "none";
            });
        }
    </script>
</head>
<body>
    <h2>Inventario Tecnológico</h2>

    <div class="search-box">
        <input type="text" id="buscar" class="search-input" placeholder="Buscar en la tabla..." onkeyup="filtrarTabla()">
    </div>

    <table id="tablaInventario">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Serial</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Responsable</th>
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
                    <td><?= htmlspecialchars($item['responsable']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="volver">
        <a href="src\php\panel_digitador.php" class="btn">← Volver al panel</a>
    </div>
</body>
</html>
