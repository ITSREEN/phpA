<?php
$host = "127.0.77";
$usuario = "root";
$contrasena = ""; 
$basededatos = "pets";

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificamos la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// echo "Conexión exitosa";
?>
