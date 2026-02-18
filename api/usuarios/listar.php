<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/auth.php");

protegerRuta();

$result=$conn->query("SELECT * FROM usuarios");

$data=[];
while($row=$result->fetch_assoc()){
    $data[]=$row;
}

response("success",$data);
?>
