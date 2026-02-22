<?php
require_once __DIR__ . "/helpers/response.php";

$route = $_GET['route'] ?? '';

switch ($route) {

    case 'test':
        jsonResponse("success", ["message" => "API funcionando correctamente"]);
        break;

    default:
        jsonResponse("error", null, "Ruta no encontrada");
        break;
}