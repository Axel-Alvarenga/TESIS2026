<?php
require_once 'db_config.php';

// Estadísticas generales
$total_textos = $pdo->query("SELECT COUNT(*) FROM respuestas WHERE campo_libre != ''")->fetchColumn();

$sentimientos = $pdo->query("SELECT sentimiento, COUNT(*) as total FROM analisis_texto GROUP BY sentimiento")->fetchAll();

$temas = $pdo->query("SELECT tema_principal, COUNT(*) as total FROM analisis_texto GROUP BY tema_principal ORDER BY total DESC")->fetchAll();

// Ejemplos de respuestas por sentimiento
$ejemplos_positivos = $pdo->query("SELECT texto_original FROM analisis_texto WHERE sentimiento = 'positivo' LIMIT 3")->fetchAll();
$ejemplos_negativos = $pdo->query("SELECT texto_original FROM analisis_texto WHERE sentimiento = 'negativo' LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard NLP - Voces del Sur</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #4a5568;
            text-align: center;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h3 {
            margin: 0 0 10px 0;
            color: #667eea;
        }
        .card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #4a5568;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .examples {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }
        .example-positive {
            border-left: 4px solid #48bb78;
            padding: 10px;
            margin: 10px 0;
            background: #f0fff4;
        }
        .example-negative {
            border-left: 4px solid #f56565;
            padding: 10px;
            margin: 10px 0;
            background: #fff5f5;
        }
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Análisis de Lenguaje Natural</h1>
        <p style="text-align: center; color: #718096;">Voces del Sur - Procesamiento de respuestas de texto libre</p>
        
        <div class="stats">
            <div class="card">
                <h3>📝 Respuestas analizadas</h3>
                <div class="number"><?= $total_textos ?></div>
            </div>
            <div class="card">
                <h3>😊 Sentimiento positivo</h3>
                <div class="number"><?= $sentimientos[0]['total'] ?? 0 ?></div>
            </div>
            <div class="card">
                <h3>😞 Sentimiento negativo</h3>
                <div class="number"><?= $sentimientos[1]['total'] ?? 0 ?></div>
            </div>
        </div>
        
        <div class="grid-2">
            <div class="chart-card">
                <h3>Sentimiento de las respuestas</h3>
                <canvas id="sentimientoChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Temas principales</h3>
                <canvas id="temasChart"></canvas>
            </div>
        </div>
        
        <div class="examples">
            <h3>💬 Ejemplos de respuestas positivas</h3>
            <?php foreach($ejemplos_positivos as $ejemplo): ?>
                <div class="example-positive">✨ <?= htmlspecialchars($ejemplo['texto_original']) ?></div>
            <?php endforeach; ?>
            
            <h3 style="margin-top: 20px;">💬 Ejemplos de respuestas negativas</h3>
            <?php foreach($ejemplos_negativos as $ejemplo): ?>
                <div class="example-negative">⚠️ <?= htmlspecialchars($ejemplo['texto_original']) ?></div>
            <?php endforeach; ?>
        </div>
        
        <div class="chart-card" style="margin-top: 20px;">
            <h3>☁️ Nube de palabras</h3>
            <img src="nube_palabras.png" alt="Nube de palabras" style="width: 100%; border-radius: 10px;">
        </div>
    </div>
    
    <script>
        // Gráfico de sentimiento
        const sentimientoCtx = document.getElementById('sentimientoChart').getContext('2d');
        new Chart(sentimientoCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($sentimientos, 'sentimiento')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($sentimientos, 'total')) ?>,
                    backgroundColor: ['#48bb78', '#f56565', '#a0aec0']
                }]
            }
        });
        
        // Gráfico de temas
        const temasCtx = document.getElementById('temasChart').getContext('2d');
        new Chart(temasCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($temas, 'tema_principal')) ?>,
                datasets: [{
                    label: 'Cantidad de menciones',
                    data: <?= json_encode(array_column($temas, 'total')) ?>,
                    backgroundColor: '#667eea'
                }]
            }
        });
    </script>
</body>
</html>