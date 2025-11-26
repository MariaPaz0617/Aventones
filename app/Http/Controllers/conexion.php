<?php
$host = "localhost";
$user = "root"; 
$pass = "";     
$dbname = "rides";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]));
}
?>