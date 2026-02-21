<?php
session_start();

$token = $_SESSION['token'];

$nombre = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$correo = $_POST['correo'];

$url = "http://localhost:3000/api/usuarios";

$data = [
    "nombre" => $nombre,
    "apellido_paterno" => $apellido_paterno,
    "apellido_materno" => $apellido_materno,
    "correo" => $correo
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$resultado = json_decode($response, true);

if ($http_code == 201) {

    echo "<script>
        alert('Usuario registrado correctamente');
        window.location.href='listar_usuarios.php';
    </script>";

} else {

    $mensaje = $resultado['message'] ?? "Error al registrar usuario";

    echo "<script>
        alert('$mensaje');
        window.history.back();
    </script>";
}
?>