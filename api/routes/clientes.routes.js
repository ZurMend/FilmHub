const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middleware/auth.middleware');
const clientesController = require('../controllers/clientes.controller');

router.post('/', verifyToken, clientesController.registrarCliente);
router.get('/', verifyToken, clientesController.obtenerClientes);
router.put('/:id/estado', verifyToken, clientesController.cambiarEstado);
router.get('/:id', verifyToken, clientesController.obtenerClientePorId);
router.put('/:id', verifyToken, clientesController.actualizarCliente);

module.exports = router;