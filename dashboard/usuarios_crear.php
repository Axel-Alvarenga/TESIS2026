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

<div class="form-container">
    <?php if ($error): ?>
        <div class="mensaje mensaje-error"><i class="fas fa-exclamation-triangle"></i> <?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Correo electrónico *</label>
            <input type="email" name="email" placeholder="ejemplo@dominio.com" required>
            <small>Este será su usuario para iniciar sesión</small>
        </div>
        <div class="form-group">
            <label><i class="fas fa-lock"></i> Contraseña *</label>
            <input type="password" name="password" required>
            <small>Mínimo 4 caracteres</small>
        </div>
        <div class="form-group">
            <label><i class="fas fa-user"></i> Nombre completo</label>
            <input type="text" name="nombre">
        </div>
        <div class="form-group">
            <label><i class="fas fa-tag"></i> Rol</label>
            <select name="rol">
                <option value="lector"><i class="fas fa-eye"></i> Lector</option>
                <option value="admin"><i class="fas fa-crown"></i> Administrador</option>
            </select>
        </div>
        <div class="form-group checkbox-group">
            <input type="checkbox" name="activo" value="1" checked>
            <label><i class="fas fa-check-circle"></i> Usuario activo</label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> Guardar usuario</button>
            <a href="usuarios.php" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
        </div>
    </form>
</div>

<style>
.form-container { max-width: 600px; margin: 0 auto; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }
.form-group input, .form-group select { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; }
.form-group input:focus, .form-group select:focus { outline: none; border-color: #667eea; }
.btn-guardar { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; font-weight: 600; margin-right: 10px; }
.btn-cancelar { background: #a0aec0; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; display: inline-block; }
.checkbox-group { display: flex; align-items: center; gap: 10px; }
.checkbox-group input { width: auto; }
</style>

<?php require_once 'footer.php'; ?>