<?php
include 'includes/header.php';
include 'includes/sidebar.php';

$token = $_SESSION['token'];
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID no válido";
    exit;
}

$url = "http://localhost:3000/api/clientes/$id";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$cliente = json_decode($response, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        "nombre" => $_POST['nombre'],
        "apellido_paterno" => $_POST['apellido_paterno'],
        "apellido_materno" => $_POST['apellido_materno'],
        "correo" => $_POST['correo'],
        "estado" => $_POST['estado']
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo "<script>
        alert('Cliente actualizado correctamente');
        window.location.href='listar_clientes.php';
    </script>";
}
?>

<div class="content">
    <h2>Editar Cliente</h2>

    <form method="POST">

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>

        <label>Apellido Paterno</label>
        <input type="text" name="apellido_paterno" value="<?php echo $cliente['apellido_paterno']; ?>" required>

        <label>Apellido Materno</label>
        <input type="text" name="apellido_materno" value="<?php echo $cliente['apellido_materno']; ?>" required>

        <label>Correo</label>
        <input type="email" name="correo" value="<?php echo $cliente['correo']; ?>" required>

        <label>Estado</label>
        <input type="text" name="estado" value="<?php echo $cliente['estado']; ?>" required>

        <button type="submit">Actualizar</button>

    </form>
</div>