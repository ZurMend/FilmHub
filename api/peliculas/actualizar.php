<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$id=$_POST['id'];
$nombre=$_POST['nombre'];
$genero=$_POST['genero'];
$descripcion=$_POST['descripcion'];
$link=$_POST['link'];

$sql=$conn->prepare("UPDATE peliculas SET nombre=?,genero=?,descripcion=?,link_trailer=? WHERE id=?");
$sql->bind_param("ssssi",$nombre,$genero,$descripcion,$link,$id);
$sql->execute();

response("success","Película actualizada");
?>
