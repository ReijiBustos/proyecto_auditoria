<?php
/**
 * config.php
 *
 * Configuración de la conexión a PostgreSQL de forma segura usando variables de entorno.
 */

// Obtener las variables de entorno para la conexión a la base de datos
$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'Proyecto';
$user = $_ENV['DB_USER'] ?? 'usuario_inseguro';
$password = $_ENV['DB_PASSWORD'] ?? '1234';

try {
    // Crear conexión segura a PostgreSQL con PDO
    $conn = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ]
    );
} catch (PDOException $e) {
    // Registrar errores en un log y no mostrarlos al usuario
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión. Contacte al administrador.");
}
?>
