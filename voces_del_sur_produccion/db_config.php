<?php
// Configuración para PRODUCCIÓN (Webuzo)
// ¡¡¡COMPLETARÁS ESTOS DATOS DESPUÉS DE CREAR LA BD EN WEBUZO!!!

$host = 'localhost';     // Siempre 'localhost' en Webuzo
$dbname = '';            // ← LO ANOTARÁS DESPUÉS
$username = '';          // ← LO ANOTARÁS DESPUÉS
$password = '';          // ← LA CONTRASEÑA QUE ELIJAS

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log($e->getMessage());
    die("⚠️ Error de conexión. Por favor, intenta más tarde.");
}
?>