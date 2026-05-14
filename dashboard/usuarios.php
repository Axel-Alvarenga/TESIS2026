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

$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll();

require_once 'header.php';

// Mostrar mensajes flash
echo mostrarFlashMessage();
?>

<a href="usuarios_crear.php" class="btn-crear"><i class="fas fa-plus"></i> Crear nuevo usuario</a>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th><i class="fas fa-envelope"></i> Correo</th>
                <th><i class="fas fa-user"></i> Nombre</th>
                <th><i class="fas fa-tag"></i> Rol</th>
                <th><i class="fas fa-circle"></i> Estado</th>
                <th><i class="fas fa-calendar"></i> Fecha</th>
                <th><i class="fas fa-cogs"></i> Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><strong><?= htmlspecialchars($usuario['email']) ?></strong></td>
                    <td><?= htmlspecialchars($usuario['nombre'] ?? '-') ?></td>
                    <td>
                        <?php if ($usuario['rol'] == 'admin'): ?>
                            <span class="badge-admin"><i class="fas fa-crown"></i> Administrador</span>
                        <?php else: ?>
                            <span class="badge-lector"><i class="fas fa-eye"></i> Lector</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($usuario['activo'] == 1): ?>
                            <span style="color: #48bb78;"><i class="fas fa-check-circle"></i> Activo</span>
                        <?php else: ?>
                            <span style="color: #e53e3e;"><i class="fas fa-times-circle"></i> Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($usuario['fecha_creacion'])) ?></td>
                    <td class="acciones">
                        <a href="usuarios_editar.php?id=<?= $usuario['id'] ?>" class="btn-editar"><i class="fas fa-edit"></i> Editar</a>
                        <?php if ($usuario['id'] != $_SESSION['admin_id']): ?>
                            <a href="usuarios_eliminar.php?id=<?= $usuario['id'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este usuario?')"><i class="fas fa-trash"></i> Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>