<?php
session_start();

if(!isset($_SESSION['token'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FilmHub Admin</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">