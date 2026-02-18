<!DOCTYPE html>
<html>
<head>
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow">
<h3 class="text-center">Inicio de sesión</h3>

<form action="dashboard.php" method="POST">
<input class="form-control mb-3" type="email" name="correo" placeholder="Correo" required>
<input class="form-control mb-3" type="password" name="clave" placeholder="Contraseña" required>
<button class="btn btn-primary w-100">Ingresar</button>
</form>

</div>
</div>

</body>
</html>
