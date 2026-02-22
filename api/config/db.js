const mysql = require('mysql2');
require('dotenv').config();

const connection = mysql.createConnection({
    host: process.env.DB_HOST || 'localhost',
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: process.env.DB_NAME || 'streaming_db',
    port: process.env.DB_PORT || 3306
});

connection.connect((err) => {
    if (err) {
        console.error("❌ Error conectando a la base de datos:", err.message);
        return;
    }
    console.log("✅ Conectado a la base de datos MySQL");
});

module.exports = connection;
