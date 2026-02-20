<?php
include 'includes/header.php';
include 'includes/sidebar.php';

$id = $_GET['id'];
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

$pelicula = null;

foreach($peliculas as $p){
    if($p['id'] == $id){
        $pelicula = $p;
        break;
    }
}
?>

<div class="content">
<h2>Editar Película</h2>

<form action="actualizar_pelicula.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $pelicula['id']; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?php echo $pelicula['nombre']; ?>" required>

    <label>Género:</label>
    <input type="text" name="genero" value="<?php echo $pelicula['genero']; ?>" required>

    <label>Descripción:</label>
    <textarea name="descripcion" required><?php echo $pelicula['descripcion']; ?></textarea>

    <label>Link Trailer:</label>
    <input type="text" name="link_trailer" value="<?php echo $pelicula['link_trailer']; ?>" required>

    <label>Imagen Actual:</label><br>
    <img src="http://localhost:3000/uploads/<?php echo $pelicula['imagen']; ?>" width="120"><br><br>

    <label>Nueva Imagen (opcional):</label>
    <input type="file" name="imagen">

    <button type="submit">Actualizar Película</button>

</form>
</div>

</div>
</body>
</html>