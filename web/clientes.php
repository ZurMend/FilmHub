<?php include("menu.php"); ?>
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

<div class="card p-4 shadow mb-4">
<h4>Registrar Cliente</h4>

<input id="nombre" class="form-control mb-2" placeholder="Nombre">
<input id="apellido_paterno" class="form-control mb-2" placeholder="Apellido Paterno">
<input id="apellido_materno" class="form-control mb-2" placeholder="Apellido Materno">
<input id="correo" type="email" class="form-control mb-2" placeholder="Correo Electrónico">

<button onclick="registrarCliente()" class="btn btn-primary">Registrar</button>
</div>

<div class="card p-4 shadow">
<h4>Lista de Clientes</h4>

<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
<th>Nombre Completo</th>
<th>Correo</th>
<th>Fecha Registro</th>
<th>Estado</th>
<th>Acciones</th>
</tr>
</thead>
<tbody id="tablaClientes"></tbody>
</table>

</div>

</div>

<script src="assets/js/clientes.js"></script>
</body>
</html>
