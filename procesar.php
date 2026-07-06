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
$opciones_sexo = ['masculino', 'femenino', 'otro', 'prefiero_no_decir'];
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
    $p3_texto = sanitizar($_POST['comentario_bloque2'] ?? '');
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
    $p4_texto = sanitizar($_POST['comentario_bloque3'] ?? '');
    if (empty($p4_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P4).');
    }
}

// 6. Validar P5 - Espiritualidad
$p5_value = $_POST['p5_espiritualidad'] ?? '';
if (empty($p5_value)) {
    die('❌ Error: Debes seleccionar una opción en P5.');
}
if ($p5_value === 'OTRO') {
    $p5_texto = sanitizar($_POST['comentario_bloque4'] ?? '');
    if (empty($p5_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P5).');
    }
}

// 7. Validar P6 - Familia
$p6_value = $_POST['p6_familia'] ?? '';
if (empty($p6_value)) {
    die('❌ Error: Debes seleccionar una opción en P6.');
}
if ($p6_value === 'OTRO') {
    $p6_texto = sanitizar($_POST['comentario_bloque5'] ?? '');
    if (empty($p6_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P6).');
    }
}

// 8. Validar P7 - Proyecto de vida
$p7_value = $_POST['p7_proyecto'] ?? '';
if (empty($p7_value)) {
    die('❌ Error: Debes seleccionar una opción en P7.');
}
if ($p7_value === 'OTRO') {
    $p7_texto = sanitizar($_POST['comentario_bloque6'] ?? '');
    if (empty($p7_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P7).');
    }
}

// 9. Validar P8 - Vocación
$p8_value = $_POST['p8_vocacion'] ?? '';
if (empty($p8_value)) {
    die('❌ Error: Debes seleccionar una opción en P8.');
}
if ($p8_value === 'OTRO') {
    $p8_texto = sanitizar($_POST['comentario_bloque7'] ?? '');
    if (empty($p8_texto)) {
        die('❌ Error: Debes especificar tu respuesta en "Otro" (P8).');
    }
}

// 10. Validar P9 - Crítica
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
        $p9_otro_texto = sanitizar($_POST['comentario_bloque8'] ?? '');
        if (empty($p9_otro_texto)) {
            die('❌ Error: Debes especificar tu respuesta en "Otro" (P9).');
        }
    }
    $p9_critica = implode(',', $_POST['p9_critica']);
} else {
    die('❌ Error: Debes seleccionar al menos una opción en P9.');
}

// 11. Validar P10 - Esperanza
$p10_esperanza = $_POST['p10_esperanza'] ?? '';
if (empty($p10_esperanza) || !in_array($p10_esperanza, ['1', '2', '3', '4', '5'])) {
    die('❌ Error: Debes seleccionar un nivel de esperanza válido.');
}

// ==================== PROCESAR DATOS "OTRO" CON PREFIJOS ====================
$campo_libre_adicional = '';

// P3 - Pertenencia
if ($p3_value === 'OTRO') {
    $p3_texto = sanitizar($_POST['comentario_bloque2'] ?? '');
    if (!empty($p3_texto)) {
        $campo_libre_adicional .= "[OTRO P3] " . $p3_texto . "\n";
    }
}
$comentario_p3 = sanitizar($_POST['comentario_bloque2'] ?? '');
if ($p3_value !== 'OTRO' && !empty($comentario_p3)) {
    $campo_libre_adicional .= "[COMENTARIO P3] " . $comentario_p3 . "\n";
}

// P4 - Atracción
if ($p4_value === 'OTRO') {
    $p4_texto = sanitizar($_POST['comentario_bloque3'] ?? '');
    if (!empty($p4_texto)) {
        $campo_libre_adicional .= "[OTRO P4] " . $p4_texto . "\n";
    }
}
$comentario_p4 = sanitizar($_POST['comentario_bloque3'] ?? '');
if ($p4_value !== 'OTRO' && !empty($comentario_p4)) {
    $campo_libre_adicional .= "[COMENTARIO P4] " . $comentario_p4 . "\n";
}

// P5 - Espiritualidad
if ($p5_value === 'OTRO') {
    $p5_texto = sanitizar($_POST['comentario_bloque4'] ?? '');
    if (!empty($p5_texto)) {
        $campo_libre_adicional .= "[OTRO P5] " . $p5_texto . "\n";
    }
}
$comentario_p5 = sanitizar($_POST['comentario_bloque4'] ?? '');
if ($p5_value !== 'OTRO' && !empty($comentario_p5)) {
    $campo_libre_adicional .= "[COMENTARIO P5] " . $comentario_p5 . "\n";
}

// P6 - Familia
if ($p6_value === 'OTRO') {
    $p6_texto = sanitizar($_POST['comentario_bloque5'] ?? '');
    if (!empty($p6_texto)) {
        $campo_libre_adicional .= "[OTRO P6] " . $p6_texto . "\n";
    }
}
$comentario_p6 = sanitizar($_POST['comentario_bloque5'] ?? '');
if ($p6_value !== 'OTRO' && !empty($comentario_p6)) {
    $campo_libre_adicional .= "[COMENTARIO P6] " . $comentario_p6 . "\n";
}

