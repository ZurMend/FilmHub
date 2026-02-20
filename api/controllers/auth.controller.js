const connection = require('../config/db');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

exports.login = (req, res) => {
    const { correo, clave } = req.body;

    if (!correo || !clave) {
        return res.status(400).json({ message: "Correo y contraseña son obligatorios" });
    }

    const sql = "SELECT * FROM usuarios WHERE correo = ? AND estado = 'activo'";

    connection.query(sql, [correo], async (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Error del servidor" });
        }

        if (results.length === 0) {
            return res.status(401).json({ message: "Credenciales incorrectas" });
        }

        const usuario = results[0];

        const passwordMatch = await bcrypt.compare(clave, usuario.clave);

        if (!passwordMatch) {
            return res.status(401).json({ message: "Credenciales incorrectas" });
        }

        const token = jwt.sign(
            { id: usuario.id, correo: usuario.correo },
            process.env.JWT_SECRET,
            { expiresIn: "24h" }
        );

        res.json({
            message: "Login exitoso",
            token
        });
    });
};