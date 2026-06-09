<?php
session_start();
require_once 'db_config.php';
require_once 'rate_limit.php';

// ==================== VALIDACIONES DE SEGURIDAD ====================

// 1. Rate limiting (máximo 5 envíos por IP cada 60 segundos)
check_rate_limit($_SERVER['REMOTE_ADDR'], 5, 60);

// 2. Verificar CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('❌ Error de seguridad: token CSRF inválido. Por favor, recarga la página y vuelve a intentar.');
}

// 3. Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ==================== FUNCIÓN DE SANITIZACIÓN ====================
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// ==================== PROCESAR DATOS ====================

// Convertir P9 (checkbox) a string separado por comas
$p9_critica = isset($_POST['p9_critica']) ? implode(',', $_POST['p9_critica']) : '';

// Valor del permiso de padres (para menores)
$permiso_padres = isset($_POST['permiso_padres']) ? 'si' : 'no';

// Valores de P4b (Contexto de vida)
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
    ':ip' => $_SERVER['REMOTE_ADDR'],
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

// Redirigir a la página de agradecimiento
header('Location: gracias.php');
exit;
?>