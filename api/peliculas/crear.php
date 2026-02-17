<?php
include("../config/conexion.php");
include("../helpers/response.php");

$nombre = $_POST['nombre'];
$genero = $_POST['genero'];
$descripcion = $_POST['descripcion'];
$link = $_POST['link'];

$imagen = $_FILES['imagen']['name'];
$ruta = "../uploads/".$imagen;

move_uploaded_file($_FILES['imagen']['tmp_name'],$ruta);

$sql = $conn->prepare("INSERT INTO peliculas(nombre,genero,imagen,descripcion,link_trailer)
VALUES(?,?,?,?,?)");

$sql->bind_param("sssss",$nombre,$genero,$imagen,$descripcion,$link);

if($sql->execute()){
    response("success","Película registrada");
}

response("error","No se pudo registrar");
?>
