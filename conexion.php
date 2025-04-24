<?php
$host = "localhost";
$usuario = "root";
$contrasena = ""; 
$basededatos = "colegio";

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificamos la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// echo "Conexión exitosa";
?>
