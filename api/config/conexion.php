<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "streaming_db";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die(json_encode(["status"=>"error","message"=>"Error BD"]));
}
?>
