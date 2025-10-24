<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "quiz";

$conn = new mysqli($host, $user, $password, $db);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>