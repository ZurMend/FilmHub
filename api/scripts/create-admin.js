/**
 * Crea el usuario de prueba para la app móvil.
 * Ejecutar: npm run create-admin   (o: node scripts/create-admin.js)
 *
 * Usuario: admin@gmail.com
 * Contraseña: Password
 */
require('dotenv').config({ path: require('path').join(__dirname, '..', '.env') });
const bcrypt = require('bcrypt');
const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'streaming_db',
    port: process.env.DB_PORT || 3306
});

async function run() {
    const hash = await bcrypt.hash('Password', 10);

    const sql = `
      INSERT INTO usuarios (nombre, apellido_paterno, apellido_materno, correo, clave, estado)
      VALUES ('Admin', 'Sistema', 'FilmHub', 'admin@gmail.com', ?, 'activo')
      ON DUPLICATE KEY UPDATE clave = ?, estado = 'activo'
    `;

    connection.query(sql, [hash, hash], (err) => {
        if (err) {
            console.error('Error:', err.message);
            if (err.code === 'ER_NO_SUCH_TABLE') {
                console.error('\nLa tabla "usuarios" no existe. Crea la BD y tablas con streaming_db.sql en MySQL/phpMyAdmin.');
            }
            connection.end();
            process.exit(1);
        }
        console.log('Usuario de prueba creado/actualizado correctamente.');
        console.log('  Correo:    admin@gmail.com');
        console.log('  Contraseña: Password');
        connection.end();
        process.exit(0);
    });
}

run();
