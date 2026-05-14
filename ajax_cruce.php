<?php
require_once 'db_config.php';

header('Content-Type: application/json');

$fila = $_GET['fila'] ?? '';
$columna = $_GET['columna'] ?? '';
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
$filtros = $_GET['filtros'] ?? [];

// Validar que las columnas existan
$columnas_validas = [
    'p1_anio', 'p2_parroquia', 'p3_pertenencia', 'p4_atraccion', 
    'p5_espiritualidad', 'p6_familia', 'p7_proyecto', 'p8_vocacion', 
    'p10_esperanza', 'sentimiento', 'p9_critica_1'
];

if (!in_array($fila, $columnas_validas) || !in_array($columna, $columnas_validas)) {
    echo json_encode(['error' => 'Columnas no válidas']);
    exit;
}

// Construir consulta
$sql = "
    SELECT 
        r.$fila as valor_fila,
        r.$columna as valor_columna,
        COUNT(*) as total
    FROM respuestas r
    LEFT JOIN analisis_texto at ON at.respuesta_id = r.id
    WHERE DATE(r.fecha) BETWEEN :desde AND :hasta
";

// Agregar filtros
if (!empty($filtros)) {
    foreach ($filtros as $key => $valor) {
        if (!empty($valor)) {
            $sql .= " AND r.$key = :filtro_$key";
        }
    }
}

$sql .= " GROUP BY r.$fila, r.$columna ORDER BY total DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':desde', $fecha_desde);
$stmt->bindParam(':hasta', $fecha_hasta);

// Bind de filtros
if (!empty($filtros)) {
    foreach ($filtros as $key => $valor) {
        if (!empty($valor)) {
            $stmt->bindParam(":filtro_$key", $valor);
        }
    }
}

$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
?>