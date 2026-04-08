<?php
$host = 'localhost';
$dbname = 'voces_del_sur';
$username = 'root';
$password = ''; // Si pusiste contraseña en MySQL, escríbela aquí

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // No pongas echo aquí, silencia la conexión
} catch(PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>