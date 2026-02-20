const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middleware/auth.middleware');
const peliculasController = require('../controllers/peliculas.controller');
const upload = require('../config/multer');

router.post(
    '/',
    verifyToken,
    upload.single('imagen'),
    peliculasController.registrarPelicula
);

router.get('/', verifyToken, peliculasController.obtenerPeliculas);

router.put('/:id/estado', verifyToken, peliculasController.cambiarEstado);

router.put('/:id', verifyToken, upload.single('imagen'), peliculasController.actualizarPelicula);

module.exports = router;