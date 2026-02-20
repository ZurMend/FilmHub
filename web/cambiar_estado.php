<?php
session_start();

if(!isset($_SESSION['token'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$estado = $_GET['estado'];
$token = $_SESSION['token'];

$url = "http://localhost:3000/api/peliculas/$id/estado";

$data = [
    "estado" => $estado
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

header("Location: listar_peliculas.php");
exit();