<?php
session_start();
include 'src\php\db.php';

try {
    // Conexión PDO a PostgreSQL
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Obtener datos del formulario
    $login_input = $_POST['username'];  // Puede ser usuario o correo
    $password_input = $_POST['password'];

    // Consultar por usuario o correo
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = :login_input OR email = :login_input");
    $stmt->bindParam(':login_input', $login_input);
    $stmt->execute();

    // Verificar si existe
    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar contraseña
        if (password_verify($password_input, $user['password'])) {
            // Iniciar sesión
            $_SESSION['username'] = $user['username'];
            $_SESSION['rol'] = $user['rol'];

            // Redirigir según rol
            if ($user['rol'] === 'administrador') {
                header("Location: src\php\panel_admin.php");
            } elseif ($user['rol'] === 'digitador') {
                header("Location: src\php\panel_digitador.php");
            } else {
                echo "Rol no reconocido.";
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario o correo no encontrado.";
    }

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
