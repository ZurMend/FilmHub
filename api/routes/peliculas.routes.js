const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middleware/auth.middleware');
const peliculasController = require('../controllers/peliculas.controller');

router.get('/', verifyToken, peliculasController.obtenerPeliculas);

module.exports = router;
