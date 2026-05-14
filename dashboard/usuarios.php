<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Gestión de Usuarios';
$icono = 'fa-users';
$active = 'usuarios';

// Verificar permisos
if ($_SESSION['admin_rol'] !== 'admin') {
    redirigirConMensaje('index.php', 'error', 'No tienes permisos para acceder a esta página');
}

// Obtener usuarios
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll();

require_once 'header.php';

// Mostrar mensajes flash
echo mostrarFlashMessage();
?>

<div style="margin-bottom: 20px;">
    <a href="usuarios_crear.php" class="btn-crear" style="display: inline-flex; align-items: center; gap: 8px; background: #48bb78; color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none;">
        <i class="fas fa-plus"></i> Crear nuevo usuario
    </a>
</div>

<div class="table-container" style="background: white; border-radius: 24px; padding: 20px; overflow-x: auto;">
    <h3 style="margin-bottom: 20px; color: #4a5568;"><i class="fas fa-users"></i> Listado de usuarios</h3>
    
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; min-width: 700px;">
            <thead>
                <tr>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;">ID</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-envelope"></i> Correo</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-user"></i> Nombre</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-tag"></i> Rol</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-circle"></i> Estado</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-calendar"></i> Fecha</th>
                    <th style="padding: 12px; text-align: left; background: #f7fafc; border-bottom: 2px solid #e2e8f0;"><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;"><?= $usuario['id'] ?></td>
                        <td style="padding: 12px;"><strong><?= htmlspecialchars($usuario['email']) ?></strong></td>
                        <td style="padding: 12px;"><?= htmlspecialchars($usuario['nombre'] ?? '-') ?></td>
                        <td style="padding: 12px;">
                            <?php if ($usuario['rol'] == 'admin'): ?>
                                <span style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-crown"></i> Administrador
                                </span>
                            <?php else: ?>
                                <span style="background: #a0aec0; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-eye"></i> Lector
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;">
                            <?php if ($usuario['activo'] == 1): ?>
                                <span style="color: #48bb78;"><i class="fas fa-check-circle"></i> Activo</span>
                            <?php else: ?>
                                <span style="color: #e53e3e;"><i class="fas fa-times-circle"></i> Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 12px;"><?= date('d/m/Y', strtotime($usuario['fecha_creacion'])) ?></td>
                        <td style="padding: 12px;">
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a href="usuarios_editar.php?id=<?= $usuario['id'] ?>" style="background: #3182ce; color: white; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <?php if ($usuario['id'] != $_SESSION['admin_id']): ?>
                                    <a href="usuarios_eliminar.php?id=<?= $usuario['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?')" style="background: #e53e3e; color: white; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #718096;">No hay usuarios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Estilos específicos para la tabla de usuarios */
.table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
}

.table-container th {
    white-space: nowrap;
}

.table-container td {
    vertical-align: middle;
}

/* Para móviles */
@media (max-width: 768px) {
    .table-container table {
        font-size: 12px;
    }
    
    .table-container th,
    .table-container td {
        padding: 8px;
    }
    
    .btn-crear {
        font-size: 14px;
        padding: 8px 16px;
    }
}
</style>

<?php require_once 'footer.php'; ?>