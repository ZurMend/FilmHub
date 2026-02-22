<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/MailHelper.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Ingresa tu email.";
    } else {

        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $error = "No existe una cuenta con ese email.";
        } else {

            $nuevaPassword = generateRandomPassword(10);
            $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

            $update = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
            $update->execute([
                ':password' => $hash,
                ':id'    => $usuario['id']
            ]);

            $mensaje = '
            <div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; background: #1a1a1a; border-radius: 12px; overflow: hidden;">
                <div style="background: #f97316; padding: 20px; text-align: center;">
                    <h1 style="color: #000; margin: 0;">FilmHub</h1>
                </div>

                <div style="padding: 30px; color: #ffffff;">
                    <h2 style="color: #f97316;">Recuperación de contraseña</h2>
                    <p>Hola <strong>' . htmlspecialchars($usuario['nombre']) . '</strong>,</p>

                    <p>Tu nueva contraseña es:</p>

                    <div style="background: #333; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;">
                        <code style="font-size: 20px; color: #f97316;">' . htmlspecialchars($nuevaPassword) . '</code>
                    </div>

                    <p style="color: #999; font-size: 13px;">
                        Te recomendamos cambiarla después de iniciar sesión.
                    </p>
                </div>

                <div style="background: #111; padding: 15px; text-align: center; color: #666; font-size: 12px;">
                    © ' . date('Y') . ' FilmHub. Todos los derechos reservados.
                </div>
            </div>
            ';

            if (MailHelper::enviarCorreo($email, "Recuperación de contraseña - FilmHub", $mensaje)) {
                $success = "Se ha enviado una nueva contraseña a tu email.";
            } else {
                $error = "Error al enviar el email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña - FilmHub</title>
</head>
<body style="margin:0; background:#111; font-family:Arial, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh;">

    <div style="background:#1a1a1a; padding:40px; border-radius:12px; width:350px; box-shadow:0 0 20px rgba(0,0,0,0.6); text-align:center;">

        <h2 style="color:#f97316; margin-bottom:20px;">Recuperar contraseña</h2>

        <?php if ($error): ?>
            <div style="background:#330000; color:#ff4d4d; padding:10px; border-radius:6px; margin-bottom:15px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background:#003300; color:#4dff4d; padding:10px; border-radius:6px; margin-bottom:15px;">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input 
                type="email" 
                name="email" 
                placeholder="Ingresa tu email"
                required
                style="width:100%; padding:12px; margin-bottom:15px; border:none; border-radius:6px; background:#333; color:white;"
            >

            <button 
                type="submit"
                style="width:100%; padding:12px; background:#f97316; border:none; border-radius:6px; color:black; font-weight:bold; cursor:pointer;"
            >
                Enviar nueva contraseña
            </button>
        </form>

        <p style="margin-top:15px; font-size:12px; color:#888;">
            © <?= date('Y') ?> FilmHub
        </p>

    </div>

</body>
</html>