// P7 - Proyecto de vida
if ($p7_value === 'OTRO') {
    $p7_texto = sanitizar($_POST['comentario_bloque6'] ?? '');
    if (!empty($p7_texto)) {
        $campo_libre_adicional .= "[OTRO P7] " . $p7_texto . "\n";
    }
}
$comentario_p7 = sanitizar($_POST['comentario_bloque6'] ?? '');
if ($p7_value !== 'OTRO' && !empty($comentario_p7)) {
    $campo_libre_adicional .= "[COMENTARIO P7] " . $comentario_p7 . "\n";
}

// P8 - Vocación
if ($p8_value === 'OTRO') {
    $p8_texto = sanitizar($_POST['comentario_bloque7'] ?? '');
    if (!empty($p8_texto)) {
        $campo_libre_adicional .= "[OTRO P8] " . $p8_texto . "\n";
    }
}
$comentario_p8 = sanitizar($_POST['comentario_bloque7'] ?? '');
if ($p8_value !== 'OTRO' && !empty($comentario_p8)) {
    $campo_libre_adicional .= "[COMENTARIO P8] " . $comentario_p8 . "\n";
}

// P9 - Crítica
if (isset($_POST['p9_critica']) && in_array('OTRO', $_POST['p9_critica'])) {
    $p9_otro_texto = sanitizar($_POST['comentario_bloque8'] ?? '');
    if (!empty($p9_otro_texto)) {
        $campo_libre_adicional .= "[OTRO P9] " . $p9_otro_texto . "\n";
    }
}
$comentario_p9 = sanitizar($_POST['comentario_bloque8'] ?? '');
if (!in_array('OTRO', $_POST['p9_critica'] ?? []) && !empty($comentario_p9)) {
    $campo_libre_adicional .= "[COMENTARIO P9] " . $comentario_p9 . "\n";
}

// ==================== PROCESAR DATOS ====================
$permiso_padres = isset($_POST['permiso_padres']) ? 'si' : 'no';

$campo_libre_original = sanitizar($_POST['campo_libre'] ?? '');
$campo_libre_final = trim($campo_libre_original . "\n" . $campo_libre_adicional);

// ==================== INSERTAR EN BASE DE DATOS ====================
// ⚠️ SOLO LOS CAMPOS QUE EXISTEN EN TU BASE DE DATOS
$sql = "INSERT INTO respuestas (
    ip, p1_anio, sexo, p2_parroquia, p3_pertenencia, p4_atraccion,
    p5_espiritualidad, p6_familia, p7_proyecto, p8_vocacion,
    p9_critica, p10_esperanza, campo_libre, permiso_padres,
    comentario_bloque2, comentario_bloque3, comentario_bloque4,
    comentario_bloque5, comentario_bloque6, 
    comentario_bloque7, comentario_bloque8, comentario_bloque9
) VALUES (
    :ip, :p1_anio, :sexo, :p2_parroquia, :p3_pertenencia, :p4_atraccion,
    :p5_espiritualidad, :p6_familia, :p7_proyecto, :p8_vocacion,
    :p9_critica, :p10_esperanza, :campo_libre, :permiso_padres,
    :comentario_bloque2, :comentario_bloque3, :comentario_bloque4,
    :comentario_bloque5, :comentario_bloque6, 
    :comentario_bloque7, :comentario_bloque8, :comentario_bloque9
)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':ip' => $ip,
    ':p1_anio' => sanitizar($p1_anio),
    ':sexo' => sanitizar($sexo),
    ':p2_parroquia' => sanitizar($p2_parroquia),
    ':p3_pertenencia' => $p3_value === 'OTRO' ? 'OTRO' : sanitizar($p3_value),
    ':p4_atraccion' => $p4_value === 'OTRO' ? 'OTRO' : sanitizar($p4_value),
    ':p5_espiritualidad' => $p5_value === 'OTRO' ? 'OTRO' : sanitizar($p5_value),
    ':p6_familia' => $p6_value === 'OTRO' ? 'OTRO' : sanitizar($p6_value),
    ':p7_proyecto' => $p7_value === 'OTRO' ? 'OTRO' : sanitizar($p7_value),
    ':p8_vocacion' => $p8_value === 'OTRO' ? 'OTRO' : sanitizar($p8_value),
    ':p9_critica' => $p9_critica,
    ':p10_esperanza' => sanitizar($p10_esperanza),
    ':campo_libre' => $campo_libre_final,
    ':permiso_padres' => $permiso_padres,
    ':comentario_bloque2' => sanitizar($_POST['comentario_bloque2'] ?? ''),
    ':comentario_bloque3' => sanitizar($_POST['comentario_bloque3'] ?? ''),
    ':comentario_bloque4' => sanitizar($_POST['comentario_bloque4'] ?? ''),
    ':comentario_bloque5' => sanitizar($_POST['comentario_bloque5'] ?? ''),
    ':comentario_bloque6' => sanitizar($_POST['comentario_bloque6'] ?? ''),
    ':comentario_bloque7' => sanitizar($_POST['comentario_bloque7'] ?? ''),
    ':comentario_bloque8' => sanitizar($_POST['comentario_bloque8'] ?? ''),
    ':comentario_bloque9' => sanitizar($_POST['comentario_bloque9'] ?? '')
]);

$_SESSION['envios_realizados'] = ($_SESSION['envios_realizados'] ?? 0) + 1;
$_SESSION[$clave_tiempo_envio] = time();
$_SESSION['encuesta_enviada'] = true;

header('Location: gracias.php');
exit;
?>