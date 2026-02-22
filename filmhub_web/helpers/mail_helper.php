<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Envia un correo electronico usando PHPMailer
 *
 * @param string $to      Email destino
 * @param string $subject Asunto
 * @param string $body    Cuerpo HTML del correo
 * @return bool
 */
function sendEmail(string $to, string $subject, string $body): bool
{
    $mail = new PHPMailer(true);

    try {
        // Configuracion del servidor
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';
        $mail->Helo       = 'filmhub.local';

        // Evitar problemas de certificado SSL en XAMPP
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ],
        ];

        // Remitente y destinatario
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Envia la contrasena de restablecimiento al usuario
 */
function sendPasswordResetEmail(string $email, string $newPassword): bool
{
    $subject = 'FilmHub - Restablecimiento de Contrasena';
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; background: #1a1a1a; border-radius: 12px; overflow: hidden;">
        <div style="background: #f97316; padding: 20px; text-align: center;">
            <h1 style="color: #000; margin: 0; font-size: 24px;">FilmHub</h1>
        </div>
        <div style="padding: 30px; color: #fff;">
            <h2 style="color: #f97316; margin-top: 0;">Restablecimiento de Contrasena</h2>
            <p>Se ha restablecido tu contrasena. Tu nueva contrasena es:</p>
            <div style="background: #333; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;">
                <code style="font-size: 20px; color: #f97316; letter-spacing: 2px;">' . htmlspecialchars($newPassword) . '</code>
            </div>
            <p style="color: #999; font-size: 13px;">Te recomendamos cambiar esta contrasena una vez que inicies sesion.</p>
        </div>
        <div style="background: #111; padding: 15px; text-align: center; color: #666; font-size: 12px;">
            &copy; ' . date('Y') . ' FilmHub. Todos los derechos reservados.
        </div>
    </div>';

    return sendEmail($email, $subject, $body);
}

/**
 * Envia la contrasena de bienvenida a un nuevo cliente/usuario
 */
function sendWelcomeEmail(string $email, string $nombre, string $password, string $role = 'cliente'): bool
{
    $roleName = $role === 'admin' ? 'Usuario Administrador' : 'Cliente';
    $subject = "FilmHub - Bienvenido a FilmHub";
    $body = '
    <div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; background: #1a1a1a; border-radius: 12px; overflow: hidden;">
        <div style="background: #f97316; padding: 20px; text-align: center;">
            <h1 style="color: #000; margin: 0; font-size: 24px;">FilmHub</h1>
        </div>
        <div style="padding: 30px; color: #fff;">
            <h2 style="color: #f97316; margin-top: 0;">Bienvenido, ' . htmlspecialchars($nombre) . '</h2>
            <p>Tu cuenta de <strong>' . $roleName . '</strong> ha sido creada exitosamente.</p>
            <p>Tus credenciales de acceso son:</p>
            <div style="background: #333; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 5px 0;"><strong style="color: #f97316;">Correo:</strong> ' . htmlspecialchars($email) . '</p>
                <p style="margin: 5px 0;"><strong style="color: #f97316;">Contrasena:</strong> <code style="font-size: 16px; color: #f97316;">' . htmlspecialchars($password) . '</code></p>
            </div>
            <p style="color: #999; font-size: 13px;">Guarda esta informacion en un lugar seguro.</p>
        </div>
        <div style="background: #111; padding: 15px; text-align: center; color: #666; font-size: 12px;">
            &copy; ' . date('Y') . ' FilmHub. Todos los derechos reservados.
        </div>
    </div>';

    return sendEmail($email, $subject, $body);
}
