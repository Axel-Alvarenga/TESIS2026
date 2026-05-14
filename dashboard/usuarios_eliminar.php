<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

if ($_SESSION['admin_rol'] !== 'admin') {
    redirigirConMensaje('index.php', 'error', 'No tienes permisos');
}

$id = $_GET['id'] ?? 0;

if ($id == $_SESSION['admin_id']) {
    redirigirConMensaje('usuarios.php', 'error', 'No puedes eliminarte a ti mismo');
}

$stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

redirigirConMensaje('usuarios.php', 'exito', 'Usuario eliminado correctamente');
?>