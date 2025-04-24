<?php
include 'conexion.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Primero obtenemos la ruta de la foto para eliminarla del servidor
    $sql_foto = "SELECT foto FROM aprendiz WHERE Id = $id";
    $result = $conn->query($sql_foto);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $foto_path = $row['foto'];
        
        // Eliminar la foto del servidor
        if(file_exists($foto_path)) {
            unlink($foto_path);
        }
    }
    
    // Luego eliminamos el registro de la base de datos
    $sql = "DELETE FROM aprendiz WHERE Id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: consultar.php?mensaje=Registro+eliminado+correctamente");
    } else {
        header("Location: consultar.php?error=Error+al+eliminar+registro");
    }
} else {
    header("Location: consultar.php");
}

$conn->close();
?>