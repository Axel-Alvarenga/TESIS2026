<?php
define('SITIO_NOMBRE', 'Voces del Sur');
define('SITIO_DESCRIPCION', 'Laboratorio de escucha genuina');
define('FECHA_CADUCIDAD', '2026-12-31');
define('VERSION', '1.0');
define('ENV', 'production');

// ==================== CONFIGURACIÓN DE reCAPTCHA v3 ====================
// VERIFICA QUE ESTAS CLAVES SEAN CORRECTAS EN LA CONSOLA DE reCAPTCHA
define('RECAPTCHA_SITE_KEY', '6LeFjSMtAAAAAMvxW9c7Hkpu1h_yxYjIdMW0KdV0');
define('RECAPTCHA_SECRET_KEY', '6LeFjSMtAAAAAOQAqgnzVv2z9XYY_1SHpwYfox8-');
define('RECAPTCHA_SCORE_THRESHOLD', 0.5);

date_default_timezone_set('America/Asuncion');

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
?>