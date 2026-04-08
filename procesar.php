<?php
session_start();
require_once 'db_config.php';

function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Convertir P9 (checkbox) a string separado por comas
$p9_critica = isset($_POST['p9_critica']) ? implode(',', $_POST['p9_critica']) : '';

$sql = "INSERT INTO respuestas (
    ip, codigo_familiar, p1_anio, p2_parroquia, p3_pertenencia,
    p4_atraccion, p5_espiritualidad, p6_familia, p7_proyecto,
    p8_vocacion, p9_critica, p10_esperanza, campo_libre
) VALUES (
    :ip, :codigo_familiar, :p1_anio, :p2_parroquia, :p3_pertenencia,
    :p4_atraccion, :p5_espiritualidad, :p6_familia, :p7_proyecto,
    :p8_vocacion, :p9_critica, :p10_esperanza, :campo_libre
)";

<<<<<<< HEAD
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':ip' => $_SERVER['REMOTE_ADDR'],
    ':codigo_familiar' => sanitizar($_POST['codigo_familiar'] ?? ''),
    ':p1_anio' => sanitizar($_POST['p1_anio'] ?? ''),
    ':p2_parroquia' => sanitizar($_POST['p2_parroquia'] ?? ''),
    ':p3_pertenencia' => sanitizar($_POST['p3_pertenencia'] ?? ''),
    ':p4_atraccion' => sanitizar($_POST['p4_atraccion'] ?? ''),
    ':p5_espiritualidad' => sanitizar($_POST['p5_espiritualidad'] ?? ''),
    ':p6_familia' => sanitizar($_POST['p6_familia'] ?? ''),
    ':p7_proyecto' => sanitizar($_POST['p7_proyecto'] ?? ''),
    ':p8_vocacion' => sanitizar($_POST['p8_vocacion'] ?? ''),
    ':p9_critica' => $p9_critica,
    ':p10_esperanza' => sanitizar($_POST['p10_esperanza'] ?? ''),
    ':campo_libre' => sanitizar($_POST['campo_libre'] ?? '')
]);
=======
// Si es repetida, registrar en log pero permitir continuar
if ($es_repetida) {
    registrar_en_log($ip_usuario, "RESPUESTA_REPETIDA (veces: " . ($veces_respondidas + 1) . ")", $archivo_log);
}
// Crear array con las respuestas
$respuesta = [
    'fecha' => date('Y-m-d H:i:s'),
    'ip' => $ip_usuario,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
    'es_respuesta_repetida' => $es_repetida,
    'numero_respuesta_desde_ip' => $veces_respondidas + 1,
    'codigo_familiar' => sanitizar($_POST['codigo_familiar'] ?? ''), // Nuevo campo opcional
    'datos' => [
        'p1_anio' => sanitizar($_POST['p1_anio'] ?? ''),
        'p2_parroquia' => sanitizar($_POST['p2_parroquia'] ?? ''),
        'p3_pertenencia' => sanitizar($_POST['p3_pertenencia'] ?? ''),
        'p4_atraccion' => sanitizar($_POST['p4_atraccion'] ?? ''),
        'p5_espiritualidad' => sanitizar($_POST['p5_espiritualidad'] ?? ''),
        'p6_familia' => sanitizar($_POST['p6_familia'] ?? ''),
        'p7_proyecto' => sanitizar($_POST['p7_proyecto'] ?? ''),
        'p8_vocacion' => sanitizar($_POST['p8_vocacion'] ?? ''),
        'p9_critica' => isset($_POST['p9_critica']) ? array_map('sanitizar', $_POST['p9_critica']) : [],
        'p10_esperanza' => sanitizar($_POST['p10_esperanza'] ?? ''),
        'campo_libre' => sanitizar($_POST['campo_libre'] ?? '')
    ]
];
>>>>>>> ab1fc32e78737d5bdf3879a74f066262c75eeb5a

header('Location: gracias.php');
exit;
?>