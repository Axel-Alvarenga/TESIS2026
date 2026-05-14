<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Gestión de Comentarios';
$icono = 'fa-comment-dots';
$active = 'comentarios';

// Filtros
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
$sentimiento_filtro = $_GET['sentimiento'] ?? '';
$buscar = $_GET['buscar'] ?? '';

// Construir WHERE
$where = [];
$params = [];

if ($fecha_desde && $fecha_hasta) {
    $where[] = "DATE(r.fecha) BETWEEN ? AND ?";
    $params[] = $fecha_desde;
    $params[] = $fecha_hasta;
}
if ($sentimiento_filtro) {
    $where[] = "a.sentimiento = ?";
    $params[] = $sentimiento_filtro;
}
if ($buscar) {
    $where[] = "(r.campo_libre LIKE ? OR r.comentario_bloque2 LIKE ? OR r.comentario_bloque3 LIKE ? OR r.comentario_bloque4 LIKE ? OR r.comentario_bloque5 LIKE ? OR r.comentario_bloque6 LIKE ? OR r.comentario_bloque7 LIKE ? OR r.comentario_bloque8 LIKE ?)";
    $like = "%$buscar%";
    for ($i = 0; $i < 8; $i++) $params[] = $like;
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Obtener comentarios
$sql = "SELECT r.id, r.fecha, r.p1_anio, r.p2_parroquia, r.campo_libre, 
        r.comentario_bloque2, r.comentario_bloque3, r.comentario_bloque4, 
        r.comentario_bloque5, r.comentario_bloque6, r.comentario_bloque7, 
        r.comentario_bloque8, a.sentimiento 
        FROM respuestas r 
        LEFT JOIN analisis_texto a ON r.id = a.respuesta_id 
        $where_sql 
        AND (r.campo_libre IS NOT NULL AND r.campo_libre != '' 
             OR r.comentario_bloque2 IS NOT NULL AND r.comentario_bloque2 != ''
             OR r.comentario_bloque3 IS NOT NULL AND r.comentario_bloque3 != ''
             OR r.comentario_bloque4 IS NOT NULL AND r.comentario_bloque4 != ''
             OR r.comentario_bloque5 IS NOT NULL AND r.comentario_bloque5 != ''
             OR r.comentario_bloque6 IS NOT NULL AND r.comentario_bloque6 != ''
             OR r.comentario_bloque7 IS NOT NULL AND r.comentario_bloque7 != ''
             OR r.comentario_bloque8 IS NOT NULL AND r.comentario_bloque8 != '')
        ORDER BY r.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$comentarios = $stmt->fetchAll();

// Estadísticas de sentimiento
$sentimientos = $pdo->query("SELECT sentimiento, COUNT(*) as total FROM analisis_texto GROUP BY sentimiento")->fetchAll();
$total_comentarios = array_sum(array_column($sentimientos, 'total'));

require_once 'header.php';
?>

<!-- FILTROS -->
<div class="filtros-card">
    <h3><i class="fas fa-filter"></i> Filtros de comentarios</h3>
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
                <label><i class="fas fa-smile"></i> Sentimiento</label>
                <select name="sentimiento">
                    <option value="">Todos</option>
                    <option value="positivo" <?= $sentimiento_filtro == 'positivo' ? 'selected' : '' ?>>😊 Positivo</option>
                    <option value="neutral" <?= $sentimiento_filtro == 'neutral' ? 'selected' : '' ?>>😐 Neutral</option>
                    <option value="negativo" <?= $sentimiento_filtro == 'negativo' ? 'selected' : '' ?>>😞 Negativo</option>
                </select>
            </div>
            <div class="filtro-group">
                <label><i class="fas fa-search"></i> Buscar texto</label>
                <input type="text" name="buscar" placeholder="Palabra clave..." value="<?= htmlspecialchars($buscar) ?>">
            </div>
            <div class="filtro-group botones-group">
                <button type="submit" class="btn-filtrar"><i class="fas fa-search"></i> Filtrar</button>
                <a href="comentarios.php" class="btn-limpiar"><i class="fas fa-eraser"></i> Limpiar</a>
            </div>
        </div>
    </form>
</div>

<!-- ESTADÍSTICAS RÁPIDAS -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-comments"></i></div>
        <div class="stat-info"><h3><?= count($comentarios) ?></h3><p>Comentarios encontrados</p></div>
    </div>
    <?php foreach ($sentimientos as $s): ?>
        <div class="stat-card">
            <?php 
            $icono_sentimiento = 'meh';
            if ($s['sentimiento'] == 'positivo') $icono_sentimiento = 'smile';
            elseif ($s['sentimiento'] == 'negativo') $icono_sentimiento = 'frown';
            ?>
            <div class="stat-icon"><i class="fas fa-<?= $icono_sentimiento ?>"></i></div>
            <div class="stat-info">
                <h3><?= $s['total'] ?></h3>
                <p><?= ucfirst($s['sentimiento']) ?> (<?= $total_comentarios > 0 ? round(($s['total'] / $total_comentarios) * 100, 1) : 0 ?>%)</p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- LISTA DE COMENTARIOS -->
<div class="table-container">
    <h3><i class="fas fa-list"></i> Comentarios de participantes</h3>
    <div class="table-responsive">
        <table id="tablaComentarios" class="tabla-dinamica" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Edad</th>
                    <th>Parroquia</th>
                    <th>Sentimiento</th>
                    <th>Comentario</th>
                    <th>Bloque</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comentarios as $c): 
                    $comentarios_bloques = [];
                    if (!empty($c['comentario_bloque2'])) $comentarios_bloques[] = ['bloque' => 'II - Vínculos', 'texto' => $c['comentario_bloque2']];
                    if (!empty($c['comentario_bloque3'])) $comentarios_bloques[] = ['bloque' => 'III - Espiritualidad', 'texto' => $c['comentario_bloque3']];
                    if (!empty($c['comentario_bloque4'])) $comentarios_bloques[] = ['bloque' => 'IV - Familia', 'texto' => $c['comentario_bloque4']];
                    if (!empty($c['comentario_bloque5'])) $comentarios_bloques[] = ['bloque' => 'V - Proyecto de vida', 'texto' => $c['comentario_bloque5']];
                    if (!empty($c['comentario_bloque6'])) $comentarios_bloques[] = ['bloque' => 'VI - Vocación', 'texto' => $c['comentario_bloque6']];
                    if (!empty($c['comentario_bloque7'])) $comentarios_bloques[] = ['bloque' => 'VII - Crítica', 'texto' => $c['comentario_bloque7']];
                    if (!empty($c['comentario_bloque8'])) $comentarios_bloques[] = ['bloque' => 'VIII - Esperanza', 'texto' => $c['comentario_bloque8']];
                    
                    if (empty($comentarios_bloques) && !empty($c['campo_libre'])) {
                        $comentarios_bloques[] = ['bloque' => 'General', 'texto' => $c['campo_libre']];
                    }
                    
                    $sentimiento_clase = '';
                    $sentimiento_icono = '';
                    if ($c['sentimiento'] == 'positivo') {
                        $sentimiento_clase = 'badge-admin';
                        $sentimiento_icono = '😊';
                    } elseif ($c['sentimiento'] == 'negativo') {
                        $sentimiento_clase = 'btn-eliminar';
                        $sentimiento_icono = '😞';
                    } else {
                        $sentimiento_clase = 'badge-lector';
                        $sentimiento_icono = '😐';
                    }
                ?>
                    <?php foreach ($comentarios_bloques as $idx => $com): ?>
                        <tr class="fade-in">
                            <?php if ($idx === 0): ?>
                                <td rowspan="<?= count($comentarios_bloques) ?>"><?= $c['id'] ?></td>
                                <td rowspan="<?= count($comentarios_bloques) ?>"><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                                <td rowspan="<?= count($comentarios_bloques) ?>"><?= $c['p1_anio'] ? (2026 - intval($c['p1_anio'])) . ' años' : '-' ?></td>
                                <td rowspan="<?= count($comentarios_bloques) ?>"><?= htmlspecialchars(substr($c['p2_parroquia'] ?? '-', 0, 35)) ?></td>
                                <td rowspan="<?= count($comentarios_bloques) ?>"><span class="<?= $sentimiento_clase ?>" style="display: inline-block; padding: 4px 10px; border-radius: 20px;"><?= $sentimiento_icono ?> <?= ucfirst($c['sentimiento'] ?? 'Sin análisis') ?></span></td>
                            <?php endif; ?>
                            <td style="max-width: 400px; word-break: break-word;"><?= nl2br(htmlspecialchars(substr($com['texto'], 0, 200))) ?><?= strlen($com['texto']) > 200 ? '...' : '' ?></td>
                            <td><span class="badge-lector" style="background: #667eea; display: inline-block; padding: 4px 10px; border-radius: 20px;"><?= $com['bloque'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php if (empty($comentarios)): ?>
                    <tr><td colspan="7" style="text-align: center; padding: 40px;">No se encontraron comentarios con los filtros seleccionados</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/dashboard.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Comentarios - <?= count($comentarios) ?> registros cargados');
});
</script>

<?php require_once 'footer.php'; ?>