const connection = require('../config/db');


// =============================
// REGISTRAR PELÍCULA
// =============================
exports.registrarPelicula = (req, res) => {
    const { nombre, genero, descripcion, link_trailer } = req.body;

    if (!nombre || !genero || !descripcion || !link_trailer) {
        return res.status(400).json({ message: "Todos los campos son obligatorios" });
    }

    if (!req.file) {
        return res.status(400).json({ message: "La imagen es obligatoria" });
    }

    const imagen = req.file.filename;

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


// =============================
// OBTENER TODAS LAS PELÍCULAS
// =============================
exports.obtenerPeliculas = (req, res) => {

    const sql = "SELECT * FROM peliculas ORDER BY id DESC";

    connection.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Error al obtener películas" });
        }

        res.json(results);
    });
};


// =============================
// CAMBIAR ESTADO
// =============================
exports.cambiarEstado = (req, res) => {

    const { id } = req.params;
    const { estado } = req.body;

    if (!estado) {
        return res.status(400).json({ message: "El estado es obligatorio" });
    }

    const sql = "UPDATE peliculas SET estado = ? WHERE id = ?";

    connection.query(sql, [estado, id], (err) => {
        if (err) {
            return res.status(500).json({ message: "Error al cambiar estado" });
        }

        res.json({ message: "Estado actualizado correctamente" });
    });
};


// =============================
// ACTUALIZAR PELÍCULA
// =============================
exports.actualizarPelicula = (req, res) => {

    const { id } = req.params;
    const { nombre, genero, descripcion, link_trailer } = req.body;

    let sql;
    let values;

    if (req.file) {
        const imagen = req.file.filename;
        sql = `
            UPDATE peliculas 
            SET nombre=?, genero=?, descripcion=?, link_trailer=?, imagen=?
            WHERE id=?
        `;
        values = [nombre, genero, descripcion, link_trailer, imagen, id];
    } else {
        sql = `
            UPDATE peliculas 
            SET nombre=?, genero=?, descripcion=?, link_trailer=?
            WHERE id=?
        `;
        values = [nombre, genero, descripcion, link_trailer, id];
    }

    connection.query(sql, values, (err) => {
        if (err) {
            return res.status(500).json({ message: "Error al actualizar película" });
        }

        res.json({ message: "Película actualizada correctamente" });
    });
};