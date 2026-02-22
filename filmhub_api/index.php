<?php
require_once __DIR__ . "/helpers/response.php";
require_once __DIR__ . "/controllers/AuthController.php";
require_once __DIR__ . "/controllers/PeliculaController.php";

$route = $_GET['route'] ?? '';

switch ($route) {

    case 'test':
        jsonResponse("success", ["message" => "API funcionando correctamente"]);
        break;

    case 'login':
        $auth = new AuthController();
        $auth->login();
        break;
    case 'peliculas':
        $pelicula = new PeliculaController();
        $pelicula->listar();
        break;
    case 'peliculas/estado':
        require_once 'controllers/PeliculaController.php';
        $controller = new PeliculaController();
        $controller->cambiarEstado();
        break;
    case 'peliculas/admin':
        require_once 'controllers/PeliculaController.php';
        $controller = new PeliculaController();
        $controller->listarTodas();
        break;
    case 'peliculas/editar':
        require_once 'controllers/PeliculaController.php';
        $controller = new PeliculaController();
        $controller->editar();
        break;

    default:
        jsonResponse("error", null, "Ruta no encontrada");
        break;
}