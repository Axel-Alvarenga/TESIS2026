<?php
session_start();
require_once 'db_config.php';
require_once 'rate_limit.php';

// ==================== VERIFICAR ACCESO ====================
if (!isset($_SESSION['acceso_verificado']) || $_SESSION['acceso_verificado'] !== true) {
    header('Location: index.php?error=acceso_no_autorizado');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'];

// ==================== LIMPIAR REGISTROS ANTIGUOS ====================
if (function_exists('limpiarRegistrosAntiguos')) {
    limpiarRegistrosAntiguos();
}

// ==================== LIMPIAR SESIÓN SI PASÓ EL TIEMPO ====================
$clave_tiempo_envio = 'ultimo_envio_timestamp';

if (isset($_SESSION[$clave_tiempo_envio])) {
    $tiempo_transcurrido = time() - $_SESSION[$clave_tiempo_envio];
    if ($tiempo_transcurrido >= 60) {
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

// ==================== RATE LIMITING ====================
if (function_exists('check_rate_limit')) {
    check_rate_limit($ip, 1, 60);
}

// ==================== VERIFICAR SI YA RESPONDIÓ ====================
if (isset($_SESSION['envios_realizados']) && $_SESSION['envios_realizados'] >= 1) {
    if (isset($_SESSION[$clave_tiempo_envio])) {
        $tiempo_transcurrido = time() - $_SESSION[$clave_tiempo_envio];
        if ($tiempo_transcurrido < 60) {
            $espera = 60 - $tiempo_transcurrido;
            header('Location: error_rate_limit.php?espera=' . $espera);
            exit;
        }
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

// ==================== VALIDACIONES SERVIDOR ====================

// 1. Validar P1 - Año de nacimiento
$p1_anio = $_POST['p1_anio'] ?? '';
if (empty($p1_anio)) {
    die('❌ Error: Debes seleccionar tu año de nacimiento.');
}

// 2. Validar sexo
$sexo = $_POST['sexo'] ?? '';
if (empty($sexo)) {
    die('❌ Error: Debes seleccionar tu género.');
}
$opciones_sexo = ['masculino', 'femenino'];
if (!in_array($sexo, $opciones_sexo)) {
    die('❌ Error: Opción de género no válida.');
}

// 3. Validar P2 - Parroquia
$p2_parroquia = $_POST['p2_parroquia'] ?? '';
if (empty($p2_parroquia) || $p2_parroquia === '') {
    die('❌ Error: Debes seleccionar una parroquia.');
}

// 4. Validar P3 - Pertenencia
$p3_value = $_POST['p3_pertenencia'] ?? '';
if (empty($p3_value)) {
    die('❌ Error: Debes seleccionar una opción en P3.');
}
if ($p3_value === 'OTRO') {
    $p3_texto = sanitizar($_POST['p3_otro_texto'] ?? '');
    if (empty($p3_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P3).');
    }
}

// 5. Validar P4 - Atracción
$p4_value = $_POST['p4_atraccion'] ?? '';
if (empty($p4_value)) {
    die('❌ Error: Debes seleccionar una opción en P4.');
}
if ($p4_value === 'OTRO') {
    $p4_texto = sanitizar($_POST['p4_otro_texto'] ?? '');
    if (empty($p4_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P4).');
    }
}

// 6. Validar P4b-1 - Situación
$p4b_situacion = $_POST['p4b_situacion'] ?? '';
if (empty($p4b_situacion)) {
    die('❌ Error: Debes seleccionar una opción en P4b-1.');
}
if ($p4b_situacion === 'OTRO') {
    $p4b1_texto = sanitizar($_POST['p4b1_otro_texto'] ?? '');
    if (empty($p4b1_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P4b-1).');
    }
}

// 7. Validar P4b-2 - Área de interés
$p4b_area = $_POST['p4b_area'] ?? '';
if (empty($p4b_area)) {
    die('❌ Error: Debes seleccionar una opción en P4b-2.');
}
if ($p4b_area === 'OTRO') {
    $p4b2_texto = sanitizar($_POST['p4b2_otro_texto'] ?? '');
    if (empty($p4b2_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P4b-2).');
    }
}

// 8. Validar P4b-3 - Movilidad
$p4b_movilidad = $_POST['p4b_movilidad'] ?? '';
if (empty($p4b_movilidad)) {
    die('❌ Error: Debes seleccionar una opción en P4b-3.');
}
if ($p4b_movilidad === 'OTRO') {
    $p4b3_texto = sanitizar($_POST['p4b3_otro_texto'] ?? '');
    if (empty($p4b3_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P4b-3).');
    }
}

// 9. Validar P5 - Espiritualidad
$p5_value = $_POST['p5_espiritualidad'] ?? '';
if (empty($p5_value)) {
    die('❌ Error: Debes seleccionar una opción en P5.');
}
if ($p5_value === 'OTRO') {
    $p5_texto = sanitizar($_POST['p5_otro_texto'] ?? '');
    if (empty($p5_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P5).');
    }
}

// 10. Validar P6 - Familia
$p6_value = $_POST['p6_familia'] ?? '';
if (empty($p6_value)) {
    die('❌ Error: Debes seleccionar una opción en P6.');
}
if ($p6_value === 'OTRO') {
    $p6_texto = sanitizar($_POST['p6_otro_texto'] ?? '');
    if (empty($p6_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P6).');
    }
}

// 11. Validar P7 - Proyecto de vida
$p7_value = $_POST['p7_proyecto'] ?? '';
if (empty($p7_value)) {
    die('❌ Error: Debes seleccionar una opción en P7.');
}
if ($p7_value === 'OTRO') {
    $p7_texto = sanitizar($_POST['p7_otro_texto'] ?? '');
    if (empty($p7_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P7).');
    }
}

// 12. Validar P8 - Vocación
$p8_value = $_POST['p8_vocacion'] ?? '';
if (empty($p8_value)) {
    die('❌ Error: Debes seleccionar una opción en P8.');
}
if ($p8_value === 'OTRO') {
    $p8_texto = sanitizar($_POST['p8_otro_texto'] ?? '');
    if (empty($p8_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P8).');
    }
}

// 13. Validar P9 - Crítica
if (isset($_POST['p9_critica'])) {
    $p9_seleccionadas = count($_POST['p9_critica']);
    if ($p9_seleccionadas > 2) {
        die('❌ Error: Solo puedes seleccionar hasta 2 opciones en P9.');
    }
    $opciones_validas = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    foreach ($_POST['p9_critica'] as $opcion) {
        if (!in_array($opcion, $opciones_validas) && $opcion !== 'OTRO') {
            die('❌ Error: Opción inválida en P9.');
        }
    }
    if (in_array('OTRO', $_POST['p9_critica'])) {
        $p9_otro_texto = sanitizar($_POST['p9_otro_texto'] ?? '');
        if (empty($p9_otro_texto)) {
            die('❌ Error: Debes especificar tu respuesta en "Otro" (P9).');
        }
    }
} else {
    die('❌ Error: Debes seleccionar al menos una opción en P9.');
}

// 14. Validar P10 - Esperanza
$p10_esperanza = $_POST['p10_esperanza'] ?? '';
if (empty($p10_esperanza) || !in_array($p10_esperanza, ['1', '2', '3', '4', '5'])) {
    die('❌ Error: Debes seleccionar un nivel de esperanza válido.');
}

// ==================== RECOGER TODOS LOS DATOS ====================

// ============================================================
// 1. RECOGER COMENTARIOS (se guardan en sus columnas específicas)
// ============================================================
$comentario_bloque2 = sanitizar($_POST['comentario_bloque2'] ?? '');
$comentario_bloque3 = sanitizar($_POST['comentario_bloque3'] ?? '');
$comentario_bloque4 = sanitizar($_POST['comentario_bloque4'] ?? '');
$comentario_bloque5 = sanitizar($_POST['comentario_bloque5'] ?? '');
$comentario_bloque6 = sanitizar($_POST['comentario_bloque6'] ?? '');
$comentario_bloque7 = sanitizar($_POST['comentario_bloque7'] ?? '');
$comentario_bloque8 = sanitizar($_POST['comentario_bloque8'] ?? '');
$comentario_bloque9 = sanitizar($_POST['comentario_bloque9'] ?? '');
$comentario_p4b1 = sanitizar($_POST['comentario_p4b1'] ?? '');
$comentario_p4b2 = sanitizar($_POST['comentario_p4b2'] ?? '');
$comentario_p4b3 = sanitizar($_POST['comentario_p4b3'] ?? '');
$comentario_p10_adicional = sanitizar($_POST['campo_libre'] ?? '');

// ============================================================
// 2. RECOGER CAMPOS "OTRO" (se guardan en campo_libre con prefijos)
// ============================================================
$p3_otro_texto = sanitizar($_POST['p3_otro_texto'] ?? '');
$p4_otro_texto = sanitizar($_POST['p4_otro_texto'] ?? '');
$p4b1_otro_texto = sanitizar($_POST['p4b1_otro_texto'] ?? '');
$p4b2_otro_texto = sanitizar($_POST['p4b2_otro_texto'] ?? '');
$p4b3_otro_texto = sanitizar($_POST['p4b3_otro_texto'] ?? '');
$p5_otro_texto = sanitizar($_POST['p5_otro_texto'] ?? '');
$p6_otro_texto = sanitizar($_POST['p6_otro_texto'] ?? '');
$p7_otro_texto = sanitizar($_POST['p7_otro_texto'] ?? '');
$p8_otro_texto = sanitizar($_POST['p8_otro_texto'] ?? '');
$p9_otro_texto = sanitizar($_POST['p9_otro_texto'] ?? '');

// ============================================================
// 3. CONSTRUIR campo_libre (SOLO PARA "OTRO")
// ============================================================
$campo_libre_final = '';

// P3 - SOLO si es OTRO
if ($p3_value === 'OTRO' && !empty($p3_otro_texto)) {
    $campo_libre_final .= "[OTRO P3] " . $p3_otro_texto . "\n";
}

// P4 - SOLO si es OTRO
if ($p4_value === 'OTRO' && !empty($p4_otro_texto)) {
    $campo_libre_final .= "[OTRO P4] " . $p4_otro_texto . "\n";
}

// P4b-1 - SOLO si es OTRO
if ($p4b_situacion === 'OTRO' && !empty($p4b1_otro_texto)) {
    $campo_libre_final .= "[OTRO P4b-1] " . $p4b1_otro_texto . "\n";
}

// P4b-2 - SOLO si es OTRO
if ($p4b_area === 'OTRO' && !empty($p4b2_otro_texto)) {
    $campo_libre_final .= "[OTRO P4b-2] " . $p4b2_otro_texto . "\n";
}

// P4b-3 - SOLO si es OTRO
if ($p4b_movilidad === 'OTRO' && !empty($p4b3_otro_texto)) {
    $campo_libre_final .= "[OTRO P4b-3] " . $p4b3_otro_texto . "\n";
}

// P5 - SOLO si es OTRO
if ($p5_value === 'OTRO' && !empty($p5_otro_texto)) {
    $campo_libre_final .= "[OTRO P5] " . $p5_otro_texto . "\n";
}

// P6 - SOLO si es OTRO
if ($p6_value === 'OTRO' && !empty($p6_otro_texto)) {
    $campo_libre_final .= "[OTRO P6] " . $p6_otro_texto . "\n";
}

// P7 - SOLO si es OTRO
if ($p7_value === 'OTRO' && !empty($p7_otro_texto)) {
    $campo_libre_final .= "[OTRO P7] " . $p7_otro_texto . "\n";
}

// P8 - SOLO si es OTRO
if ($p8_value === 'OTRO' && !empty($p8_otro_texto)) {
    $campo_libre_final .= "[OTRO P8] " . $p8_otro_texto . "\n";
}

// P9 - SOLO si es OTRO (checkbox)
if (isset($_POST['p9_critica']) && in_array('OTRO', $_POST['p9_critica']) && !empty($p9_otro_texto)) {
    $campo_libre_final .= "[OTRO P9] " . $p9_otro_texto . "\n";
}

// Limpiar campo_libre (eliminar saltos de línea extra)
$campo_libre_final = trim($campo_libre_final);

// ============================================================
// 4. PROCESAR P9 CRÍTICA
// ============================================================
if (isset($_POST['p9_critica'])) {
    if (in_array('OTRO', $_POST['p9_critica'])) {
        $p9_critica_array = array_filter($_POST['p9_critica'], function($v) { return $v !== 'OTRO'; });
        if (empty($p9_critica_array)) {
            $p9_critica = 'OTRO: ' . $p9_otro_texto;
        } else {
            $p9_critica = implode(',', $p9_critica_array) . ', OTRO: ' . $p9_otro_texto;
        }
    } else {
        $p9_critica = implode(',', $_POST['p9_critica']);
    }
} else {
    $p9_critica = '';
}

// ============================================================
// 5. PROCESAR DATOS FINALES
// ============================================================
$permiso_padres = isset($_POST['permiso_padres']) ? 'si' : 'no';

// ============================================================
// 6. INSERTAR EN BASE DE DATOS
// ============================================================
$sql = "INSERT INTO respuestas (
    ip, p1_anio, sexo, p2_parroquia, p3_pertenencia, p4_atraccion,
    p4b_situacion, p4b_area, p4b_movilidad,
    p5_espiritualidad, p6_familia, p7_proyecto, p8_vocacion,
    p9_critica, p10_esperanza, campo_libre, permiso_padres,
    comentario_bloque2, comentario_bloque3, comentario_bloque4,
    comentario_p4b1, comentario_p4b2, comentario_p4b3,
    comentario_bloque5, comentario_bloque6, 
    comentario_bloque7, comentario_bloque8, comentario_bloque9,
    comentario_p10_adicional
) VALUES (
    :ip, :p1_anio, :sexo, :p2_parroquia, :p3_pertenencia, :p4_atraccion,
    :p4b_situacion, :p4b_area, :p4b_movilidad,
    :p5_espiritualidad, :p6_familia, :p7_proyecto, :p8_vocacion,
    :p9_critica, :p10_esperanza, :campo_libre, :permiso_padres,
    :comentario_bloque2, :comentario_bloque3, :comentario_bloque4,
    :comentario_p4b1, :comentario_p4b2, :comentario_p4b3,
    :comentario_bloque5, :comentario_bloque6, 
    :comentario_bloque7, :comentario_bloque8, :comentario_bloque9,
    :comentario_p10_adicional
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':ip' => $ip,
    ':p1_anio' => sanitizar($p1_anio),
    ':sexo' => sanitizar($sexo),
    ':p2_parroquia' => sanitizar($p2_parroquia),
    ':p3_pertenencia' => $p3_value === 'OTRO' ? 'OTRO' : sanitizar($p3_value),
    ':p4_atraccion' => $p4_value === 'OTRO' ? 'OTRO' : sanitizar($p4_value),
    ':p4b_situacion' => $p4b_situacion === 'OTRO' ? 'OTRO' : sanitizar($p4b_situacion),
    ':p4b_area' => $p4b_area === 'OTRO' ? 'OTRO' : sanitizar($p4b_area),
    ':p4b_movilidad' => $p4b_movilidad === 'OTRO' ? 'OTRO' : sanitizar($p4b_movilidad),
    ':p5_espiritualidad' => $p5_value === 'OTRO' ? 'OTRO' : sanitizar($p5_value),
    ':p6_familia' => $p6_value === 'OTRO' ? 'OTRO' : sanitizar($p6_value),
    ':p7_proyecto' => $p7_value === 'OTRO' ? 'OTRO' : sanitizar($p7_value),
    ':p8_vocacion' => $p8_value === 'OTRO' ? 'OTRO' : sanitizar($p8_value),
    ':p9_critica' => $p9_critica,
    ':p10_esperanza' => sanitizar($p10_esperanza),
    ':campo_libre' => $campo_libre_final,
    ':permiso_padres' => $permiso_padres,
    ':comentario_bloque2' => $comentario_bloque2,
    ':comentario_bloque3' => $comentario_bloque3,
    ':comentario_bloque4' => $comentario_bloque4,
    ':comentario_p4b1' => $comentario_p4b1,
    ':comentario_p4b2' => $comentario_p4b2,
    ':comentario_p4b3' => $comentario_p4b3,
    ':comentario_bloque5' => $comentario_bloque5,
    ':comentario_bloque6' => $comentario_bloque6,
    ':comentario_bloque7' => $comentario_bloque7,
    ':comentario_bloque8' => $comentario_bloque8,
    ':comentario_bloque9' => $comentario_bloque9,
    ':comentario_p10_adicional' => $comentario_p10_adicional
]);

$_SESSION['envios_realizados'] = ($_SESSION['envios_realizados'] ?? 0) + 1;
$_SESSION[$clave_tiempo_envio] = time();
$_SESSION['encuesta_enviada'] = true;

header('Location: gracias.php');
exit;
?>