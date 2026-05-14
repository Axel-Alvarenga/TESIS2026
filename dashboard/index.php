<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Panel de Administración';
$icono = 'fa-tachometer-alt';
$active = 'inicio';

// Obtener estadísticas
$total_respuestas = $pdo->query("SELECT COUNT(*) FROM respuestas")->fetchColumn();
$total_comentarios = $pdo->query("SELECT COUNT(*) FROM respuestas WHERE campo_libre != '' AND campo_libre IS NOT NULL")->fetchColumn();
$ultima_respuesta = $pdo->query("SELECT MAX(fecha) FROM respuestas")->fetchColumn();

// Respuestas por edad
$edad_stats = $pdo->query("
    SELECT 
        CASE 
            WHEN p1_anio >= 2009 THEN '15-17 años'
            WHEN p1_anio BETWEEN 2001 AND 2008 THEN '18-25 años'
            WHEN p1_anio BETWEEN 1991 AND 2000 THEN '26-35 años'
            ELSE 'Otros'
        END as grupo_edad,
        COUNT(*) as total
    FROM respuestas
    GROUP BY grupo_edad
")->fetchAll();

// Sentimiento
$sentimiento_stats = $pdo->query("
    SELECT sentimiento, COUNT(*) as total 
    FROM analisis_texto 
    GROUP BY sentimiento
")->fetchAll();

require_once 'header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-info">
            <h3><?= number_format($total_respuestas) ?></h3>
            <p>Respuestas totales</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-comment"></i></div>
        <div class="stat-info">
            <h3><?= number_format($total_comentarios) ?></h3>
            <p>Comentarios recibidos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <h3><?= $ultima_respuesta ? date('d/m/Y', strtotime($ultima_respuesta)) : 'Sin datos' ?></h3>
            <p>Última respuesta</p>
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Respuestas por grupo de edad</h3>
        <canvas id="edadChart"></canvas>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-smile"></i> Análisis de sentimiento</h3>
        <canvas id="sentimientoChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('edadChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($edad_stats, 'grupo_edad')) ?>,
            datasets: [{
                label: 'Cantidad',
                data: <?= json_encode(array_column($edad_stats, 'total')) ?>,
                backgroundColor: '#667eea',
                borderRadius: 10
            }]
        }
    });

    new Chart(document.getElementById('sentimientoChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($sentimiento_stats, 'sentimiento')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($sentimiento_stats, 'total')) ?>,
                backgroundColor: ['#48bb78', '#e53e3e', '#a0aec0']
            }]
        }
    });
</script>

<?php require_once 'footer.php'; ?>