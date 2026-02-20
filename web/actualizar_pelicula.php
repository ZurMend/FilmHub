<?php
session_start();

$id = $_POST['id'];
$token = $_SESSION['token'];

$url = "http://localhost:3000/api/peliculas/$id";

$data = [
    'nombre' => $_POST['nombre'],
    'genero' => $_POST['genero'],
    'descripcion' => $_POST['descripcion'],
    'link_trailer' => $_POST['link_trailer']
];

if(!empty($_FILES['imagen']['tmp_name'])){
    $data['imagen'] = new CURLFile(
        $_FILES['imagen']['tmp_name'],
        $_FILES['imagen']['type'],
        $_FILES['imagen']['name']
    );
}

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
curl_close($ch);

header("Location: listar_peliculas.php");
exit();