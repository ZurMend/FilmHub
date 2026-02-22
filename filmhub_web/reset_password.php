<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/helpers/mail_helper.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Por favor ingresa tu correo electronico.';
    } else {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND role = 'admin'");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generar nueva contrasena aleatoria
            $newPassword = generateRandomPassword(10);
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            // Actualizar en BD
            $update = $db->prepare("UPDATE users SET password = :pass, updated_at = NOW() WHERE id = :id");
            $update->execute([':pass' => $hashedPassword, ':id' => $user['id']]);

            // Guardar token de reset
            $resetToken = generateToken();
            $db->prepare("DELETE FROM password_reset_tokens WHERE email = :email")->execute([':email' => $email]);
            $db->prepare("INSERT INTO password_reset_tokens (email, token) VALUES (:email, :token)")
               ->execute([':email' => $email, ':token' => $resetToken]);

            // Enviar correo
            $sent = sendPasswordResetEmail($email, $newPassword);

            if ($sent) {
                header('Location: login.php?reset=ok');
                exit;
            } else {
                $error = 'No se pudo enviar el correo. Verifica la configuracion SMTP en config.php.';
            }
        } else {
            // No revelamos si el correo existe o no (seguridad)
            header('Location: login.php?reset=ok');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmHub - Restablecer Contrasena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --orange: #f97316;
            --orange-hover: #ea580c;
            --dark: #0a0a0a;
            --dark-card: #141414;
            --dark-input: #1e1e1e;
            --border-color: #2a2a2a;
        }
        body {
            background-color: var(--dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .reset-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        .reset-card {
            background: var(--dark-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 32px;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-logo h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--orange);
            margin: 0;
        }
        .brand-logo p {
            color: #666;
            margin: 8px 0 0;
            font-size: 0.9rem;
        }
        .form-label {
            color: #ccc;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .form-control {
            background: var(--dark-input);
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .form-control:focus {
            background: var(--dark-input);
            border-color: var(--orange);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }
        .form-control::placeholder { color: #555; }
        .btn-primary-custom {
            background: var(--orange);
            border: none;
            color: #000;
            font-weight: 700;
            border-radius: 10px;
            padding: 12px;
            font-size: 1rem;
            width: 100%;
        }
        .btn-primary-custom:hover { background: var(--orange-hover); color: #000; }
        .btn-back {
            background: transparent;
            border: 1px solid var(--border-color);
            color: #999;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
        }
        .btn-back:hover { border-color: #555; color: #fff; }
        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: 12px 16px;
        }
        .input-group-text {
            background: var(--dark-input);
            border: 1px solid var(--border-color);
            border-right: none;
            color: #666;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control { border-left: none; border-radius: 0 10px 10px 0; }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="brand-logo">
                <h1><i class="bi bi-film"></i> FilmHub</h1>
                <p>Restablecer Contrasena</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-danger-custom mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <p style="color: #888; font-size: 0.9rem; margin-bottom: 20px;">
                Ingresa tu correo electronico y te enviaremos una nueva contrasena.
            </p>

            <form method="POST" action="reset_password.php">
                <div class="mb-4">
                    <label class="form-label">Correo Electronico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="admin@filmhub.com" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom mb-3">
                    <i class="bi bi-send me-1"></i> Enviar Nueva Contrasena
                </button>

                <a href="login.php" class="btn btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Login
                </a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
