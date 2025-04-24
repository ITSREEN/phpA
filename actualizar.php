<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    
    // Obtener la foto actual con manejo de errores
    $sql_foto = "SELECT foto FROM aprendiz WHERE Id = ?";
    $stmt_foto = $conn->prepare($sql_foto);
    $stmt_foto->bind_param("i", $id);
    $stmt_foto->execute();
    $result = $stmt_foto->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ruta_foto = $row['foto'];
        
        // Manejo de la nueva imagen si se subió
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2MB
            
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $_FILES['foto']['tmp_name']);
            
            if (in_array($mime_type, $allowed_types) && $_FILES['foto']['size'] <= $max_size) {
                // Eliminar la foto anterior si existe
                if(file_exists($ruta_foto)) {
                    unlink($ruta_foto);
                }
                
                // Crear nombre único para la nueva imagen
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto_nombre = uniqid().'.'.$ext;
                $carpeta_destino = 'imagenes/';
                
                if (!is_dir($carpeta_destino)) {
                    mkdir($carpeta_destino, 0755, true);
                }
                
                $ruta_foto = $carpeta_destino.$foto_nombre;
                move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_foto);
            }
        }
        
        // Actualizar los datos en la base de datos
        $stmt = $conn->prepare("UPDATE aprendiz SET nombre=?, correo=?, telefono=?, foto=? WHERE Id=?");
        $stmt->bind_param("ssssi", $nombre, $correo, $telefono, $ruta_foto, $id);
        
        if ($stmt->execute()) {
            header("Location: consultar.php?mensaje=Registro+actualizado+correctamente");
            exit();
        } else {
            header("Location: consultar.php?error=Error+al+actualizar+registro: ".$conn->error);
            exit();
        }
    } else {
        header("Location: consultar.php?error=Registro+no+encontrado");
        exit();
    }
} else {
    header("Location: consultar.php");
    exit();
}

$conn->close();
?>