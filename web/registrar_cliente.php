<?php
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="content">
    <h2>Registrar Cliente</h2>

    <form action="guardar_cliente.php" method="POST">
        
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido_paterno" placeholder="Apellido Paterno" required>
        <input type="text" name="apellido_materno" placeholder="Apellido Materno" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>

        <input type="text" value="Se generará automáticamente" disabled>

        <button type="submit">Registrar Cliente</button>

    </form>
</div>