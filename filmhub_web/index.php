<?php
// Redirigir al login o al dashboard
require_once __DIR__ . '/config/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
