<?php
include 'includes/header.php';

$token = $_SESSION['token'];

$nombre = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$correo = $_POST['correo'];

$url = "http://localhost:3000/api/clientes";

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
curl_close($ch);

$resultado = json_decode($response, true);

if(isset($resultado['password_generado'])) {

    $passwordGenerado = $resultado['password_generado'];

    // ---- ENVIAR CORREO ----
    $asunto = "Bienvenido a FilmHub 🎬";
    $mensaje = "
    Hola $nombre,

    Tu cuenta ha sido creada correctamente.

    Correo: $correo
    Contraseña: $passwordGenerado

    Te recomendamos cambiarla al iniciar sesión.
    ";

    $headers = "From: no-reply@filmhub.com";

    mail($correo, $asunto, $mensaje, $headers);

    echo "<script>
        alert('Cliente registrado correctamente. Contraseña generada: $passwordGenerado');
        window.location.href='listar_clientes.php';
    </script>";

} else {
    echo "<script>
        alert('Error al registrar cliente');
        window.history.back();
    </script>";
}
?>