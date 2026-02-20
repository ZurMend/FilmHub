<?php
session_start();

if(isset($_SESSION['token'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - FilmHub</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>FilmHub Admin</h2>

        <?php
        if(isset($_GET['error'])){
            echo "<div class='alert-error'>Credenciales incorrectas</div>";
        }
        ?>

        <form action="procesar_login.php" method="POST">
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="clave" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</div>

</body>
</html>