<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$id=$_GET['id'];
$conn->query("DELETE FROM usuarios WHERE id=$id");

response("success","Usuario eliminado");
?>
