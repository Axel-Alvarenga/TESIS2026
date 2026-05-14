<?php
// ==================== FUNCIONES REUTILIZABLES ====================

// Validar formato de email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Mostrar mensajes de éxito/error
function mostrarMensaje($tipo, $mensaje) {
    $icono = ($tipo == 'exito') ? 'check-circle' : 'exclamation-triangle';
    $clase = ($tipo == 'exito') ? 'mensaje-exito' : 'mensaje-error';
    return "<div class='mensaje $clase'><i class='fas fa-$icono'></i> $mensaje</div>";
}

// Obtener usuario por ID
function obtenerUsuarioPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Obtener usuario por email
function obtenerUsuarioPorEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

// Verificar si un email ya existe (excluyendo un ID opcional)
function emailExiste($pdo, $email, $excluirId = null) {
    if ($excluirId) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excluirId]);
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
    }
    return $stmt->fetch() !== false;
}

// Sanitizar entrada
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// Generar hash de contraseña
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verificar contraseña
function verificarPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Redirigir con mensaje
function redirigirConMensaje($url, $tipo, $mensaje) {
    $_SESSION['flash_message'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    header("Location: $url");
    exit;
}

// Mostrar mensaje flash (si existe)
function mostrarFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return mostrarMensaje($msg['tipo'], $msg['mensaje']);
    }
    return '';
}
?>