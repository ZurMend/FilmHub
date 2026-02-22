<?php
require_once __DIR__ . '/helpers/MailHelper.php';

$result = MailHelper::enviarCorreo(
    'ironman.imlo28@gmail.com',
    'Prueba FilmHub',
    '<h2>Correo de prueba funcionando 🚀</h2>'
);

echo $result ? "Correo enviado" : "Error al enviar";