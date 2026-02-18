<?php
include("../config/conexion.php");
include("../helpers/response.php");

$result=$conn->query("SELECT * FROM peliculas WHERE estado='activa'");

$data=[];
while($row=$result->fetch_assoc()){
    $data[]=$row;
}

response("success",$data);
?>
