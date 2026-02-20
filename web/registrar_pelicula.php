<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="content">

<h2>Registrar Nueva Película</h2>

<form action="procesar_pelicula.php" method="POST" enctype="multipart/form-data">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Género:</label><br>
    <input type="text" name="genero" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="descripcion" required></textarea><br><br>

    <label>Link del Trailer:</label><br>
    <input type="text" name="link_trailer" required><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <button type="submit">Registrar Película</button>

</form>

</div>

</div>
</body>
</html>