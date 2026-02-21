const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middleware/auth.middleware');
const usuariosController = require('../controllers/usuarios.controller');

router.post('/', verifyToken, usuariosController.registrarUsuario);
router.get('/', verifyToken, usuariosController.obtenerUsuarios);
router.put('/:id/estado', verifyToken, usuariosController.cambiarEstado);
router.get('/:id', verifyToken, usuariosController.obtenerUsuarioPorId);
router.put('/:id', verifyToken, usuariosController.actualizarUsuario);

module.exports = router;