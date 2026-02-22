<?php
require_once __DIR__ . "/../models/Pelicula.php";
require_once __DIR__ . "/../helpers/response.php";

class PeliculaController {

    public function listar() {

        $peliculaModel = new Pelicula();
        $peliculas = $peliculaModel->obtenerTodas();

        if ($peliculas) {
            jsonResponse("success", $peliculas, "Lista de películas");
        } else {
            jsonResponse("error", [], "No hay películas registradas");
        }
    }
    public function cambiarEstado() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id']) || !isset($data['estado'])) {
            echo json_encode([
                "status" => "error",
                "message" => "ID y estado son requeridos"
            ]);
            return;
        }

        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $db = $database->connect();

        $stmt = $db->prepare("UPDATE peliculas SET estado = :estado WHERE id = :id");
        $stmt->execute([
            ':estado' => $data['estado'],
            ':id' => $data['id']
        ]);

        echo json_encode([
            "status" => "success",
            "message" => "Estado actualizado correctamente"
        ]);
    }
        public function listarTodas() {

        require_once __DIR__ . '/../config/database.php';

        $database = new Database();
        $db = $database->connect();

        $stmt = $db->query("SELECT * FROM peliculas ORDER BY id DESC");
        $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($peliculas) {
            echo json_encode([
                "status" => "success",
                "data" => $peliculas,
                "message" => null
            ]);
        } else {
            echo json_encode([
                "status" => "success",
                "data" => [],
                "message" => "No hay películas registradas"
            ]);
        }
    }
}