<?php
if (!isset($titulo)) $titulo = 'Dashboard';
if (!isset($icono)) $icono = 'fa-tachometer-alt';
if (!isset($active)) $active = '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $titulo ?> - Voces del Sur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Voces del Sur</h2>
                <p>Panel de control</p>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item <?= $active == 'inicio' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i><span>Inicio</span>
                </a>
                <a href="estadisticas.php" class="nav-item <?= $active == 'estadisticas' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i><span>Estadísticas</span>
                </a>
                <a href="cruces.php" class="nav-item <?= $active == 'cruces' ? 'active' : '' ?>">
                    <i class="fas fa-exchange-alt"></i><span>Cruces</span>
                </a>
                <a href="comentarios.php" class="nav-item <?= $active == 'comentarios' ? 'active' : '' ?>">
                    <i class="fas fa-comment-dots"></i><span>Comentarios</span>
                </a>
                <a href="exportar.php" class="nav-item <?= $active == 'exportar' ? 'active' : '' ?>">
                    <i class="fas fa-download"></i><span>Exportar</span>
                </a>
                <a href="usuarios.php" class="nav-item <?= $active == 'usuarios' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i><span>Usuarios</span>
                </a>
                <a href="logout.php" class="nav-item logout">
                    <i class="fas fa-sign-out-alt"></i><span>Cerrar sesión</span>
                </a>
            </nav>
        </aside>

        <!-- Botón para abrir menú en móvil (se crea con JS) -->

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="top-bar">
                <h1><i class="fas <?= $icono ?>"></i> <?= $titulo ?></h1>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['admin_nombre'] ?? 'Usuario') ?>
                </div>
            </div>