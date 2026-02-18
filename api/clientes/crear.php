<?php
include("../config/conexion.php");
include("../helpers/response.php");

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$nombre = $_POST['nombre'];
$ap = $_POST['apellido_paterno'];
$am = $_POST['apellido_materno'];
$correo = $_POST['correo'];

$clave = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"),0,8);
$hash = password_hash($clave,PASSWORD_DEFAULT);

$sql = $conn->prepare("INSERT INTO clientes(nombre,apellido_paterno,apellido_materno,correo,clave)
VALUES(?,?,?,?,?)");

$sql->bind_param("sssss",$nombre,$ap,$am,$correo,$hash);

if($sql->execute()){

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host='smtp.gmail.com';
    $mail->SMTPAuth=true;
    $mail->Username='TU_CORREO@gmail.com';
    $mail->Password='APP_PASSWORD';
    $mail->SMTPSecure='tls';
    $mail->Port=587;

    $mail->setFrom('TU_CORREO@gmail.com','Streaming');
    $mail->addAddress($correo);
    $mail->Subject='Cuenta creada';
    $mail->Body="Tu contraseña es: $clave";

    $mail->send();

    response("success","Cliente registrado");
}

response("error","Error");
?>
