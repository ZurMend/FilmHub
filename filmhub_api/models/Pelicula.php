<?php
require_once __DIR__ . "/../config/database.php";

class Pelicula {

    private $conn;
    private $table = "peliculas"; // ⚠️ confirma que tu tabla se llama así

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function obtenerTodas() {

        $query = "SELECT * FROM {$this->table} WHERE estado = 'activa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}