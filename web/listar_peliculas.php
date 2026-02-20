<?php
include 'includes/header.php';
include 'includes/sidebar.php';

$token = $_SESSION['token'];

$url = "http://localhost:3000/api/peliculas";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
curl_close($ch);

$peliculas = json_decode($response, true);
?>

<div class="content">
    <h2>Lista de Películas</h2>

    <table class="tabla">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Género</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        
        <?php foreach($peliculas as $p): ?>
            <tr>
                <td>
                    <img src="http://localhost:3000/uploads/<?php echo $p['imagen']; ?>" width="80">
                </td>
                <td><?php echo $p['nombre']; ?></td>
                <td><?php echo $p['genero']; ?></td>
                <td><?php echo substr($p['descripcion'], 0, 100); ?>...</td>
                <td>
                    <?php if($p['estado'] == 'activa'): ?>
                        <span class="badge-activa">Activa</span>
                    <?php else: ?>
                        <span class="badge-inactiva">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p['estado'] == 'activa'): ?>
                        <a href="cambiar_estado.php?id=<?php echo $p['id']; ?>&estado=inactiva"
                            class="btn-danger"
                            onclick="return confirm('¿Seguro que deseas desactivar esta película?')">Desactivar</a>
                    <?php else: ?>
                        <a href="cambiar_estado.php?id=<?php echo $p['id']; ?>&estado=activa" class="btn-success">Activar</a>
                    <?php endif; ?>

                    <a href="editar_pelicula.php?id=<?php echo $p['id']; ?>" class="btn-edit">Modificar</a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

</div>
</body>
</html>