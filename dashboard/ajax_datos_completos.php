<?php
/**
 * ajax_datos_completos.php
 * Endpoint que devuelve TODOS los datos necesarios para la tabla dinámica multivariable
 * Incluye todas las variables que pueden ser usadas en filas, columnas y filtros
 */

require_once '../db_config.php';

// ==================== PARÁMETROS ====================
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');

// Variables adicionales para filtros futuros (opcional)
$filtro_extra = $_GET['filtro'] ?? '';

// ==================== CONSTRUCCIÓN DE LA CONSULTA ====================
// Seleccionamos TODAS las variables que pueden ser usadas en cruces
$campos = [
    'p3_pertenencia',
    'p4_atraccion', 
    'p5_espiritualidad',
    'p6_familia',
    'p7_proyecto',
    'p8_vocacion',
    'p1_anio',
    'p2_parroquia',
    'p10_esperanza',
    'permiso_padres',
    'id',
    'fecha'
];

$sql = "SELECT " . implode(", ", $campos) . " 
        FROM respuestas 
        WHERE DATE(fecha) BETWEEN :fecha_desde AND :fecha_hasta
        ORDER BY id DESC";

$params = [
    ':fecha_desde' => $fecha_desde,
    ':fecha_hasta' => $fecha_hasta
];

// ==================== EJECUTAR CONSULTA ====================
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== POST-PROCESAMIENTO ====================
// Convertir valores nulos a null para JSON
foreach ($datos as &$fila) {
    foreach ($fila as $key => $valor) {
        if ($valor === '') {
            $fila[$key] = null;
        }
        // Convertir p10_esperanza a entero
        if ($key === 'p10_esperanza' && $valor !== null) {
            $fila[$key] = intval($valor);
        }
    }
}

// ==================== RESPUESTA ====================
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>