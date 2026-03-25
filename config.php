<?php
// Configuración del sistema
define('SITIO_NOMBRE', 'Voces del Sur');
define('SITIO_DESCRIPCION', 'Laboratorio de escucha genuina');
define('FECHA_CADUCIDAD', '2026-12-31');
define('VERSION', '1.0');

// Configuración de zona horaria
date_default_timezone_set('America/Asuncion');

// Configuración de errores (desactivar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>