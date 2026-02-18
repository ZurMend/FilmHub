<?php
include("../config/conexion.php");
include("../helpers/response.php");

$id = $_GET['id'];

$conn->query("UPDATE peliculas SET estado='activa' WHERE id=$id");

response("success","Activada");
?>
