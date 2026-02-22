const connection = require('../config/db');

exports.obtenerPeliculas = (req, res) => {
    const sql = "SELECT * FROM peliculas ORDER BY id DESC";

    connection.query(sql, (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Error al obtener películas" });
        }
        res.json(results);
    });
};
