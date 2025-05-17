<?php
session_start();
include 'src\php\db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'digitador') {
    header("Location: index.html");
    exit();
}

$mensaje_form = '';
$mensaje_csv = '';

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener lista de personas para el formulario
    $personas = $conn->query("SELECT id_persona, username FROM usuarios ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

    // Registro manual
    if (isset($_POST['registrar_manual'])) {
        $stmt = $conn->prepare("INSERT INTO inventario (marca, modelo, serial, categoria, estado, id_persona) VALUES (:marca, :modelo, :serial, :categoria, :estado, :id_persona)");
        $stmt->execute([
            ':marca' => $_POST['marca'],
            ':modelo' => $_POST['modelo'],
            ':serial' => $_POST['serial'],
            ':categoria' => $_POST['categoria'],
            ':estado' => $_POST['estado'],
            ':id_persona' => $_POST['id_persona']
        ]);
        $mensaje_form = "Elemento registrado correctamente.";
    }

    // Carga CSV sin id_inventario (generado automáticamente)
    if (isset($_POST['cargar_csv']) && isset($_FILES['archivo_csv'])) {
        $file = $_FILES['archivo_csv']['tmp_name'];
        $handle = fopen($file, "r");
        $first = true;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($first) {
                $first = false;
                // Validar encabezado
                $encabezado_esperado = ['marca', 'modelo', 'serial', 'categoria', 'estado', 'id_persona'];
                if ($data !== $encabezado_esperado) {
                    $mensaje_csv = "El archivo CSV debe tener las columnas: " . implode(", ", $encabezado_esperado);
                    break;
                }
                continue;
            }
            if (count($data) === 6) {
                $stmt = $conn->prepare("INSERT INTO inventario (marca, modelo, serial, categoria, estado, id_persona) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute($data);
            }
        }
        fclose($handle);

        if (!$mensaje_csv) {
            $mensaje_csv = "Archivo CSV cargado correctamente.";
        }
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Inventario</title>
    <link rel="stylesheet" href="src/css/estilos.css">
</head>

<body>
    <div class="container">
        <h2>Registrar Elemento de Inventario</h2>

        <?php if ($mensaje_form) echo "<p class='success'>$mensaje_form</p>"; ?>

        <form method="POST">
            <label>Marca:</label><input type="text" name="marca" required>
            <label>Modelo:</label><input type="text" name="modelo" required>
            <label>Serial:</label><input type="text" name="serial" required>
            <label>Categoría:</label><input type="text" name="categoria" required>
            <label>Estado:</label><input type="text" name="estado" required>
            <label>Persona Responsable:</label>
            <select name="id_persona" required>
                <option value="">Seleccione una persona</option>
                <?php foreach ($personas as $p): ?>
                    <option value="<?= $p['id_persona'] ?>"><?= htmlspecialchars($p['username']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="registrar_manual" value="Registrar Manualmente" class="btn">
        </form>

        <hr>

        <h2>Carga Masiva desde CSV</h2>
        <?php if ($mensaje_csv) echo "<p class='success'>$mensaje_csv</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Archivo CSV:</label>
            <input type="file" name="archivo_csv" accept=".csv" required>
            <input type="submit" name="cargar_csv" value="Cargar CSV" class="btn">
        </form>

        <p><a href="plantilla_inventario.csv" download class="btn">Descargar plantilla CSV</a></p>
        <p><a href="src\php\panel_digitador.php">← Volver al panel</a></p>
    </div>
</body>

</html>
