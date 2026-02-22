require('dotenv').config();
const express = require('express');
const cors = require('cors');
require('./config/db');

const app = express();

app.use(cors());
app.use(express.json());

const authRoutes = require('./routes/auth.routes');
app.use('/api/auth', authRoutes);

const peliculasRoutes = require('./routes/peliculas.routes');
app.use('/api/peliculas', peliculasRoutes);

app.get('/', (req, res) => {
    res.send('API funcionando 🔥');
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log('Servidor corriendo en http://localhost:' + PORT);
});
