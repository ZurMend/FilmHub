<?php
require_once __DIR__ . "/helpers/response.php";
require_once __DIR__ . "/controllers/AuthController.php";

$route = $_GET['route'] ?? '';

switch ($route) {

    case 'test':
        jsonResponse("success", ["message" => "API funcionando correctamente"]);
        break;

    case 'login':
        $auth = new AuthController();
        $auth->login();
        break;

    default:
        jsonResponse("error", null, "Ruta no encontrada");
        break;
}