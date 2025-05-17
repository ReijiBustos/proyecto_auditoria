<?php
include 'src\php\db.php';

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nueva_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validar token y fecha
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE token_recuperacion = :token AND expira_token > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $update = $conn->prepare("UPDATE usuarios SET password = :password, token_recuperacion = NULL, expira_token = NULL WHERE token_recuperacion = :token");
            $update->bindParam(':password', $nueva_pass);
            $update->bindParam(':token', $token);
            $update->execute();

            echo "<p style='color:green;'>Contraseña actualizada correctamente. <a href='index.html'>Iniciar sesión</a></p>";
        } else {
            echo "<p style='color:red;'>El token es inválido o ha expirado.</p>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="src\css\estilos.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <input type="submit" value="Actualizar Contraseña">
        </form>
    </div>
</body>
</html>
