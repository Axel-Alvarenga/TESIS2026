<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Estadísticas Detalladas';
$icono = 'fa-chart-line';
$active = 'estadisticas';

// ==================== FILTROS ====================
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
$parroquia_filtro = $_GET['parroquia'] ?? '';
$edad_filtro = $_GET['edad'] ?? '';

// Construir WHERE dinámico
$where = [];
$params = [];

if ($fecha_desde && $fecha_hasta) {
    $where[] = "DATE(fecha) BETWEEN ? AND ?";
    $params[] = $fecha_desde;
    $params[] = $fecha_hasta;
}
if ($parroquia_filtro) {
    $where[] = "p2_parroquia = ?";
    $params[] = $parroquia_filtro;
}
if ($edad_filtro) {
    if ($edad_filtro == '15-17') $where[] = "p1_anio >= 2009";
    elseif ($edad_filtro == '18-25') $where[] = "p1_anio BETWEEN 2001 AND 2008";
    elseif ($edad_filtro == '26-35') $where[] = "p1_anio BETWEEN 1991 AND 2000";
}
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Métricas
$sql_metrics = "SELECT COUNT(*) as total, SUM(CASE WHEN campo_libre != '' AND campo_libre IS NOT NULL THEN 1 ELSE 0 END) as comentarios, SUM(CASE WHEN p10_esperanza >= 4 THEN 1 ELSE 0 END) as esperanza_alta, AVG(p10_esperanza) as esperanza_promedio FROM respuestas $where_sql";
$stmt = $pdo->prepare($sql_metrics);
$stmt->execute($params);
$metrics = $stmt->fetch();

// Parroquias para filtro
$parroquias = $pdo->query("SELECT DISTINCT p2_parroquia FROM respuestas WHERE p2_parroquia IS NOT NULL AND p2_parroquia != '' ORDER BY p2_parroquia")->fetchAll();

// Datos para gráficos
$sql_edad = "SELECT CASE WHEN p1_anio >= 2009 THEN '15-17 años' WHEN p1_anio BETWEEN 2001 AND 2008 THEN '18-25 años' WHEN p1_anio BETWEEN 1991 AND 2000 THEN '26-35 años' ELSE 'Otros' END as grupo, COUNT(*) as total FROM respuestas $where_sql GROUP BY grupo";
$stmt_edad = $pdo->prepare($sql_edad);
$stmt_edad->execute($params);
$edad_stats = $stmt_edad->fetchAll();

$sql_sentimiento = "SELECT sentimiento, COUNT(*) as total FROM analisis_texto at INNER JOIN respuestas r ON at.respuesta_id = r.id $where_sql GROUP BY sentimiento";
$stmt_sentimiento = $pdo->prepare($sql_sentimiento);
$stmt_sentimiento->execute($params);
$sentimiento_stats = $stmt_sentimiento->fetchAll();

$sql_parroquias = "SELECT p2_parroquia, COUNT(*) as total FROM respuestas $where_sql AND p2_parroquia IS NOT NULL GROUP BY p2_parroquia ORDER BY total DESC LIMIT 10";
$stmt_parroquias = $pdo->prepare($sql_parroquias);
$stmt_parroquias->execute($params);
$parroquia_stats = $stmt_parroquias->fetchAll();

$sql_evolucion = "SELECT DATE(fecha) as dia, COUNT(*) as total FROM respuestas $where_sql GROUP BY DATE(fecha) ORDER BY dia ASC";
$stmt_evolucion = $pdo->prepare($sql_evolucion);
$stmt_evolucion->execute($params);
$evolucion_stats = $stmt_evolucion->fetchAll();

// Preguntas
$preguntas = ['p3_pertenencia', 'p4_atraccion', 'p5_espiritualidad', 'p6_familia', 'p7_proyecto', 'p8_vocacion', 'p10_esperanza'];
$preguntas_stats = [];
foreach ($preguntas as $pregunta) {
    $sql = "SELECT $pregunta as valor, COUNT(*) as total FROM respuestas $where_sql AND $pregunta IS NOT NULL GROUP BY $pregunta ORDER BY total DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $preguntas_stats[$pregunta] = $stmt->fetchAll();
}

