<?php
include 'includes/header.php';

$id = $_GET['id'];
$estado = $_GET['estado'];

$token = $_SESSION['token'];

$url = "http://localhost:3000/api/clientes/$id/estado";

$data = [
    "estado" => $estado
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

header("Location: listar_clientes.php");
exit;