<?php
// rate_limit.php - Control de tasa (Anti-DDoS básico)
function check_rate_limit(string $ip, int $limite = 10, int $tiempo = 60): void{
    $archivo = __DIR__ . '/datos/rate_limit.json';
    $ahora = time();
    
    $datos = [];
    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true) ?: [];
    }
    
    foreach ($datos as $key => $item) {
        if ($item['tiempo'] < $ahora - 3600) {
            unset($datos[$key]);
        }
    }
    
    $contador = 0;
    foreach ($datos as $item) {
        if ($item['ip'] === $ip && $item['tiempo'] > $ahora - $tiempo) {
            $contador++;
        }
    }
    
    if ($contador >= $limite) {
        header('HTTP/1.1 429 Too Many Requests');
        die('❌ Demasiadas solicitudes. Por favor, espera un momento antes de volver a intentar.');
    }
    
    $datos[] = ['ip' => $ip, 'tiempo' => $ahora];
    file_put_contents($archivo, json_encode($datos));
}
?>