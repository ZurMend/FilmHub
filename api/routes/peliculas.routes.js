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

module.exports = router;