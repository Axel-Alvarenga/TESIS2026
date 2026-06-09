<?php
// Configuración del sistema - MODO PRODUCCIÓN
define('SITIO_NOMBRE', 'Voces del Sur');
define('SITIO_DESCRIPCION', 'Laboratorio de escucha genuina');
define('FECHA_CADUCIDAD', '2026-12-31');
define('VERSION', '1.0');
define('ENV', 'production');

date_default_timezone_set('America/Asuncion');

// En producción - DESACTIVAR errores en pantalla (SEGURIDAD)
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Log de errores a archivo
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
?>