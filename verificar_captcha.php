<?php
// verificar_captcha.php - Verifica el token de reCAPTCHA v3
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// ==================== MODO PRUEBA (saltar reCAPTCHA) ====================
// Si la URL tiene ?test=1, se salta reCAPTCHA
if (isset($_GET['test']) && $_GET['test'] === '1') {
    $_SESSION['acceso_verificado'] = true;
    $_SESSION['acceso_verificado_timestamp'] = time();
    echo json_encode([
        'success' => true,
        'mensaje' => 'Modo prueba (sin reCAPTCHA)',
        'score' => 0.9
    ]);
    exit;
}

$recaptcha_response = $_POST['recaptcha_response'] ?? '';

if (empty($recaptcha_response)) {
    echo json_encode(['success' => false, 'mensaje' => 'No se recibió el token de verificación.']);
    exit;
}

// Verificar con Google
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => RECAPTCHA_SECRET_KEY,
    'response' => $recaptcha_response,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($result === false) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error de conexión: ' . $error
        ]);
        exit;
    }
} else {
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === false) {
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error de conexión con el servidor de verificación.'
        ]);
        exit;
    }
}

$response = json_decode($result, true);

if (!$response['success']) {
    $error_codes = implode(', ', $response['error-codes'] ?? ['desconocido']);
    error_log("reCAPTCHA falló - IP: {$_SERVER['REMOTE_ADDR']} - Errores: $error_codes");
    echo json_encode([
        'success' => false,
        'mensaje' => 'Error de verificación: ' . $error_codes
    ]);
    exit;
}

$score = $response['score'] ?? 0;

if ($score < RECAPTCHA_SCORE_THRESHOLD) {
    error_log("reCAPTCHA score bajo - IP: {$_SERVER['REMOTE_ADDR']} - Score: $score");
    echo json_encode([
        'success' => false,
        'mensaje' => 'Actividad sospechosa detectada. Por favor, intenta más tarde.'
    ]);
    exit;
}

$action = $response['action'] ?? '';
if ($action !== 'acceso') {
    echo json_encode([
        'success' => false,
        'mensaje' => 'Acción no válida.'
    ]);
    exit;
}

$_SESSION['acceso_verificado'] = true;
$_SESSION['acceso_verificado_timestamp'] = time();

echo json_encode([
    'success' => true,
    'mensaje' => 'Verificación exitosa',
    'score' => $score
]);
?>