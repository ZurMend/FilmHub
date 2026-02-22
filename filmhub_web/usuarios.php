<?php
$currentPage = 'usuarios';
$pageTitle = 'Usuarios Administradores';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/MailHelper.php';

$user = requireAdmin();
$db = Database::getConnection();

$success = '';
$error = '';

// ===== ACCIONES (Activar / Desactivar) =====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    // No permitir desactivarse a si mismo
    if ($id === (int) $user['id'] && $action === 'desactivar') {
        $error = 'No puedes desactivarte a ti mismo.';
    } else {
        if ($action === 'activar') {
            $db->prepare("UPDATE users SET status = 'activo' WHERE id = :id AND role = 'admin'")->execute([':id' => $id]);
            $success = 'Usuario activado correctamente.';
        } elseif ($action === 'desactivar') {
            $db->prepare("UPDATE users SET status = 'inactivo' WHERE id = :id AND role = 'admin'")->execute([':id' => $id]);
            $success = 'Usuario desactivado correctamente.';
        }
    }
}

// ===== REGISTRAR NUEVO USUARIO ADMIN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apPat    = trim($_POST['apellido_paterno'] ?? '');
    $apMat    = trim($_POST['apellido_materno'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if (empty($nombre) || empty($email)) {
        $error = 'El nombre y correo son obligatorios.';
    } else {
        $check = $db->prepare("SELECT id FROM users WHERE email = :email");
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $error = 'Ya existe un usuario con ese correo electronico.';
        } else {
            $generatedPassword = generateRandomPassword(10);
            $hashedPassword = password_hash($generatedPassword, PASSWORD_BCRYPT);

            $stmt = $db->prepare("
                INSERT INTO users (nombre, apellido_paterno, apellido_materno, email, password, role, status)
                VALUES (:nombre, :ap, :am, :email, :pass, 'admin', 'activo')
            ");
            $stmt->execute([
                ':nombre' => $nombre,
                ':ap'     => $apPat,
                ':am'     => $apMat,
                ':email'  => $email,
                ':pass'   => $hashedPassword,
            ]);

            // ==============================
            // MENSAJE DE BIENVENIDA ADMIN
            // ==============================

            $mensaje = '
            <div style="font-family: Arial; max-width:500px; margin:auto; background:#1a1a1a; border-radius:12px; overflow:hidden;">
                <div style="background:#f97316; padding:20px; text-align:center;">
                    <h1 style="margin:0; color:#000;">FilmHub</h1>
                </div>
                <div style="padding:30px; color:#fff;">
                    <h2 style="color:#f97316;">Cuenta de Administrador Creada</h2>
                    <p>Hola <strong>' . htmlspecialchars($nombre) . '</strong>,</p>
                    <p>Tu cuenta de administrador ha sido creada exitosamente.</p>

                    <div style="background:#333; padding:15px; border-radius:8px; margin:20px 0;">
                        <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                        <p><strong>Contraseña:</strong> 
                            <code style="color:#f97316;">' . htmlspecialchars($generatedPassword) . '</code>
                        </p>
                    </div>

                    <p style="color:#999; font-size:13px;">
                        Por seguridad, te recomendamos cambiar tu contraseña después de iniciar sesión.
                    </p>
                </div>
                <div style="background:#111; padding:15px; text-align:center; color:#666; font-size:12px;">
                    © ' . date('Y') . ' FilmHub
                </div>
            </div>
            ';

            $sent = MailHelper::enviarCorreo(
                $email,
                "Cuenta Administrador - FilmHub",
                $mensaje
            );

            if ($sent) {
                $success = 'Usuario administrador registrado. Se envio la contrasena a su correo.';
            } else {
                $success = 'Usuario registrado. No se pudo enviar el correo (verifica config SMTP). Contrasena: ' . $generatedPassword;
            }
        }
    }
}

// ===== MODIFICAR USUARIO =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId  = (int) $_POST['edit_id'];
    $nombre  = trim($_POST['nombre'] ?? '');
    $apPat   = trim($_POST['apellido_paterno'] ?? '');
    $apMat   = trim($_POST['apellido_materno'] ?? '');
    $email   = trim($_POST['email'] ?? '');

    if (empty($nombre) || empty($email)) {
        $error = 'El nombre y correo son obligatorios.';
    } else {
        $check = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
        $check->execute([':email' => $email, ':id' => $editId]);
        if ($check->fetch()) {
            $error = 'Ya existe otro usuario con ese correo.';
        } else {
            $db->prepare("
                UPDATE users SET nombre = :nombre, apellido_paterno = :ap, apellido_materno = :am, email = :email
                WHERE id = :id AND role = 'admin'
            ")->execute([
                ':nombre' => $nombre,
                ':ap'     => $apPat,
                ':am'     => $apMat,
                ':email'  => $email,
                ':id'     => $editId,
            ]);
            $success = 'Usuario actualizado correctamente.';
        }
    }
}

