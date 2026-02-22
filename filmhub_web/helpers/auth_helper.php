<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Funciones auxiliares de autenticacion y tokens
 */

/**
 * Verifica si el usuario esta logueado y es admin.
 * Redirige al login si no lo esta.
 */
function requireAdmin(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['token'])) {
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }

    // Verificar token en BD
    $db = Database::getConnection();
    $stmt = $db->prepare("
        SELECT pat.*, u.role, u.status 
        FROM personal_access_tokens pat
        JOIN users u ON u.id = pat.tokenable_id AND pat.tokenable_type = 'App\\\\Models\\\\User'
        WHERE pat.token = :token 
          AND (pat.expires_at IS NULL OR pat.expires_at > NOW())
    ");
    $stmt->execute([':token' => $_SESSION['token']]);
    $tokenRow = $stmt->fetch();

    if (!$tokenRow || $tokenRow['role'] !== 'admin' || $tokenRow['status'] !== 'activo') {
        session_destroy();
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }

    // Actualizar last_used_at
    $update = $db->prepare("UPDATE personal_access_tokens SET last_used_at = NOW() WHERE token = :token");
    $update->execute([':token' => $_SESSION['token']]);

    // Retornar datos del usuario
    $userStmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $userStmt->execute([':id' => $_SESSION['user_id']]);
    return $userStmt->fetch();
}

/**
 * Genera un token unico de 64 caracteres hex
 */
function generateToken(): string
{
    return bin2hex(random_bytes(32));
}

/**
 * Crea un token de acceso personal para un usuario
 */
function createAccessToken(int $userId, string $name = 'web-session'): string
{
    $token = generateToken();
    $db = Database::getConnection();

    // Eliminar tokens anteriores del mismo usuario
    $del = $db->prepare("DELETE FROM personal_access_tokens WHERE tokenable_type = 'App\\\\Models\\\\User' AND tokenable_id = :uid");
    $del->execute([':uid' => $userId]);

    $stmt = $db->prepare("
        INSERT INTO personal_access_tokens (tokenable_type, tokenable_id, name, token, abilities, expires_at)
        VALUES ('App\\\\Models\\\\User', :uid, :name, :token, '[\"*\"]', DATE_ADD(NOW(), INTERVAL :hours HOUR))
    ");
    $stmt->execute([
        ':uid'   => $userId,
        ':name'  => $name,
        ':token' => $token,
        ':hours' => TOKEN_EXPIRY_HOURS,
    ]);

    return $token;
}

/**
 * Elimina el token de sesion actual
 */
function destroyAccessToken(): void
{
    if (isset($_SESSION['token'])) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM personal_access_tokens WHERE token = :token");
        $stmt->execute([':token' => $_SESSION['token']]);
    }
    session_destroy();
}

/**
 * Genera una contrasena aleatoria de longitud dada
 */
function generateRandomPassword(int $length = 10): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}