// Detalle
$sql_detalle = "SELECT id, fecha, p1_anio, p2_parroquia, p10_esperanza, campo_libre FROM respuestas $where_sql ORDER BY id DESC LIMIT 100";
$stmt_detalle = $pdo->prepare($sql_detalle);
$stmt_detalle->execute($params);
$respuestas_detalle = $stmt_detalle->fetchAll();

require_once 'header.php';
?>

<!-- FILTROS -->
<div class="filtros-card">
    <h3><i class="fas fa-filter"></i> Filtros de análisis</h3>
    <form method="GET" class="filtros-form">
        <div class="filtros-grid">
            <div class="filtro-group">
                <label><i class="fas fa-calendar"></i> Desde</label>
                <input type="date" name="fecha_desde" value="<?= $fecha_desde ?>">
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-calendar"></i> Hasta</label>
                <input type="date" name="fecha_hasta" value="<?= $fecha_hasta ?>">
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-church"></i> Parroquia</label>
                <select name="parroquia">
                    <option value="">Todas</option>
                    <?php foreach ($parroquias as $p): ?>
                        <option value="<?= htmlspecialchars($p['p2_parroquia']) ?>" <?= $parroquia_filtro == $p['p2_parroquia'] ? 'selected' : '' ?>><?= htmlspecialchars($p['p2_parroquia']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-users"></i> Grupo etario</label>
                <select name="edad">
                    <option value="">Todos</option>
                    <option value="15-17" <?= $edad_filtro == '15-17' ? 'selected' : '' ?>>15-17 años</option>
                    <option value="18-25" <?= $edad_filtro == '18-25' ? 'selected' : '' ?>>18-25 años</option>
                    <option value="26-35" <?= $edad_filtro == '26-35' ? 'selected' : '' ?>>26-35 años</option>
                </select>
            </div>
            <div class="filtro-group botones-group">
                <button type="submit" class="btn-filtrar"><i class="fas fa-search"></i> Aplicar filtros</button>
                <a href="estadisticas.php" class="btn-limpiar"><i class="fas fa-eraser"></i> Limpiar</a>
                <button type="button" class="btn-excel" id="exportarExcel"><i class="fas fa-file-excel"></i> Exportar</button>
            </div>
        </div>
    </form>
</div>

<!-- MÉTRICAS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info"><h3><?= number_format($metrics['total']) ?></h3><p>Respuestas totales</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-comment"></i></div>
        <div class="stat-info"><h3><?= number_format($metrics['comentarios']) ?></h3><p>Comentarios</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-smile"></i></div>
        <div class="stat-info"><h3><?= number_format($metrics['esperanza_alta']) ?></h3><p>Esperanza alta (4-5)</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info"><h3><?= number_format($metrics['esperanza_promedio'], 1) ?></h3><p>Esperanza promedio</p></div>
    </div>
</div>

<!-- GRÁFICOS -->
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-chart-bar"></i> Distribución por edad</h3><canvas id="edadChart"></canvas></div>
    <div class="chart-card"><h3><i class="fas fa-smile"></i> Análisis de sentimiento</h3><canvas id="sentimientoChart"></canvas></div>
</div>
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-church"></i> Top 10 parroquias</h3><canvas id="parroquiaChart"></canvas></div>
    <div class="chart-card"><h3><i class="fas fa-chart-line"></i> Evolución temporal</h3><canvas id="evolucionChart"></canvas></div>
</div>
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-handshake"></i> P3 - Pertenencia</h3><canvas id="p3Chart"></canvas></div>
    <div class="chart-card"><h3><i class="fas fa-magnet"></i> P4 - Atracción</h3><canvas id="p4Chart"></canvas></div>
</div>
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-pray"></i> P5 - Espiritualidad</h3><canvas id="p5Chart"></canvas></div>
    <div class="chart-card"><h3><i class="fas fa-home"></i> P6 - Familia</h3><canvas id="p6Chart"></canvas></div>
</div>
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-bullseye"></i> P7 - Proyecto de vida</h3><canvas id="p7Chart"></canvas></div>
    <div class="chart-card"><h3><i class="fas fa-compass"></i> P8 - Vocación</h3><canvas id="p8Chart"></canvas></div>
</div>
<div class="charts-grid">
    <div class="chart-card"><h3><i class="fas fa-chart-simple"></i> P10 - Escala de esperanza</h3><canvas id="p10Chart"></canvas></div>
</div>

<!-- TABLA DETALLE -->
<div class="table-container">
    <h3><i class="fas fa-table"></i> Detalle de respuestas</h3>
    <div class="table-responsive">
        <table id="tablaRespuestas" class="tabla-dinamica">
            <thead><tr><th onclick="sortTable(0)">ID</th><th onclick="sortTable(1)">Fecha</th><th onclick="sortTable(2)">Edad</th><th onclick="sortTable(3)">Parroquia</th><th onclick="sortTable(4)">Esperanza</th><th>Comentario</th></tr></thead>
            <tbody>
                <?php foreach ($respuestas_detalle as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
                    <td><?= $r['p1_anio'] ? (2026 - intval($r['p1_anio'])) . ' años' : '-' ?></td>
                    <td><?= htmlspecialchars($r['p2_parroquia'] ?? '-') ?></td>
                    <td><span class="esperanza esperanza-<?= $r['p10_esperanza'] ?>"><?= str_repeat('★', $r['p10_esperanza']) ?><?= str_repeat('☆', 5 - $r['p10_esperanza']) ?></span></td>
                    <td><?= htmlspecialchars(substr($r['campo_libre'] ?? '', 0, 100)) ?>...</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
<script>
// Pasar datos de PHP a JavaScript
const datosGraficos = {
    edadLabels: <?= json_encode(array_column($edad_stats, 'grupo')) ?>,
    edadData: <?= json_encode(array_column($edad_stats, 'total')) ?>,
    sentimientoLabels: <?= json_encode(array_column($sentimiento_stats, 'sentimiento')) ?>,
    sentimientoData: <?= json_encode(array_column($sentimiento_stats, 'total')) ?>,
    parroquiaLabels: <?= json_encode(array_column($parroquia_stats, 'p2_parroquia')) ?>,
    parroquiaData: <?= json_encode(array_column($parroquia_stats, 'total')) ?>,
    evolucionLabels: <?= json_encode(array_column($evolucion_stats, 'dia')) ?>,
    evolucionData: <?= json_encode(array_column($evolucion_stats, 'total')) ?>,
    p3Chart: { labels: <?= json_encode(array_column($preguntas_stats['p3_pertenencia'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p3_pertenencia'], 'total')) ?> },
    p4Chart: { labels: <?= json_encode(array_column($preguntas_stats['p4_atraccion'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p4_atraccion'], 'total')) ?> },
    p5Chart: { labels: <?= json_encode(array_column($preguntas_stats['p5_espiritualidad'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p5_espiritualidad'], 'total')) ?> },
    p6Chart: { labels: <?= json_encode(array_column($preguntas_stats['p6_familia'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p6_familia'], 'total')) ?> },
    p7Chart: { labels: <?= json_encode(array_column($preguntas_stats['p7_proyecto'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p7_proyecto'], 'total')) ?> },
    p8Chart: { labels: <?= json_encode(array_column($preguntas_stats['p8_vocacion'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p8_vocacion'], 'total')) ?> },
    p10Chart: { labels: <?= json_encode(array_column($preguntas_stats['p10_esperanza'], 'valor')) ?>, data: <?= json_encode(array_column($preguntas_stats['p10_esperanza'], 'total')) ?> }
};
</script>
<script src="js/estadisticas.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => renderizarGraficos(datosGraficos));
function sortTable(columna) { /* función de ordenamiento */ }
</script>

<?php require_once 'footer.php'; ?>