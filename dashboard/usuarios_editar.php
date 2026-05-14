<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Editar Usuario';
$icono = 'fa-user-edit';
$active = 'usuarios';

if ($_SESSION['admin_rol'] !== 'admin') {
    redirigirConMensaje('index.php', 'error', 'No tienes permisos');
}

$id = $_GET['id'] ?? 0;
$user = obtenerUsuarioPorId($pdo, $id);

if (!$user) {
    redirigirConMensaje('usuarios.php', 'error', 'Usuario no encontrado');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $rol = $_POST['rol'] ?? 'lector';
    $activo = isset($_POST['activo']) ? 1 : 0;
    $password = $_POST['password'] ?? '';
    
    if (empty($email)) {
        $error = '❌ El correo electrónico es obligatorio';
    } elseif (!validarEmail($email)) {
        $error = '❌ El correo electrónico no es válido';
    } elseif (emailExiste($pdo, $email, $id)) {
        $error = '❌ El correo electrónico ya está registrado por otro usuario';
    } else {
        if (!empty($password)) {
            if (strlen($password) < 4) {
                $error = '❌ La contraseña debe tener al menos 4 caracteres';
            } else {
                $hash = hashPassword($password);
                $stmt = $pdo->prepare("UPDATE usuarios SET email = ?, nombre = ?, rol = ?, activo = ?, password = ? WHERE id = ?");
                $stmt->execute([$email, $nombre, $rol, $activo, $hash, $id]);
                redirigirConMensaje('usuarios.php', 'exito', 'Usuario actualizado correctamente');
            }
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET email = ?, nombre = ?, rol = ?, activo = ? WHERE id = ?");
            $stmt->execute([$email, $nombre, $rol, $activo, $id]);
            redirigirConMensaje('usuarios.php', 'exito', 'Usuario actualizado correctamente');
        }
    }
}

require_once 'header.php';
?>

<div class="form-container" style="max-width: 600px; margin: 0 auto;">
    <?php if ($error): ?>
        <div class="mensaje mensaje-error" style="background: #fed7d7; color: #742a2a; padding: 12px; border-radius: 12px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" style="background: white; border-radius: 24px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-envelope"></i> Correo electrónico *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-lock"></i> Nueva contraseña</label>
            <input type="password" name="password" style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
            <small style="font-size: 0.75em; color: #718096;">Dejar en blanco para no cambiar</small>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-user"></i> Nombre completo</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-tag"></i> Rol</label>
            <select name="rol" style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
                <option value="lector" <?= $user['rol'] == 'lector' ? 'selected' : '' ?>>Lector</option>
                <option value="admin" <?= $user['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="activo" value="1" <?= $user['activo'] == 1 ? 'checked' : '' ?> style="width: auto;">
            <label style="margin: 0; font-weight: 600; color: #4a5568;"><i class="fas fa-check-circle"></i> Usuario activo</label>
        </div>
        <div class="form-group" style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
            <a href="usuarios.php" style="background: #a0aec0; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; display: inline-block;">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>