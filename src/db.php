<?php
$host = "localhost";
$dbname = "Proyecto";
$user = "usuario_inseguro";
$dbpass = "1234";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
