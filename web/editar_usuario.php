<?php
include 'includes/header.php';
include 'includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: listar_usuarios.php");
    exit;
}

$id = $_GET['id'];
$token = $_SESSION['token'];

/* ===============================
   1️⃣ Si se envió el formulario
================================ */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $data = json_encode([
        "nombre" => $_POST['nombre'],
        "apellido_paterno" => $_POST['apellido_paterno'],
        "apellido_materno" => $_POST['apellido_materno'],
        "correo" => $_POST['correo']
    ]);

    $url = "http://localhost:3000/api/usuarios/$id";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ]);

    curl_exec($ch);
    curl_close($ch);

    header("Location: listar_usuarios.php");
    exit;
}

/* ===============================
   2️⃣ Obtener usuario por ID
================================ */
$url = "http://localhost:3000/api/usuarios/$id";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$usuario = json_decode($response, true);
?>

<div class="content">
    <h2>Editar Usuario</h2>

    <form method="POST">

        <label>Nombre</label>
        <input type="text" name="nombre"
               value="<?php echo $usuario['nombre']; ?>" required>

        <label>Apellido Paterno</label>
        <input type="text" name="apellido_paterno"
               value="<?php echo $usuario['apellido_paterno']; ?>" required>

        <label>Apellido Materno</label>
        <input type="text" name="apellido_materno"
               value="<?php echo $usuario['apellido_materno']; ?>" required>

        <label>Correo</label>
        <input type="email" name="correo"
               value="<?php echo $usuario['correo']; ?>" required>

        <br><br>

        <button type="submit" class="btn-success">
            Actualizar Usuario
        </button>

        <a href="listar_usuarios.php" class="btn-danger">
            Cancelar
        </a>

    </form>
</div>