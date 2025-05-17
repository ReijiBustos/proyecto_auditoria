<?php
include 'db.php';

function generarToken($longitud = 32) {
    return bin2hex(random_bytes($longitud / 2));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $token = generarToken();
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $update = $conn->prepare("UPDATE usuarios SET token_recuperacion = :token, expira_token = :expira WHERE email = :email");
            $update->bindParam(':token', $token);
            $update->bindParam(':expira', $expira);
            $update->bindParam(':email', $email);
            $update->execute();

            $enlace = "http://localhost/nueva_contrasena.php?token=$token";

            echo "<p style='color:green;'>Se ha enviado un enlace de recuperación (simulado):</p>";
            echo "<p><a href='$enlace'>$enlace</a></p>";

        } else {
            echo "<p style='color:red;'>No se encontró una cuenta con ese correo.</p>";
        }

    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form method="POST">
            <div class="form-group">
                <label for="email">Ingresa tu correo electrónico:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <input type="submit" value="Recuperar">

            <div class="form-group" style="margin-top: 10px;">
                <a href="index.html" style="text-decoration: none;">← Volver al login</a>
            </div>
        </form>
    </div>
</body>
</html>
