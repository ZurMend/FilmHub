const connection = require('../config/db');

exports.registrarPelicula = (req, res) => {
    const { nombre, genero, descripcion, link_trailer } = req.body;

    if (!nombre || !genero || !descripcion || !link_trailer) {
        return res.status(400).json({ message: "Todos los campos son obligatorios" });
    }

    if (!req.file) {
        return res.status(400).json({ message: "La imagen es obligatoria" });
    }

    const imagen = req.file.filename;

    // Verificar si ya existe la película
    const checkSql = "SELECT id FROM peliculas WHERE nombre = ?";

    connection.query(checkSql, [nombre], (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Error del servidor" });
        }

        if (results.length > 0) {
            return res.status(400).json({ message: "Ya existe una película con ese nombre" });
        }

        const insertSql = `
            INSERT INTO peliculas (nombre, genero, imagen, descripcion, link_trailer, estado)
            VALUES (?, ?, ?, ?, ?, 'activa')
        `;

        connection.query(insertSql, [nombre, genero, imagen, descripcion, link_trailer], (err) => {
            if (err) {
                return res.status(500).json({ message: "Error al registrar la película" });
            }

            res.status(201).json({ message: "Película registrada correctamente 🎬" });
        });
    });
};