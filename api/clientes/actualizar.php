<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$id=$_POST['id'];
$nombre=$_POST['nombre'];
$ap=$_POST['apellido_paterno'];
$am=$_POST['apellido_materno'];
$correo=$_POST['correo'];

$sql=$conn->prepare("UPDATE clientes SET nombre=?,apellido_paterno=?,apellido_materno=?,correo=? WHERE id=?");
$sql->bind_param("ssssi",$nombre,$ap,$am,$correo,$id);

$sql->execute();

response("success","Cliente actualizado");
?>
