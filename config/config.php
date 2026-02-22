<?php
/**
 * FilmHub - Archivo de configuracion principal
 * Modifica estos valores segun tu entorno (XAMPP / Hostinger)
 */

// ============================================
// CONFIGURACION DE BASE DE DATOS
// ============================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'filmhub_api_db');
define('DB_USER', 'root');       // En XAMPP por defecto es 'root'
define('DB_PASS', '');           // En XAMPP por defecto es vacio
define('DB_CHARSET', 'utf8mb4');

// ============================================
// CONFIGURACION DE CORREO (PHPMailer)
// ============================================
define('MAIL_HOST', 'smtp.gmail.com');       // Servidor SMTP
define('MAIL_PORT', 465);                     // Puerto SMTP
define('MAIL_USERNAME', 'luxprower@gmail.com');// Tu correo Gmail
define('MAIL_PASSWORD', 'pctqehuovpbmwvf');   // App Password de Gmail
define('MAIL_FROM', 'luxprower@gmail.com');
define('MAIL_FROM_NAME', 'FilmHub');

// ============================================
// CONFIGURACION GENERAL
// ============================================
define('APP_NAME', 'FilmHub');
define('APP_URL', 'http://localhost/filmhub'); // Cambia esto en produccion
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('TOKEN_EXPIRY_HOURS', 24); // Horas de validez del token de sesion

// ============================================
// ZONA HORARIA
// ============================================
date_default_timezone_set('America/Mexico_City');

// ============================================
// INICIAR SESION
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
