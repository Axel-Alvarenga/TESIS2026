<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias! - Voces del Sur</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .thanks-card {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: fadeIn 0.5s ease-in;
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
        }
        
        .info-message {
            background: #e6f7ff;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 4px solid #1890ff;
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
            transition: transform 0.2s;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thanks-card">
            <h1>✨ ¡Gracias por tu voz! ✨</h1>
            <p>Tu participación es muy valiosa para este proyecto de escucha genuina.</p>
            
            <div class="info-message">
                <strong>📢 Los resultados te van a llegar.</strong><br><br>
                Los resultados de este laboratorio de escucha se comunicarán por los 
                mismos canales por los que te llegó esta encuesta y también en las 
                páginas y redes oficiales de la Diócesis de Encarnación y de la 
                Universidad Católica. Tu voz no va al vacío.
            </div>
            
            <a href="index.php" class="btn-home">Volver al inicio</a>
        </div>
    </div>
</body>
</html>