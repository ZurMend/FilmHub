const express = require('express');
const router = express.Router();
const { verifyToken } = require('../middleware/auth.middleware');

router.get('/private', verifyToken, (req, res) => {
    res.json({
        message: "Acceso autorizado 🎉",
        usuario: req.usuario
    });
});

module.exports = router;