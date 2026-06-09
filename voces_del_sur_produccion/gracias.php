<?php
$es_repetida = isset($_GET['repetida']) && $_GET['repetida'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias! - Voces del Sur</title>
    <link rel="stylesheet" href="css/gracias.css">
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <!-- HEADER: SOLO LOGOS SIN TÍTULO -->
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