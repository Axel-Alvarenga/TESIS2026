<?php
function validarEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function mostrarMensaje(string $tipo, string $mensaje): string {
    $icono = ($tipo == 'exito') ? 'check-circle' : 'exclamation-triangle';
    $clase = ($tipo == 'exito') ? 'mensaje-exito' : 'mensaje-error';
    return "<div class='mensaje $clase'><i class='fas fa-$icono'></i> $mensaje</div>";
}

function obtenerUsuarioPorId($pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function obtenerUsuarioPorEmail($pdo, string $email) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function emailExiste($pdo, string $email, ?int $excluirId = null): bool {
    if ($excluirId) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $excluirId]);
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
    }
    return $stmt->fetch() !== false;
}

function sanitizar(string $dato): string {
    return htmlspecialchars(strip_tags(trim($dato)));
}

function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verificarPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

function redirigirConMensaje(string $url, string $tipo, string $mensaje): void {
    $_SESSION['flash_message'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
    header("Location: $url");
    exit;
}

function mostrarFlashMessage(): string {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return mostrarMensaje($msg['tipo'], $msg['mensaje']);
    }
    return '';
}
?>