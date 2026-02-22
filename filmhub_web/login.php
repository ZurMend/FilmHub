<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';

$error = '';
$success = '';

// Si ya esta logueado, redirigir al dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['token'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Por favor ingresa tu correo y contrasena.';
    } else {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND role = 'admin'");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'activo') {
                $error = 'Tu cuenta esta desactivada. Contacta al administrador.';
            } else {
                // Crear token de sesion
                $token = createAccessToken((int) $user['id']);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['token']   = $token;
                $_SESSION['user_name'] = $user['nombre'];

                header('Location: dashboard.php');
                exit;
            }
        } else {
            $error = 'Correo o contrasena incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmHub - Iniciar Sesion</title>
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
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        .login-card {
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
            letter-spacing: -0.5px;
        }
        .brand-logo p {
            color: #666;
            margin: 4px 0 0;
            font-size: 0.9rem;
        }
        .form-label {
            color: #ccc;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 6px;
        }
        .form-control {
            background: var(--dark-input);
            border: 1px solid var(--border-color);
            color: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            background: var(--dark-input);
            border-color: var(--orange);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }
        .form-control::placeholder {
            color: #555;
        }
        .btn-primary-custom {
            background: var(--orange);
            border: none;
            color: #000;
            font-weight: 700;
            border-radius: 10px;
            padding: 12px;
            font-size: 1rem;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-primary-custom:hover {
            background: var(--orange-hover);
            color: #000;
        }
        .forgot-link {
            color: var(--orange);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .forgot-link:hover {
            color: var(--orange-hover);
            text-decoration: underline;
        }
        .alert-danger-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        .alert-success-custom {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        .input-group-text {
            background: var(--dark-input);
            border: 1px solid var(--border-color);
            border-right: none;
            color: #666;
            border-radius: 10px 0 0 10px;
        }
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-logo">
                <h1><i class="bi bi-film"></i> FilmHub</h1>
                <p>Panel de Administracion</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-danger-custom mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['reset']) && $_GET['reset'] === 'ok'): ?>
                <div class="alert-success-custom mb-3">
                    <i class="bi bi-check-circle me-1"></i> Se ha enviado una nueva contrasena a tu correo.
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label class="form-label">Correo Electronico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="admin@filmhub.com" 
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contrasena</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Tu contrasena" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="reset_password.php" class="forgot-link">Olvidaste tu contrasena?</a>
                </div>

                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesion
                </button>
            </form>
        </div>
        <p class="text-center mt-3" style="color: #444; font-size: 0.8rem;">&copy; <?= date('Y') ?> FilmHub. Solo administradores.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
