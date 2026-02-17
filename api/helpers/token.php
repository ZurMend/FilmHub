<?php
require '../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "CLAVE_SUPER_SECRETA";

function generarToken($data){
    global $secret_key;

    $payload = [
        "iat" => time(),
        "exp" => time() + (60*60),
        "data" => $data
    ];

    return JWT::encode($payload, $secret_key, 'HS256');
}

function validarToken(){
    global $secret_key;

    $headers = apache_request_headers();
    if(!isset($headers['Authorization'])){
        http_response_code(401);
        exit;
    }

    $token = str_replace("Bearer ","",$headers['Authorization']);

    try{
        return JWT::decode($token, new Key($secret_key,'HS256'));
    } catch(Exception $e){
        http_response_code(401);
        exit;
    }
}
?>
