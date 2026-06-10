<?php
// rate_limit.php - Control de tasa avanzado (Anti-DDoS)

// ==================== LÍMITE PRINCIPAL (1 envío cada 60 segundos) ====================
function check_rate_limit(string $ip, int $limite = 1, int $tiempo = 60): bool {
    $archivo = __DIR__ . '/datos/rate_limit.json';
    $ahora = time();
    
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
    
    // Contar cuántos intentos hizo esta IP en el tiempo especificado
    $contador = 0;
    $ultimo_envio = null;
    foreach ($datos as $item) {
        if ($item['ip'] === $ip && $item['tiempo'] > $ahora - $tiempo) {
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
        
        // Si el tiempo restante es 0, permitir nuevo envío (limpiar registro antiguo)
        if ($tiempo_restante <= 0) {
            // Limpiar registros antiguos de esta IP
            foreach ($datos as $key => $item) {
                if ($item['ip'] === $ip) {
                    unset($datos[$key]);
                }
            }
            file_put_contents($archivo, json_encode(array_values($datos)));
            return true; // Permitir envío
        }
        
        // Registrar intento de ataque en log
        error_log("⚠️ Rate limit excedido para IP: $ip - Debe esperar {$tiempo_restante} segundos");
        
        // Redirigir a la página de error bonita
        header('Location: error_rate_limit.php?espera=' . $tiempo_restante);
        exit;
    }
    
    // Registrar este intento
    $datos[] = [
        'ip' => $ip,
        'tiempo' => $ahora,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    
    file_put_contents($archivo, json_encode(array_values($datos)));
    return true;
}

// ==================== VERIFICAR TIEMPO MÍNIMO POR SESIÓN ====================
function check_min_time(string $ip, int $min_segundos = 60): bool {
    session_start();
    
    $clave_sesion = 'ultimo_envio_' . md5($ip);
    $clave_tiempo = 'tiempo_restante_' . md5($ip);
    
    if (isset($_SESSION[$clave_sesion])) {
        $tiempo_transcurrido = time() - $_SESSION[$clave_sesion];
        
        // Si ya pasó el tiempo mínimo, limpiar y permitir
        if ($tiempo_transcurrido >= $min_segundos) {
            unset($_SESSION[$clave_sesion]);
            unset($_SESSION[$clave_tiempo]);
            return true;
        }
        
        $espera = $min_segundos - $tiempo_transcurrido;
        header('Location: error_rate_limit.php?espera=' . $espera);
        exit;
    }
    
    $_SESSION[$clave_sesion] = time();
    return true;
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