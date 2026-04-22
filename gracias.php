<?php
// Detectar si es respuesta repetida por el parámetro en la URL
$es_repetida = isset($_GET['repetida']) && $_GET['repetida'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>¡Gracias! - Voces del Sur</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .thanks-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
        }

        .thanks-card h1 {
            color: #48bb78;
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 20px;
        }

        .thanks-card p {
            font-size: 1.2em;
            color: #4a5568;
            text-align: center;
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
            text-align: left;
            font-size: 1em;
            margin-bottom: 10px;
        }

        .warning-card p:last-child {
            margin-bottom: 0;
        }

        .info-message {
            background: #e6f7ff;
            border-left: 4px solid #1890ff;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            text-align: left;
        }

        .info-message strong {
            color: #0050b3;
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .info-message p {
            margin: 0;
            font-size: 1em;
            color: #2c3e50;
            text-align: left;
        }

        .suggestion-box {
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
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
            text-align: left;
        }

        .btn-home {
            background: #00093e;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            margin-top: 20px;
            width: 100%;
            text-align: center;
            font-weight: 500;
        }

        .btn-home:hover {
            background: #001a7a;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .footer-note {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 0.75em;
            color: #718096;
        }

        @media (max-width: 768px) {
            .thanks-card {
                padding: 20px;
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
            .btn-home {
                padding: 12px 20px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <div class="header-principal">
                <div class="logo-izquierda">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica Campus Itapúa" class="logo-img">
                </div>
                <div class="logo-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT" class="logo-img">
                </div>
                <div class="logo-derecha">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación" class="logo-img">
                </div>
            </div>
            
            <?php if ($es_repetida): ?>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu respuesta ha sido guardada correctamente.</p>
                
                <div class="warning-card">
                    <h3>📌 Aviso importante</h3>
                    <p>Hemos detectado que desde este dispositivo ya se ha respondido la encuesta anteriormente.</p>
                    <p><strong>Si eres un familiar diferente:</strong> No te preocupes, tu respuesta ha sido guardada correctamente.</p>
                    <p><strong>Si ya respondiste antes:</strong> Tu respuesta anterior se mantiene. Gracias por tu interés.</p>
                </div>
            <?php else: ?>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu participación es muy valiosa para este proyecto de escucha genuina. Cada respuesta nos ayuda a entender mejor la realidad de los jóvenes de Itapúa.</p>
            <?php endif; ?>
            
            <div class="info-message">
                <strong>📢 Los resultados te van a llegar</strong>
                <p>Los resultados de este laboratorio de escucha se comunicarán por los mismos canales por los que te llegó esta encuesta y también en las páginas y redes oficiales de la Diócesis de Encarnación y de la Universidad Católica. Tu voz no va al vacío.</p>
            </div>
            
            <?php if ($es_repetida): ?>
                <div class="suggestion-box">
                    <strong>💡 Sugerencia</strong>
                    <p>Si otras personas de tu familia aún no han respondido, pueden hacerlo desde este mismo dispositivo. Todas las respuestas son igualmente valiosas.</p>
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