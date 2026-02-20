<?php

$token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiY29ycmVvIjoiVGhlbWFzdGVyQEZpbG1IdWIuY29tIiwiaWF0IjoxNzcxNTc0NjA0LCJleHAiOjE3NzE1ODE4MDR9.a-EfS54Rw0CX4LgPhO39VjuuP5KNcI54WFHl2--RMWY";

$url = "http://localhost:3000/api/peliculas";

// Crear CURL
$ch = curl_init();

$data = [
    'nombre' => $_POST['nombre'],
    'genero' => $_POST['genero'],
    'descripcion' => $_POST['descripcion'],
    'link_trailer' => $_POST['link_trailer'],
    'imagen' => new CURLFile($_FILES['imagen']['tmp_name'], $_FILES['imagen']['type'], $_FILES['imagen']['name'])
];

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo "Error: " . curl_error($ch);
} else {
    $resultado = json_decode($response, true);
    echo "<h3>" . $resultado['message'] . "</h3>";
}

curl_close($ch);
?>