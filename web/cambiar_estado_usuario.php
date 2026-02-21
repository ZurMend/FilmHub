<?php
include 'includes/header.php';

if (!isset($_GET['id']) || !isset($_GET['estado'])) {
    header("Location: listar_usuarios.php");
    exit;
}

$id = $_GET['id'];
$estado = $_GET['estado'];
$token = $_SESSION['token'];

$url = "http://localhost:3000/api/usuarios/$id/estado";

$data = json_encode([
    "estado" => $estado
]);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

header("Location: listar_usuarios.php");
exit;