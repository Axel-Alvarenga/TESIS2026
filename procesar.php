<?php
session_start();

// Configuración
$archivo_datos = 'datos/respuestas.json';

// Función para sanitizar datos
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// Verificar que se recibieron datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Crear array con las respuestas
$respuesta = [
    'fecha' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
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

// Guardar en archivo JSON
if (!file_exists('datos')) {
    mkdir('datos', 0777, true);
}

$respuestas_existentes = [];
if (file_exists($archivo_datos)) {
    $contenido = file_get_contents($archivo_datos);
    $respuestas_existentes = json_decode($contenido, true) ?? [];
}

$respuestas_existentes[] = $respuesta;
file_put_contents($archivo_datos, json_encode($respuestas_existentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Redirigir a página de agradecimiento
header('Location: gracias.php');
exit;
?>