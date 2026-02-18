<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$id=$_GET['id'];
$conn->query("UPDATE peliculas SET estado='inactiva' WHERE id=$id");

response("success","Película inactivada");
?>
