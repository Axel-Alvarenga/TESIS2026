<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Exportar Datos';
$icono = 'fa-download';
$active = 'exportar';

// Obtener filtros
$fecha_desde = $_GET['fecha_desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['fecha_hasta'] ?? date('Y-m-d');
$parroquia_filtro = $_GET['parroquia'] ?? '';
$exportar = isset($_GET['exportar']) && $_GET['exportar'] == '1';

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
$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// ==================== SI SE SOLICITA EXPORTAR ====================
if ($exportar) {
    // Limpiar buffer de salida
    ob_clean();
    
    // Configurar cabeceras para descarga forzada
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="voces_del_sur_' . date('Y-m-d') . '.csv"');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Obtener datos
    $sql = "SELECT 
                id, fecha, ip, 
                p1_anio, 
                CASE 
                    WHEN p1_anio >= 2009 THEN '15-17 años'
                    WHEN p1_anio BETWEEN 2001 AND 2008 THEN '18-25 años'
                    WHEN p1_anio BETWEEN 1991 AND 2000 THEN '26-35 años'
                    WHEN p1_anio = 'antes_1991' THEN 'Antes de 1991'
                    WHEN p1_anio = 'despues_2011' THEN 'Después de 2011'
                    ELSE 'No especificado'
                END as grupo_edad,
                p2_parroquia,
                p3_pertenencia, p4_atraccion, p5_espiritualidad, p6_familia, 
                p7_proyecto, p8_vocacion, p9_critica, p10_esperanza,
                campo_libre, permiso_padres,
                comentario_bloque2, comentario_bloque3, comentario_bloque4,
                comentario_bloque5, comentario_bloque6, comentario_bloque7, comentario_bloque8
            FROM respuestas 
            $where_sql 
            ORDER BY id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $respuestas = $stmt->fetchAll();
    
    // Etiquetas
    $opcionesP3 = ['A' => 'Grupo de amigos', 'B' => 'Eucaristía/liturgia', 'C' => 'Ayudar a alguien', 'D' => 'Redes sociales', 'E' => 'Naturaleza', 'F' => 'Deporte', 'G' => 'Silencio/reflexión', 'H' => 'No recuerdo'];
    $opcionesP4 = ['A' => 'Vínculos de confianza', 'B' => 'Silencio', 'C' => 'Liderazgo', 'D' => 'Habilidades técnicas', 'E' => 'Emprendimiento', 'F' => 'Sin juicios', 'G' => 'Cambio real'];
    $opcionesP5 = ['A' => 'Fe como referencia', 'B' => 'A veces la fe', 'C' => 'Otros ámbitos', 'D' => 'No me pregunto'];
    $opcionesP6 = ['A' => 'Apoyo y refugio', 'B' => 'Tensiones', 'C' => 'No me entienden', 'D' => 'Motivación', 'E' => 'Sin referencia'];
    $opcionesP7 = ['A' => 'Estabilidad económica', 'B' => 'Familia', 'C' => 'Impacto social', 'D' => 'Paz interior', 'E' => 'En proceso'];
    $opcionesP8 = ['A' => 'Misión clara', 'B' => 'Miedo a equivocarme', 'C' => 'Presión social', 'D' => 'Plan de Dios', 'E' => 'No lo pienso'];
    
    $output = fopen('php://output', 'w');
    fwrite($output, "\xEF\xBB\xBF"); // BOM para Excel
    
    // Cabeceras
    fputcsv($output, ['ID', 'Fecha', 'IP', 'Año', 'Grupo edad', 'Parroquia', 'P3', 'P4', 'P5', 'P6', 'P7', 'P8', 'P9', 'P10', 'Comentario general', 'Permiso padres', 'Comentario B2', 'Comentario B3', 'Comentario B4', 'Comentario B5', 'Comentario B6', 'Comentario B7', 'Comentario B8']);
    
    // Datos
    foreach ($respuestas as $r) {
        fputcsv($output, [
            $r['id'],
            $r['fecha'],
            $r['ip'],
            $r['p1_anio'],
            $r['grupo_edad'],
            $r['p2_parroquia'],
            $opcionesP3[$r['p3_pertenencia']] ?? $r['p3_pertenencia'],
            $opcionesP4[$r['p4_atraccion']] ?? $r['p4_atraccion'],
            $opcionesP5[$r['p5_espiritualidad']] ?? $r['p5_espiritualidad'],
            $opcionesP6[$r['p6_familia']] ?? $r['p6_familia'],
            $opcionesP7[$r['p7_proyecto']] ?? $r['p7_proyecto'],
            $opcionesP8[$r['p8_vocacion']] ?? $r['p8_vocacion'],
            $r['p9_critica'],
            $r['p10_esperanza'],
            $r['campo_libre'],
            $r['permiso_padres'],
            $r['comentario_bloque2'],
            $r['comentario_bloque3'],
            $r['comentario_bloque4'],
            $r['comentario_bloque5'],
            $r['comentario_bloque6'],
            $r['comentario_bloque7'],
            $r['comentario_bloque8']
        ]);
    }
    
    fclose($output);
    exit;
}

// ==================== SI NO SE EXPORTA, MOSTRAR LA PÁGINA ====================

// Obtener datos para vista previa
$sql = "SELECT 
            id, fecha, ip, 
            p1_anio, 
            CASE 
                WHEN p1_anio >= 2009 THEN '15-17 años'
                WHEN p1_anio BETWEEN 2001 AND 2008 THEN '18-25 años'
                WHEN p1_anio BETWEEN 1991 AND 2000 THEN '26-35 años'
                WHEN p1_anio = 'antes_1991' THEN 'Antes de 1991'
                WHEN p1_anio = 'despues_2011' THEN 'Después de 2011'
                ELSE 'No especificado'
            END as grupo_edad,
            p2_parroquia,
            p10_esperanza,
            campo_libre
        FROM respuestas 
        $where_sql 
        ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$respuestas = $stmt->fetchAll();

// Obtener lista de parroquias para el filtro
$parroquias = $pdo->query("SELECT DISTINCT p2_parroquia FROM respuestas WHERE p2_parroquia IS NOT NULL AND p2_parroquia != '' ORDER BY p2_parroquia")->fetchAll();

require_once 'header.php';
?>

<!-- FILTROS -->
<div class="filtros-card">
    <h3><i class="fas fa-filter"></i> Filtros para exportar</h3>
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
                        <option value="<?= htmlspecialchars($p['p2_parroquia']) ?>" <?= $parroquia_filtro == $p['p2_parroquia'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['p2_parroquia']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filtro-group botones-group">
                <button type="submit" class="btn-filtrar"><i class="fas fa-search"></i> Filtrar</button>
                <button type="submit" name="exportar" value="1" class="btn-excel"><i class="fas fa-file-excel"></i> Exportar CSV</button>
            </div>
        </div>
    </form>
</div>

<!-- TABLA PREVIA -->
<div class="table-container">
    <h3><i class="fas fa-table"></i> Vista previa de datos a exportar (<?= count($respuestas) ?> registros)</h3>
    <div class="table-responsive">
        <table id="tablaExportar" class="tabla-dinamica">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Edad</th>
                    <th>Parroquia</th>
                    <th>Esperanza</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($respuestas, 0, 50) as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['fecha'])) ?></td>
                        <td><?= $r['grupo_edad'] ?></td>
                        <td><?= htmlspecialchars($r['p2_parroquia'] ?? '-') ?></td>
                        <td><span class="esperanza esperanza-<?= $r['p10_esperanza'] ?>"><?= str_repeat('★', $r['p10_esperanza'] ?? 0) ?><?= str_repeat('☆', 5 - ($r['p10_esperanza'] ?? 0)) ?></span></td>
                        <td><?= htmlspecialchars(substr($r['campo_libre'] ?? '', 0, 50)) ?>...</td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($respuestas) > 50): ?>
                    <tr><td colspan="6" style="text-align: center">... y <?= count($respuestas) - 50 ?> registros más. Exporta CSV para ver todos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Exportar - Listo, <?= count($respuestas) ?> registros disponibles');
        
        // Forzar que el formulario se envíe correctamente
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const exportBtn = this.querySelector('button[name="exportar"]');
                if (exportBtn && exportBtn === document.activeElement) {
                    // Si se hizo clic en Exportar CSV, asegurar que el parámetro se envía
                    console.log('Exportando CSV...');
                }
            });
        }
    });
</script>

<?php require_once 'footer.php'; ?>