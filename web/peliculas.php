<?php include("menu.php"); ?>

<div class="container mt-4">

<div class="card p-4 shadow mb-4">
<h4>Registrar Película</h4>

<input id="nombre" class="form-control mb-2" placeholder="Nombre">
<input id="genero" class="form-control mb-2" placeholder="Género">
<input id="descripcion" class="form-control mb-2" placeholder="Descripción">
<input id="link" class="form-control mb-2" placeholder="Link Trailer">
<input id="imagen" type="file" class="form-control mb-2">

<button onclick="registrarPelicula()" class="btn btn-primary">Registrar</button>
</div>

<div class="card p-4 shadow">
<h4>Lista Películas</h4>
<table class="table table-bordered">
<thead>
<tr>
<th>Imagen</th>
<th>Nombre</th>
<th>Género</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody id="tablaPeliculas"></tbody>
</table>
</div>

</div>

<script src="assets/js/peliculas.js"></script>
