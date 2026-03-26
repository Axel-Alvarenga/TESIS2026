<?php
// Detectar si es respuesta repetida por el parámetro en la URL
$es_repetida = isset($_GET['repetida']) && $_GET['repetida'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias! - Voces del Sur</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .thanks-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .thanks-card h1 {
            color: #48bb78;
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        .thanks-card p {
            font-size: 1.2em;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .warning-card {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: left;
        }

        .warning-card h3 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .warning-card p {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 1em;
        }

        .warning-card p:last-child {
            margin-bottom: 0;
        }

        .info-message {
            background: #e6f7ff;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 4px solid #1890ff;
            text-align: left;
        }

        .info-message strong {
            color: #0050b3;
            font-size: 1.1em;
        }

        .info-message p {
            margin: 10px 0 0 0;
            font-size: 1em;
            color: #2c3e50;
        }

        .suggestion-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }

        .suggestion-box strong {
            color: #2d3748;
            display: block;
            margin-bottom: 8px;
        }

        .suggestion-box p {
            margin: 0;
            font-size: 0.95em;
            color: #4a5568;
        }

        .btn-home {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .footer-note {
            margin-top: 30px;
            font-size: 0.85em;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }

        .emoji-big {
            font-size: 3em;
            margin-bottom: 20px;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .thanks-card {
                padding: 30px 20px;
            }
            
            .thanks-card h1 {
                font-size: 1.8em;
            }
            
            .thanks-card p {
                font-size: 1em;
            }
            
            .warning-card p,
            .info-message p,
            .suggestion-box p {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <?php if ($es_repetida): ?>
                <div class="emoji-big">⚠️</div>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu respuesta ha sido guardada correctamente.</p>
                
                <!-- <div class="warning-card">
                    <h3>📌 Aviso importante</h3>
                    <p><strong>Hemos detectado que desde este dispositivo ya se ha respondido la encuesta anteriormente.</strong></p>
                    <p>• <strong>Si eres un familiar diferente:</strong> No te preocupes, tu respuesta ha sido guardada correctamente. Te recomendamos usar un <strong>código familiar</strong> (como "FamiliaPerez" o "Casa123") para que podamos identificar que son respuestas de diferentes personas del mismo hogar.</p>
                    <p>• <strong>Si ya respondiste antes:</strong> Tu respuesta anterior se mantiene, pero valoramos mucho tu interés. Por favor, considera que lo más valioso es que cada persona responda una sola vez para mantener la representatividad de la encuesta.</p>
                </div> -->
            <?php else: ?>
                <div class="emoji-big">✨</div>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu participación es muy valiosa para este proyecto de escucha genuina. Cada respuesta nos ayuda a entender mejor la realidad de los jóvenes de Itapúa.</p>
            <?php endif; ?>
            
            <div class="info-message">
                <strong>📢 Los resultados te van a llegar</strong>
                <p>Los resultados de este laboratorio de escucha se comunicarán por los mismos canales por los que te llegó esta encuesta y también en las páginas y redes oficiales de la Diócesis de Encarnación y de la Universidad Católica. Tu voz no va al vacío.</p>
            </div>
            
            <?php if ($es_repetida): ?>
                <div class="suggestion-box">
                    <strong>💡 Sugerencia para familias</strong>
                    <p>Si otras personas de tu familia aún no han respondido, pueden hacerlo desde este mismo dispositivo. Para ayudarnos a identificar las respuestas de cada uno, pueden usar un código familiar común (ej: "ApellidoGomez" o "Casa123") en el campo de código familiar que aparece al inicio de la encuesta.</p>
                </div>
            <?php else: ?>
                <div class="suggestion-box">
                    <strong>🙏 Ayúdanos a llegar a más jóvenes</strong>
                    <p>Si conoces a otros jóvenes de Itapúa que aún no han participado, compárteles el enlace de esta encuesta. Entre más voces escuchemos, mejor podremos diseñar propuestas que realmente respondan a sus necesidades.</p>
                </div>
            <?php endif; ?>
            
            <a href="index.php" class="btn-home">← Volver al inicio</a>
            
            <div class="footer-note">
                <small>Proyecto Voces del Sur · Diócesis de la Santísima Encarnación · Universidad Católica Nuestra Señora de la Asunción · Pastoral de Juventud · Encarnación, Paraguay · 2026</small>
            </div>
        </div>
    </div>
</body>
</html>