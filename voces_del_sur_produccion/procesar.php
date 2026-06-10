<?php
session_start();
require_once 'db_config.php';
require_once 'rate_limit.php';

$ip = $_SERVER['REMOTE_ADDR'];

// ==================== LIMPIAR REGISTROS ANTIGUOS ====================
if (function_exists('limpiarRegistrosAntiguos')) {
    limpiarRegistrosAntiguos();
}

// ==================== LIMPIAR SESIÓN SI PASÓ EL TIEMPO ====================
// Esto permite que después de 1 minuto se pueda volver a responder
$clave_tiempo_envio = 'ultimo_envio_timestamp';

if (isset($_SESSION[$clave_tiempo_envio])) {
    $tiempo_transcurrido = time() - $_SESSION[$clave_tiempo_envio];
    if ($tiempo_transcurrido >= 60) {
        // Si pasó más de 1 minuto, limpiar toda la sesión de envíos
        unset($_SESSION['envios_realizados']);
        unset($_SESSION[$clave_tiempo_envio]);
        unset($_SESSION['encuesta_enviada']);
    }
}

// ==================== VERIFICAR IP BLOQUEADA ====================
if (function_exists('ipBloqueada') && ipBloqueada($ip)) {
    http_response_code(403);
    die('❌ Acceso denegado. IP bloqueada.');
}

// ==================== RATE LIMITING (1 envío cada 60 segundos) ====================
if (function_exists('check_rate_limit')) {
    check_rate_limit($ip, 1, 60);
}

// ==================== VERIFICAR SI YA RESPONDIÓ EN ESTA SESIÓN ====================
if (isset($_SESSION['envios_realizados']) && $_SESSION['envios_realizados'] >= 1) {
    // Ya hay un registro de envío, verificar tiempo
    if (isset($_SESSION[$clave_tiempo_envio])) {
        $tiempo_transcurrido = time() - $_SESSION[$clave_tiempo_envio];
        
        if ($tiempo_transcurrido < 60) {
            // Aún no ha pasado 1 minuto
            $espera = 60 - $tiempo_transcurrido;
            header('Location: error_rate_limit.php?espera=' . $espera);
            exit;
        }
        // Si pasó 1 minuto, el código de arriba ya limpió la sesión
        // Por lo tanto, no debería entrar aquí
    }
}

// ==================== HONEYPOT (Anti-bot) ====================
if (!empty($_POST['website']) || isset($_POST['confirm'])) {
    error_log("🤖 Bot detectado - IP: $ip");
    http_response_code(403);
    die('❌ Acceso denegado.');
}

// ==================== VALIDAR CSRF ====================
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    error_log("⚠️ CSRF inválido - IP: $ip");
    die('❌ Error de seguridad: token CSRF inválido.');
}

// ==================== VERIFICAR MÉTODO POST ====================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ==================== FUNCIÓN DE SANITIZACIÓN ====================
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// ==================== PROCESAR DATOS ====================
$p9_critica = isset($_POST['p9_critica']) ? implode(',', $_POST['p9_critica']) : '';
$permiso_padres = isset($_POST['permiso_padres']) ? 'si' : 'no';

$p4b_situacion = sanitizar($_POST['p4b_situacion'] ?? '');
$p4b_area = sanitizar($_POST['p4b_area'] ?? '');
$p4b_movilidad = sanitizar($_POST['p4b_movilidad'] ?? '');

// ==================== INSERTAR EN BASE DE DATOS ====================
$sql = "INSERT INTO respuestas (
    ip, p1_anio, p2_parroquia, p3_pertenencia, p4_atraccion,
    p4b_situacion, p4b_area, p4b_movilidad,
    p5_espiritualidad, p6_familia, p7_proyecto, p8_vocacion,
    p9_critica, p10_esperanza, campo_libre, permiso_padres,
    comentario_bloque2, comentario_bloque3, comentario_bloque4,
    comentario_p4b1, comentario_p4b2, comentario_p4b3,
    comentario_bloque5, comentario_bloque6, 
    comentario_bloque7, comentario_bloque8, comentario_bloque9
) VALUES (
    :ip, :p1_anio, :p2_parroquia, :p3_pertenencia, :p4_atraccion,
    :p4b_situacion, :p4b_area, :p4b_movilidad,
    :p5_espiritualidad, :p6_familia, :p7_proyecto, :p8_vocacion,
    :p9_critica, :p10_esperanza, :campo_libre, :permiso_padres,
    :comentario_bloque2, :comentario_bloque3, :comentario_bloque4,
    :comentario_p4b1, :comentario_p4b2, :comentario_p4b3,
    :comentario_bloque5, :comentario_bloque6, 
    :comentario_bloque7, :comentario_bloque8, :comentario_bloque9
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':ip' => $ip,
    ':p1_anio' => sanitizar($_POST['p1_anio'] ?? ''),
    ':p2_parroquia' => sanitizar($_POST['p2_parroquia'] ?? ''),
    ':p3_pertenencia' => sanitizar($_POST['p3_pertenencia'] ?? ''),
    ':p4_atraccion' => sanitizar($_POST['p4_atraccion'] ?? ''),
    ':p4b_situacion' => $p4b_situacion,
    ':p4b_area' => $p4b_area,
    ':p4b_movilidad' => $p4b_movilidad,
    ':p5_espiritualidad' => sanitizar($_POST['p5_espiritualidad'] ?? ''),
    ':p6_familia' => sanitizar($_POST['p6_familia'] ?? ''),
    ':p7_proyecto' => sanitizar($_POST['p7_proyecto'] ?? ''),
    ':p8_vocacion' => sanitizar($_POST['p8_vocacion'] ?? ''),
    ':p9_critica' => $p9_critica,
    ':p10_esperanza' => sanitizar($_POST['p10_esperanza'] ?? ''),
    ':campo_libre' => sanitizar($_POST['campo_libre'] ?? ''),
    ':permiso_padres' => $permiso_padres,
    ':comentario_bloque2' => sanitizar($_POST['comentario_bloque2'] ?? ''),
    ':comentario_bloque3' => sanitizar($_POST['comentario_bloque3'] ?? ''),
    ':comentario_bloque4' => sanitizar($_POST['comentario_bloque4'] ?? ''),
    ':comentario_p4b1' => sanitizar($_POST['comentario_p4b1'] ?? ''),
    ':comentario_p4b2' => sanitizar($_POST['comentario_p4b2'] ?? ''),
    ':comentario_p4b3' => sanitizar($_POST['comentario_p4b3'] ?? ''),
    ':comentario_bloque5' => sanitizar($_POST['comentario_bloque5'] ?? ''),
    ':comentario_bloque6' => sanitizar($_POST['comentario_bloque6'] ?? ''),
    ':comentario_bloque7' => sanitizar($_POST['comentario_bloque7'] ?? ''),
    ':comentario_bloque8' => sanitizar($_POST['comentario_bloque8'] ?? ''),
    ':comentario_bloque9' => sanitizar($_POST['comentario_bloque9'] ?? '')
]);

// Marcar que ya envió en esta sesión (con timestamp)
$_SESSION['envios_realizados'] = ($_SESSION['envios_realizados'] ?? 0) + 1;
$_SESSION[$clave_tiempo_envio] = time();
$_SESSION['encuesta_enviada'] = true;

// Redirigir a la página de agradecimiento
header('Location: gracias.php');
exit;
?>