<?php
// auth.php - Verifica que el usuario haya iniciado sesión
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Asegurar datos de sesión completos
if (!isset($_SESSION['admin_rol']) && isset($_SESSION['admin_email'])) {
    require_once '../db_config.php';
    require_once 'functions.php';
    $user = obtenerUsuarioPorEmail($pdo, $_SESSION['admin_email']);
    if ($user) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_rol'] = $user['rol'];
        $_SESSION['admin_nombre'] = $user['nombre'];
    }
}

// Timeout de 30 minutos
$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header('Location: login.php?expirado=1');
    exit;
}
$_SESSION['last_activity'] = time();
?>