<?php
include("../config/conexion.php");
include("../helpers/response.php");
include("../helpers/token.php");

$data = json_decode(file_get_contents("php://input"), true);

$correo = $data['correo'] ?? '';
$clave  = $data['clave'] ?? '';

$sql = $conn->prepare("SELECT * FROM usuarios WHERE correo=? AND estado='activo'");
$sql->bind_param("s",$correo);
$sql->execute();
$result = $sql->get_result();

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    
    if(password_verify($clave,$user['clave'])){
        $token = generarToken($user);
        response("success",["token"=>$token]);
    }
}

response("error","Credenciales incorrectas");
?>