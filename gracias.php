<?php $es_repetida = isset($_GET['repetida']) && $_GET['repetida'] == '1'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>¡Gracias! - Voces del Sur</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <div class="logos-header">
                <div class="logo-left"><img src="img/LOGOUCCAMPUSITAPÚA.png" alt="UC" class="logo-img"></div>
                <div class="logo-center"><img src="img/bie-cat.jpeg" alt="Bienvenida" class="logo-img-center"></div>
                <div class="logo-right"><img src="img/logodio.png" alt="Diócesis" class="logo-img"></div>
            </div>
            
            <?php if ($es_repetida): ?>
                <h1>¡Gracias por tu voz!</h1>
                <p>Tu respuesta ha sido guardada correctamente.</p>
                <div class="warning-card">
                    <h3>📌 Aviso importante</h3>
                    <p>Hemos detectado que desde este dispositivo ya se ha respondido la encuesta anteriormente.</p>
                    <p><strong>Si eres un familiar diferente:</strong> No te preocupes, tu respuesta ha sido guardada correctamente.</p>
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
                    <p>Si otras personas de tu familia aún no han respondido, pueden hacerlo desde este mismo dispositivo.</p>
                </div>
            <?php else: ?>
                <div class="suggestion-box">
                    <strong>🙏 Ayúdanos a llegar a más jóvenes</strong>
                    <p>Comparte el enlace de esta encuesta. Entre más voces escuchemos, mejor podremos diseñar propuestas que realmente respondan a sus necesidades.</p>
                </div>
            <?php endif; ?>
            
            <a href="index.php" class="btn-secondary">← Volver al inicio</a>
            <div class="footer-note"><small>Proyecto Voces del Sur · Diócesis de la Santísima Encarnación · Universidad Católica Nuestra Señora de la Asunción · Pastoral de Juventud · Encarnación, Paraguay · 2026</small></div>
        </div>
    </div>
</body>
</html>