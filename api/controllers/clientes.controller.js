const connection = require('../config/db');
const bcrypt = require('bcrypt');
const nodemailer = require('nodemailer');

// 📧 Configuración del transporte de correo
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: process.env.EMAIL_USER,
        pass: process.env.EMAIL_PASS
    }
});

// ===============================
// REGISTRAR CLIENTE
// ===============================
exports.registrarCliente = async (req, res) => {
    const { nombre, apellido_paterno, apellido_materno, correo } = req.body;

    if (!nombre || !apellido_paterno || !apellido_materno || !correo) {
        return res.status(400).json({ message: "Todos los campos son obligatorios" });
    }

    try {
        // 🔐 Generar contraseña aleatoria
        const passwordPlano = generarPassword(10);

        // 🔐 Encriptar contraseña
        const hash = await bcrypt.hash(passwordPlano, 10);

        const sql = `
            INSERT INTO clientes 
            (nombre, apellido_paterno, apellido_materno, correo, clave, estado)
            VALUES (?, ?, ?, ?, ?, 'activo')
        `;

        connection.query(
            sql,
            [nombre, apellido_paterno, apellido_materno, correo, hash],
            async (err) => {

                if (err) {
                    if (err.code === 'ER_DUP_ENTRY') {
                        return res.status(400).json({ message: "El correo ya está registrado" });
                    }
                    return res.status(500).json({ message: "Error al registrar cliente" });
                }

                // 📧 Enviar correo con contraseña
                const mailOptions = {
                    from: process.env.EMAIL_USER,
                    to: correo,
                    subject: 'Bienvenido a FilmHub 🎬',
                    html: `
                        <h2>Hola ${nombre}</h2>
                        <p>Tu cuenta fue creada correctamente.</p>
                        <p><strong>Correo:</strong> ${correo}</p>
                        <p><strong>Contraseña:</strong> ${passwordPlano}</p>
                        <p>Te recomendamos cambiar tu contraseña al iniciar sesión.</p>
                    `
                };

                try {
                    await transporter.sendMail(mailOptions);
                    console.log("Correo enviado correctamente ✅", info.response);
                } catch (error) {
                    console.log("Error enviando correo:", error);
                }

                res.status(201).json({
                    message: "Cliente registrado correctamente ✅",
                    password_generado: passwordPlano
                });
            }
        );

    } catch (error) {
        res.status(500).json({ message: "Error del servidor" });
    }
};

// ===============================
// OBTENER TODOS LOS CLIENTES
// ===============================
exports.obtenerClientes = (req, res) => {
    const sql = `
        SELECT id, nombre, apellido_paterno, apellido_materno, correo, estado 
        FROM clientes 
        ORDER BY id DESC
    `;

    connection.query(sql, (err, results) => {
        if (err) return res.status(500).json({ message: "Error al obtener clientes" });

        res.json(results);
    });
};

// ===============================
// OBTENER CLIENTE POR ID
// ===============================
exports.obtenerClientePorId = (req, res) => {
    const { id } = req.params;

    const sql = `
        SELECT id, nombre, apellido_paterno, apellido_materno, correo, estado 
        FROM clientes 
        WHERE id = ?
    `;

    connection.query(sql, [id], (err, results) => {
        if (err) {
            return res.status(500).json({ message: "Error al obtener cliente" });
        }

        if (results.length === 0) {
            return res.status(404).json({ message: "Cliente no encontrado" });
        }

        res.json(results[0]);
    });
};

// ===============================
// ACTUALIZAR CLIENTE COMPLETO
// ===============================
exports.actualizarCliente = (req, res) => {
    const { id } = req.params;
    const { nombre, apellido_paterno, apellido_materno, correo, estado } = req.body;

    if (!nombre || !apellido_paterno || !apellido_materno || !correo || !estado) {
        return res.status(400).json({ message: "Todos los campos son obligatorios" });
    }

    const sql = `
        UPDATE clientes 
        SET nombre = ?, 
            apellido_paterno = ?, 
            apellido_materno = ?, 
            correo = ?, 
            estado = ?
        WHERE id = ?
    `;

    connection.query(
        sql,
        [nombre, apellido_paterno, apellido_materno, correo, estado, id],
        (err, result) => {

            if (err) {
                if (err.code === 'ER_DUP_ENTRY') {
                    return res.status(400).json({ message: "El correo ya está registrado" });
                }
                return res.status(500).json({ message: "Error al actualizar cliente" });
            }

            if (result.affectedRows === 0) {
                return res.status(404).json({ message: "Cliente no encontrado" });
            }

            res.json({ message: "Cliente actualizado correctamente ✅" });
        }
    );
};

// ===============================
// CAMBIAR SOLO ESTADO
// ===============================
exports.cambiarEstado = (req, res) => {
    const { id } = req.params;
    const { estado } = req.body;

    if (!estado) {
        return res.status(400).json({ message: "El estado es obligatorio" });
    }

    const sql = "UPDATE clientes SET estado = ? WHERE id = ?";

    connection.query(sql, [estado, id], (err, result) => {
        if (err) {
            return res.status(500).json({ message: "Error al actualizar estado" });
        }

        if (result.affectedRows === 0) {
            return res.status(404).json({ message: "Cliente no encontrado" });
        }

        res.json({ message: "Estado actualizado correctamente ✅" });
    });
};

// ===============================
// FUNCIÓN PARA GENERAR PASSWORD
// ===============================
function generarPassword(longitud) {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';

    for (let i = 0; i < longitud; i++) {
        password += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }

    return password;
}