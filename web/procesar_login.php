<?php
session_start();

$url = "http://localhost:3000/api/auth/login";

$data = [
    "correo" => $_POST['correo'],
    "clave" => $_POST['clave']
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo "Error: " . curl_error($ch);
    exit();
}

curl_close($ch);

$resultado = json_decode($response, true);

if(isset($resultado['token'])){
    $_SESSION['token'] = $resultado['token'];
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php?error=1");
    exit();
}