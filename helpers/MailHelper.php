<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class MailHelper {

    public static function enviarCorreo($destinatario, $asunto, $mensajeHTML) {

        $mail = new PHPMailer(true);

        try {

            // Configuración SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'luxprower19@gmail.com'; // <-- CAMBIAR
            $mail->Password   = 'brhnwegnttufzoce';     // <-- CAMBIAR
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('luxprower19@gmail.com', 'FilmHub');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asunto;
            $mail->Body    = $mensajeHTML;

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}