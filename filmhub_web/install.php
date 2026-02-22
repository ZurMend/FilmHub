<?php
/**
 * FilmHub - Script de instalacion
 * Ejecuta este archivo UNA VEZ para:
 * 1. Insertar el admin inicial con password hasheado
 * 2. Crear la carpeta uploads
 *
 * Accede a: http://localhost/filmhub/install.php
 * ELIMINA este archivo despues de usarlo.
 */

require_once __DIR__ . '/config/database.php';

$messages = [];

try {
    $db = Database::getConnection();

    // 1. Verificar si ya existe el admin
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => 'admin@filmhub.com']);

    if ($stmt->fetch()) {
        // Actualizar password con hash
        $hashedPassword = password_hash('Admin1234', PASSWORD_BCRYPT);
        $update = $db->prepare("UPDATE users SET password = :pass WHERE email = :email");
        $update->execute([':pass' => $hashedPassword, ':email' => 'admin@filmhub.com']);
        $messages[] = "Admin existente actualizado con password hasheado.";
    } else {
        // Insertar admin nuevo
        $hashedPassword = password_hash('Admin1234', PASSWORD_BCRYPT);
        $insert = $db->prepare("
            INSERT INTO users (nombre, apellido_paterno, apellido_materno, email, password, role, status)
            VALUES (:nombre, :ap, :am, :email, :pass, 'admin', 'activo')
        ");
        $insert->execute([
            ':nombre' => 'Admin',
            ':ap'     => 'FilmHub',
            ':am'     => '',
            ':email'  => 'admin@filmhub.com',
            ':pass'   => $hashedPassword,
        ]);
        $messages[] = "Admin inicial creado exitosamente.";
    }

    // 2. Crear carpeta uploads si no existe
    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
        $messages[] = "Carpeta 'uploads' creada.";
    } else {
        $messages[] = "Carpeta 'uploads' ya existe.";
    }

    $messages[] = "Instalacion completada. Credenciales: admin@filmhub.com / Admin1234";
    $messages[] = "IMPORTANTE: Elimina este archivo (install.php) por seguridad.";

} catch (Exception $e) {
    $messages[] = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FilmHub - Instalacion</title>
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: #fff; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 40px; max-width: 500px; width: 100%; }
        h1 { color: #f97316; margin-top: 0; }
        .msg { background: #222; border-left: 4px solid #f97316; padding: 10px 15px; margin: 10px 0; border-radius: 4px; }
        .msg.error { border-left-color: #ef4444; }
        a { color: #f97316; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h1>FilmHub - Instalacion</h1>
        <?php foreach ($messages as $msg): ?>
            <div class="msg <?= str_starts_with($msg, 'Error') ? 'error' : '' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endforeach; ?>
        <p style="margin-top: 20px;"><a href="login.php">Ir al Login &rarr;</a></p>
    </div>
</body>
</html>
