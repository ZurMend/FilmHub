<?php
include("../config/conexion.php");
include("../helpers/response.php");

$result = $conn->query("SELECT * FROM peliculas");

$peliculas = [];

while($row = $result->fetch_assoc()){
    $peliculas[] = $row;
}

response("success",$peliculas);
?>
