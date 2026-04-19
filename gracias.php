<?php
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
            background: linear-gradient(135deg, #00093e 0%, #001a7a 100%);
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
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
            text-align: center;
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

        /* Header con logos en esquinas y logo central - SIN TEXTOS */
        .header-gracias {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
            text-align: center;
        }

        .logo-gracias {
            flex-shrink: 0;
            text-align: center;
        }

        .logo-gracias img {
            height: 45px;
            width: auto;
        }

        .logo-gracias-central img {
            height: 40px;
            width: auto;
        }

        .titulo-gracias {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo-gracias h2 {
            color: #00093e;
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .titulo-gracias p {
            color: #718096;
            font-size: 0.8em;
        }

        .thanks-card h1 {
            color: #00093e;
            font-size: 2em;
            margin: 20px 0 15px 0;
        }

        .thanks-card p {
            font-size: 1em;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .info-message {
            background: #e8f0fe;
            padding: 18px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #00093e;
            text-align: left;
        }

        .info-message strong {
            color: #00093e;
            font-size: 1em;
        }

        .info-message p {
            margin: 8px 0 0 0;
            font-size: 0.9em;
            color: #2c3e50;
            margin-bottom: 0;
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
            color: #00093e;
            display: block;
            margin-bottom: 6px;
            font-size: 0.9em;
        }

        .suggestion-box p {
            margin: 0;
            font-size: 0.85em;
            color: #4a5568;
            margin-bottom: 0;
        }

        .btn-home {
            background: linear-gradient(135deg, #00093e 0%, #001a7a 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 14px;
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

        .footer-note {
            margin-top: 25px;
            font-size: 0.75em;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            text-align: center;
        }

        .emoji-big {
            font-size: 2.5em;
            margin: 10px 0 5px 0;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .thanks-card {
                padding: 25px 20px;
            }
            
            .thanks-card h1 {
                font-size: 1.6em;
            }
            
            .thanks-card p {
                font-size: 0.9em;
            }
            
            .header-gracias {
                flex-direction: column;
                gap: 12px;
            }
            
            .logo-gracias img {
                height: 40px;
            }
            
            .logo-gracias-central img {
                height: 35px;
            }
            
            .titulo-gracias h2 {
                font-size: 1.3em;
            }
            
            .info-message {
                padding: 15px;
            }
            
            .info-message p {
                font-size: 0.85em;
            }
            
            .suggestion-box p {
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <!-- HEADER: SOLO LOGOS SIN TEXTOS -->
            <div class="header-gracias">
                <div class="logo-gracias">
                    <img src="img/LOGOUCCAMPUSITAPÚA.png" alt="Universidad Católica">
                </div>
                <div class="logo-gracias-central">
                    <img src="img/bie-cat.jpeg" alt="BIE CAT">
                </div>
                <div class="logo-gracias">
                    <img src="img/logodio.png" alt="Diócesis de Encarnación">
                </div>
            </div>
            
            <!-- TÍTULO PRINCIPAL -->
            <div class="titulo-gracias">
                <h2>Voces del Sur</h2>
                <p>Proyecto de escucha genuina</p>
            </div>
            
            <?php if ($es_repetida): ?>
                <div class="emoji-big">⚠️</div>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu respuesta ha sido guardada correctamente.</p>
            <?php else: ?>
                <div class="emoji-big">✨</div>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu participación es muy valiosa para este proyecto de escucha genuina. Cada respuesta nos ayuda a entender mejor la realidad de los jóvenes de Itapúa.</p>
            <?php endif; ?>
            
            <div class="info-message">
                <strong>📢 Los resultados te van a llegar</strong>
                <p>Los resultados de este laboratorio de escucha se comunicarán por los mismos canales por los que te llegó esta encuesta y también en las páginas y redes oficiales de la Diócesis de Encarnación y de la Universidad Católica. Tu voz no va al vacío.</p>
            </div>
            
            <div class="suggestion-box">
                <strong>🙏 Ayúdanos a llegar a más jóvenes</strong>
                <p>Si conoces a otros jóvenes de Itapúa que aún no han participado, compárteles el enlace de esta encuesta. Entre más voces escuchemos, mejor podremos diseñar propuestas que realmente respondan a sus necesidades.</p>
            </div>
            
            <a href="index.php" class="btn-home">← Volver al inicio</a>
            
            <div class="footer-note">
                <small>Proyecto Voces del Sur · Diócesis de la Santísima Encarnación · Universidad Católica Nuestra Señora de la Asunción · Pastoral de Juventud · Encarnación, Paraguay · 2026</small>
            </div>
        </div>
    </div>
</body>
</html>