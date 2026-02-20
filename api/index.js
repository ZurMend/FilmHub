require('dotenv').config();
const express = require('express');
const cors = require('cors');
require('./config/db');

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());
app.use('/uploads', express.static('uploads'));

const authRoutes = require('./routes/auth.routes');
app.use('/api/auth', authRoutes);

const testRoutes = require('./routes/test.routes');
app.use('/api/test', testRoutes);

const peliculasRoutes = require('./routes/peliculas.routes');
app.use('/api/peliculas', peliculasRoutes);

const clientesRoutes = require('./routes/clientes.routes');
app.use('/api/clientes', clientesRoutes);

// Ruta de prueba
app.get('/', (req, res) => {
    res.json({
        mensaje: "API de FilmHub funcionando correctamente 🚀"
    });
});

// Puerto
const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});