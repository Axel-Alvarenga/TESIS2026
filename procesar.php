<?php
session_start();

// Configuración
$archivo_datos = 'datos/respuestas.json';
$archivo_log = 'datos/registro_ips.log';

// Función para sanitizar datos
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// Función para verificar si la IP ya ha respondido
function ip_ya_respondio($ip, $archivo_datos) {
    if (!file_exists($archivo_datos)) {
        return false;
    }
    
    $contenido = file_get_contents($archivo_datos);
    $respuestas = json_decode($contenido, true) ?? [];
    
    foreach ($respuestas as $respuesta) {
        if (isset($respuesta['ip']) && $respuesta['ip'] === $ip) {
            return true;
        }
    }
    return false;
}

// Función para contar cuántas veces ha respondido una IP
function contar_respuestas_por_ip($ip, $archivo_datos) {
    if (!file_exists($archivo_datos)) {
        return 0;
    }
    
    $contenido = file_get_contents($archivo_datos);
    $respuestas = json_decode($contenido, true) ?? [];
    
    $contador = 0;
    foreach ($respuestas as $respuesta) {
        if (isset($respuesta['ip']) && $respuesta['ip'] === $ip) {
            $contador++;
        }
    }
    return $contador;
}

// Función para registrar en log
function registrar_en_log($ip, $accion, $archivo_log) {
    $fecha = date('Y-m-d H:i:s');
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';
    $log_linea = "[$fecha] IP: $ip - Acción: $accion - UA: $user_agent" . PHP_EOL;
    file_put_contents($archivo_log, $log_linea, FILE_APPEND);
}

// Verificar que se recibieron datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Obtener IP del usuario
$ip_usuario = $_SERVER['REMOTE_ADDR'];

// Verificar si es una IP repetida
$es_repetida = ip_ya_respondio($ip_usuario, $archivo_datos);
$veces_respondidas = contar_respuestas_por_ip($ip_usuario, $archivo_datos);

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

// Registrar éxito en log
registrar_en_log($ip_usuario, "RESPUESTA_GUARDADA (veces: " . ($veces_respondidas + 1) . ")", $archivo_log);

// Redirigir a página de agradecimiento con parámetro de advertencia
$parametro = $es_repetida ? '?repetida=1' : '';
header('Location: gracias.php' . $parametro);
exit;
?>