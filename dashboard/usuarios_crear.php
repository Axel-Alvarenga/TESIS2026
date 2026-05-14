<?php
require_once 'auth.php';
require_once 'functions.php';
require_once '../db_config.php';

$titulo = 'Crear Nuevo Usuario';
$icono = 'fa-user-plus';
$active = 'usuarios';

if ($_SESSION['admin_rol'] !== 'admin') {
    redirigirConMensaje('index.php', 'error', 'No tienes permisos');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $rol = $_POST['rol'] ?? 'lector';
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    if (empty($email) || empty($password)) {
        $error = '❌ Correo y contraseña son obligatorios';
    } elseif (!validarEmail($email)) {
        $error = '❌ El correo electrónico no es válido';
    } elseif (strlen($password) < 4) {
        $error = '❌ La contraseña debe tener al menos 4 caracteres';
    } elseif (emailExiste($pdo, $email)) {
        $error = '❌ El correo electrónico ya está registrado';
    } else {
        $hash = hashPassword($password);
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password, nombre, rol, activo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $hash, $nombre, $rol, $activo]);
        redirigirConMensaje('usuarios.php', 'exito', 'Usuario creado correctamente');
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
            <input type="email" name="email" placeholder="ejemplo@dominio.com" required style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
            <small style="font-size: 0.75em; color: #718096;">Este será su usuario para iniciar sesión</small>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-lock"></i> Contraseña *</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
            <small style="font-size: 0.75em; color: #718096;">Mínimo 4 caracteres</small>
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-user"></i> Nombre completo</label>
            <input type="text" name="nombre" style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
        </div>
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568;"><i class="fas fa-tag"></i> Rol</label>
            <select name="rol" style="width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px;">
                <option value="lector"><i class="fas fa-eye"></i> Lector</option>
                <option value="admin"><i class="fas fa-crown"></i> Administrador</option>
            </select>
        </div>
        <div class="form-group" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="activo" value="1" checked style="width: auto;">
            <label style="margin: 0; font-weight: 600; color: #4a5568;"><i class="fas fa-check-circle"></i> Usuario activo</label>
        </div>
        <div class="form-group" style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; font-weight: 600;">
                <i class="fas fa-save"></i> Guardar usuario
            </button>
            <a href="usuarios.php" style="background: #a0aec0; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; display: inline-block;">
                <i class="fas fa-times"></i> Cancelar
            </a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>