<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$id=$_GET['id'];
$conn->query("UPDATE clientes SET estado='activo' WHERE id=$id");

response("success","Cliente activado");
?>
