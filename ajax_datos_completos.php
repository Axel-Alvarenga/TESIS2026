<?php
require_once 'db_config.php';

header('Content-Type: application/json');

$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');

// Obtener respuestas con sentimiento
$sql = "
    SELECT 
        r.*,
        at.sentimiento
    FROM respuestas r
    LEFT JOIN analisis_texto at ON at.respuesta_id = r.id
    WHERE DATE(r.fecha) BETWEEN :desde AND :hasta
    ORDER BY r.id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':desde' => $fecha_desde, ':hasta' => $fecha_hasta]);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar p9_critica (separar por comas si es necesario)
foreach ($datos as &$row) {
    // Si p9_critica tiene múltiples valores separados por comas
    if (isset($row['p9_critica']) && strpos($row['p9_critica'], ',') !== false) {
        $valores = explode(',', $row['p9_critica']);
        $row['p9_critica_1'] = trim($valores[0] ?? '');
        $row['p9_critica_2'] = trim($valores[1] ?? '');
    } else {
        $row['p9_critica_1'] = $row['p9_critica'] ?? '';
        $row['p9_critica_2'] = '';
    }
    
    // Limpiar valores nulos
    foreach ($row as $key => $value) {
        if ($value === null) $row[$key] = '';
    }
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE);
?>