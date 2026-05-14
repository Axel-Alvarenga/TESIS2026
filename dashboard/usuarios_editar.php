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

<div class="form-container">
    <?php if ($error): ?>
        <div class="mensaje mensaje-error"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Correo electrónico *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label><i class="fas fa-lock"></i> Nueva contraseña</label>
            <input type="password" name="password">
            <small>Dejar en blanco para no cambiar</small>
        </div>
        <div class="form-group">
            <label><i class="fas fa-user"></i> Nombre completo</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label><i class="fas fa-tag"></i> Rol</label>
            <select name="rol">
                <option value="lector" <?= $user['rol'] == 'lector' ? 'selected' : '' ?>>Lector</option>
                <option value="admin" <?= $user['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>
        <div class="form-group checkbox-group">
            <input type="checkbox" name="activo" value="1" <?= $user['activo'] == 1 ? 'checked' : '' ?>>
            <label><i class="fas fa-check-circle"></i> Usuario activo</label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> Guardar cambios</button>
            <a href="usuarios.php" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </form>
</div>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }
.form-group input, .form-group select { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; }
.btn-guardar { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; margin-right: 10px; }
.btn-cancelar { background: #a0aec0; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; display: inline-block; }
.checkbox-group { display: flex; align-items: center; gap: 10px; }
.checkbox-group input { width: auto; }
</style>

<?php require_once 'footer.php'; ?>