const connection = require('../config/db');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

/**
 * Login para la APP MÓVIL: valida contra la tabla CLIENTES.
 */
exports.login = (req, res) => {
    const { correo, clave } = req.body;
    console.log('[LOGIN] Intento:', correo ? correo : '(sin correo)');

    if (!correo || !clave) {
        return res.status(400).json({ message: "Correo y contraseña son obligatorios" });
    }

    const sql = "SELECT * FROM clientes WHERE correo = ? AND estado = 'activo'";

    connection.query(sql, [correo], async (err, results) => {
        if (err) {
            console.log('[LOGIN] Error BD:', err.message);
            return res.status(500).json({ message: "Error del servidor" });
        }

        if (results.length === 0) {
            console.log('[LOGIN] No existe cliente activo con ese correo');
            return res.status(401).json({ message: "Credenciales incorrectas" });
        }

        const cliente = results[0];
        let hash = (cliente.clave || '').trim();
        if (hash.length < 50) {
            console.log('[LOGIN] Hash de contraseña inválido o truncado (longitud ' + hash.length + ')');
            return res.status(500).json({ message: "Error en contraseña del usuario. Ejecuta: npm run create-client" });
        }
        if (hash.startsWith('$2y$')) {
            hash = '$2b$' + hash.slice(4);
        }

        let passwordMatch = false;
        try {
            passwordMatch = await bcrypt.compare(clave, hash);
        } catch (e) {
            console.log('[LOGIN] Error bcrypt:', e.message);
            return res.status(500).json({ message: "Error al verificar contraseña. Ejecuta: npm run create-client" });
        }

        if (!passwordMatch) {
            console.log('[LOGIN] Contraseña incorrecta');
            return res.status(401).json({ message: "Credenciales incorrectas" });
        }

        const token = jwt.sign(
            { id: cliente.id, correo: cliente.correo },
            process.env.JWT_SECRET,
            { expiresIn: "24h" }
        );
        console.log('[LOGIN] OK:', cliente.correo);

        res.json({
            message: "Login exitoso",
            token
        });
    });
};