// Obtener todos los admins
$usuarios = $db->query("SELECT * FROM users WHERE role = 'admin' ORDER BY created_at DESC")->fetchAll();

// Usuario a editar
$editUsuario = null;
if (isset($_GET['action']) && $_GET['action'] === 'editar' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND role = 'admin'");
    $stmt->execute([':id' => (int) $_GET['id']]);
    $editUsuario = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h2><i class="bi bi-person-badge me-2" style="color: var(--orange);"></i>Usuarios Administradores</h2>
        <p>Registra y administra usuarios con acceso al panel.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert-success-custom mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-danger-custom mb-4"><i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Formulario de Modificar -->
    <?php if ($editUsuario): ?>
    <div class="card-custom mb-4">
        <h5 style="color: var(--orange); font-weight: 700; margin-bottom: 16px;">
            <i class="bi bi-pencil-square me-1"></i> Modificar Usuario
        </h5>
        <form method="POST" action="usuarios.php">
            <input type="hidden" name="edit_id" value="<?= $editUsuario['id'] ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($editUsuario['nombre']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" value="<?= htmlspecialchars($editUsuario['apellido_paterno'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" value="<?= htmlspecialchars($editUsuario['apellido_materno'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electronico</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editUsuario['email']) ?>" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-orange me-2"><i class="bi bi-check-lg me-1"></i> Guardar Cambios</button>
                    <a href="usuarios.php" class="btn btn-outline-custom">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Formulario de Registro -->
    <div class="card-custom mb-4">
        <h5 style="color: var(--orange); font-weight: 700; margin-bottom: 16px;">
            <i class="bi bi-person-plus me-1"></i> Registrar Nuevo Usuario Admin
        </h5>
        <form method="POST" action="usuarios.php">
            <input type="hidden" name="registrar" value="1">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nombre <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" placeholder="Apellido Materno">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electronico <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="admin@ejemplo.com" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Clave</label>
                    <input type="text" class="form-control" value="Se genera automaticamente" disabled readonly>
                    <small style="color: var(--text-muted);">Se generara y enviara al correo del usuario.</small>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-orange">
                        <i class="bi bi-person-plus me-1"></i> Registrar Usuario
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card-custom" style="overflow-x: auto;">
        <h5 style="color: var(--orange); font-weight: 700; margin-bottom: 16px;">
            <i class="bi bi-table me-1"></i> Lista de Usuarios Administradores
        </h5>
        <?php if (empty($usuarios)): ?>
            <div class="text-center py-4" style="color: var(--text-muted);">
                <i class="bi bi-person-badge" style="font-size: 2.5rem;"></i>
                <p class="mt-2">No hay usuarios registrados.</p>
            </div>
        <?php else: ?>
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Correo</th>
                        <th>Fecha de Registro</th>
                        <th>Estado</th>
                        <th style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usr): ?>
                    <tr>
                        <td style="font-weight: 600;">
                            <?= htmlspecialchars($usr['nombre'] . ' ' . ($usr['apellido_paterno'] ?? '') . ' ' . ($usr['apellido_materno'] ?? '')) ?>
                            <?php if ((int)$usr['id'] === (int)$user['id']): ?>
                                <span style="background: var(--orange-light); color: var(--orange); padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 600; margin-left: 6px;">Tu</span>
                            <?php endif; ?>
                        </td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($usr['email']) ?></td>
                        <td style="color: var(--text-secondary);"><?= date('d/m/Y H:i', strtotime($usr['created_at'])) ?></td>
                        <td>
                            <span class="<?= $usr['status'] === 'activo' ? 'badge-active' : 'badge-inactive' ?>">
                                <?= ucfirst($usr['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($usr['status'] === 'activo'): ?>
                                <a href="usuarios.php?action=desactivar&id=<?= $usr['id'] ?>" 
                                   class="btn btn-outline-custom btn-sm me-1" title="Desactivar"
                                   onclick="return confirm('Desactivar este usuario?')">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            <?php else: ?>
                                <a href="usuarios.php?action=activar&id=<?= $usr['id'] ?>" 
                                   class="btn btn-outline-custom btn-sm me-1" title="Activar"
                                   style="color: #4ade80; border-color: rgba(34,197,94,0.3);">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            <?php endif; ?>
                            <a href="usuarios.php?action=editar&id=<?= $usr['id'] ?>" 
                               class="btn btn-outline-custom btn-sm" title="Modificar"
                               style="color: var(--orange); border-color: rgba(249,115,22,0.3);">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
