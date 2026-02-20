<?php
include 'includes/header.php';
include 'includes/sidebar.php';

$token = $_SESSION['token'];

$url = "http://localhost:3000/api/clientes";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$clientes = json_decode($response, true);
?>

<div class="content">
    <h2>Lista de Clientes</h2>

    <a href="registrar_cliente.php" class="btn-success">Registrar Cliente</a>

    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>

        <?php if(is_array($clientes)): ?>
            <?php foreach($clientes as $c): ?>
                <tr>
                    <td><?php echo $c['nombre']; ?></td>
                    <td><?php echo $c['apellido_paterno']; ?></td>
                    <td><?php echo $c['apellido_materno']; ?></td>
                    <td><?php echo $c['correo']; ?></td>
                    <!-- Estado -->
                    <td>
                        <?php if($c['estado'] == 'activo'): ?>
                            <span class="badge-activa">Activo</span>
                        <?php else: ?>
                            <span class="badge-inactiva">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <!-- Acciones -->
                    <td>
                        <?php if($c['estado'] == 'activo'): ?>
                            <a href="cambiar_estado_cliente.php?id=<?php echo $c['id']; ?>&estado=inactivo"
                            class="btn-danger"
                            onclick="return confirm('¿Seguro que deseas desactivar este cliente?')">
                            Desactivar
                            </a>
                        <?php else: ?>
                            <a href="cambiar_estado_cliente.php?id=<?php echo $c['id']; ?>&estado=activo"
                            class="btn-success">
                            Activar
                            </a>
                        <?php endif; ?>

                        <a href="editar_cliente.php?id=<?php echo $c['id']; ?>" 
                        class="btn-edit">
                        Modificar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        </tbody>
    </table>
</div>