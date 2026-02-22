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

        echo json_encode([
            "status" => "success",
            "data" => $peliculas ?: [],
            "message" => $peliculas ? null : "No hay películas registradas"
        ]);
    }

    public function editar() {

        require_once __DIR__ . '/../config/database.php';

        $database = new Database();
        $db = $database->connect();

        $id          = $_POST['id'] ?? null;
        $nombre      = $_POST['nombre'] ?? '';
        $genero      = $_POST['genero'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $trailer     = $_POST['link_trailer'] ?? null;

        if (!$id || empty($nombre) || empty($genero) || empty($descripcion)) {
            echo json_encode([
                "status" => "error",
                "message" => "Datos incompletos"
            ]);
            return;
        }

        // Campos base
        $updateFields = "nombre = :nombre,
                         genero = :genero,
                         descripcion = :descripcion,
                         link_trailer = :trailer";

        $params = [
            ':id' => $id,
            ':nombre' => $nombre,
            ':genero' => $genero,
            ':descripcion' => $descripcion,
            ':trailer' => $trailer
        ];

        // Si viene nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreImagen = uniqid('pelicula_') . '.' . $ext;

            $uploadDir = dirname(__DIR__) . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $nombreImagen)) {
                $updateFields .= ", imagen = :imagen";
                $params[':imagen'] = $nombreImagen;
            }
        }

        $sql = "UPDATE peliculas SET $updateFields WHERE id = :id";

        $stmt = $db->prepare($sql);

        if ($stmt->execute($params)) {
            echo json_encode([
                "status" => "success",
                "message" => "Película actualizada correctamente"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar película"
            ]);
        }
    }
}