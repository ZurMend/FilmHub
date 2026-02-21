<?php
include 'includes/header.php';
include 'includes/sidebar.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$token = $_SESSION['token'];

$url = "http://localhost:3000/api/usuarios";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$usuarios = json_decode($response, true);
?>

<div class="content">
    <h2>Lista de Usuarios</h2>

    <a href="registrar_usuario.php" class="btn-success">
        Registrar Usuario
    </a>

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

        <?php if(is_array($usuarios)): ?>
            <?php foreach($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['nombre']; ?></td>
                    <td><?php echo $u['apellido_paterno']; ?></td>
                    <td><?php echo $u['apellido_materno']; ?></td>
                    <td><?php echo $u['correo']; ?></td>

                    <!-- Estado -->
                    <td>
                        <?php if($u['estado'] == 'activo'): ?>
                            <span class="badge-activa">Activo</span>
                        <?php else: ?>
                            <span class="badge-inactiva">Inactivo</span>
                        <?php endif; ?>
                    </td>

                    <!-- Acciones -->
                    <td>
                        <?php if($u['estado'] == 'activo'): ?>
                            <a href="cambiar_estado_usuario.php?id=<?php echo $u['id']; ?>&estado=inactivo"
                               class="btn-danger"
                               onclick="return confirm('¿Seguro que deseas desactivar este usuario?')">
                               Desactivar
                            </a>
                        <?php else: ?>
                            <a href="cambiar_estado_usuario.php?id=<?php echo $u['id']; ?>&estado=activo"
                               class="btn-success">
                               Activar
                            </a>
                        <?php endif; ?>

                        <a href="editar_usuario.php?id=<?php echo $u['id']; ?>" 
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