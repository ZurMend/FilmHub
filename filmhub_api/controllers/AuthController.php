<?php
require_once __DIR__ . "/../models/Usuario.php";
require_once __DIR__ . "/../helpers/response.php";

class AuthController {

    public function login() {

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['email']) || !isset($input['password'])) {
            jsonResponse("error", null, "email y password son requeridos");
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->login($input['email'], $input['password']);

        if ($usuario) {
            jsonResponse("success", $usuario, "Login exitoso");
        } else {
            jsonResponse("error", null, "Credenciales incorrectas");
        }
    }
}