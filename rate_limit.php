<?php
// rate_limit.php - Control de tasa por sesión/navegador
// Permite que múltiples dispositivos en el mismo WiFi respondan sin bloquearse

// ==================== GENERAR ID ÚNICO POR NAVEGADOR ====================
function getVisitorId() {
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Generar ID único para este navegador/dispositivo si no existe
    if (!isset($_SESSION['visitor_id'])) {
        $_SESSION['visitor_id'] = bin2hex(random_bytes(16));
    }
    
    return $_SESSION['visitor_id'];
}

// ==================== CHECK RATE LIMIT POR VISITANTE ====================
// La función mantiene la misma firma para no romper procesar.php
function check_rate_limit(string $ip, int $limite = 1, int $tiempo = 60): void {
    $archivo = __DIR__ . '/datos/rate_limit.json';
    $ahora = time();
    
    // Usamos visitor_id en lugar de IP como identificador principal
    $visitorId = getVisitorId();
    
    // Cargar registros existentes
    $datos = [];
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true) ?: [];
    }
    
    // Limpiar registros antiguos (más de 1 hora)
    foreach ($datos as $key => $item) {
        if ($item['tiempo'] < $ahora - 3600) {
            unset($datos[$key]);
        }
    }
    
    // Contar cuántos intentos hizo este visitante en el tiempo especificado
    $contador = 0;
    $ultimo_envio = null;
    foreach ($datos as $item) {
        if ($item['visitor_id'] === $visitorId && $item['tiempo'] > $ahora - $tiempo) {
            $contador++;
            if ($item['tiempo'] > $ultimo_envio) {
                $ultimo_envio = $item['tiempo'];
            }
        }
    }
    
    // Si ya envió antes, calcular tiempo restante
    if ($contador >= $limite) {
        $tiempo_restante = $tiempo - ($ahora - $ultimo_envio);
        if ($tiempo_restante < 0) $tiempo_restante = 0;
        
        // Registrar intento bloqueado en log
        error_log("⏳ Rate limit excedido para visitor: $visitorId - IP: $ip - Debe esperar {$tiempo_restante} segundos");
        
        // Redirigir a la página de error bonita
        header('Location: error_rate_limit.php?espera=' . $tiempo_restante);
        exit;
    }
    
    // Registrar este intento (guardamos visitor_id + ip para referencia)
    $datos[] = [
        'visitor_id' => $visitorId,
        'ip' => $ip,
        'tiempo' => $ahora,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    
    file_put_contents($archivo, json_encode(array_values($datos)));
}

// ==================== VERIFICAR IP BLOQUEADA ====================
function ipBloqueada($ip) {
    $archivo = __DIR__ . '/datos/ip_bloqueadas.json';
    if (!file_exists($archivo)) return false;
    
    $bloqueadas = json_decode(file_get_contents($archivo), true) ?: [];
    return in_array($ip, $bloqueadas);
}

// ==================== LIMPIAR REGISTROS ANTIGUOS MANUALMENTE ====================
function limpiarRegistrosAntiguos() {
    $archivo = __DIR__ . '/datos/rate_limit.json';
    if (!file_exists($archivo)) return;
    
    $ahora = time();
    $datos = json_decode(file_get_contents($archivo), true) ?: [];
    
    $datos_limpios = [];
    foreach ($datos as $item) {
        if ($item['tiempo'] > $ahora - 3600) { // Mantener solo últimos 60 minutos
            $datos_limpios[] = $item;
        }
    }
    
    file_put_contents($archivo, json_encode($datos_limpios));
}
?>