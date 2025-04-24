<?php
include 'conexion.php';

// Verificar que se recibió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar campos requeridos
    if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['contrasena'])) {
        die("Error: Nombre, correo y contraseña son obligatorios");
    }

    // Sanitizar entradas
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $telefono = !empty($_POST['telefono']) ? mysqli_real_escape_string($conn, $_POST['telefono']) : NULL;

    // Manejo de la imagen
    $ruta_foto = NULL;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        // Configuración de la imagen
        $carpeta_destino = 'imagenes/';
        if (!file_exists($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        // Validar tipo de archivo
        $permitidos = ['image/jpeg', 'image/png', 'image/gif'];
        $tipo = $_FILES['foto']['type'];
        if (in_array($tipo, $permitidos)) {
            // Generar nombre único
            $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $nombre_archivo = uniqid() . '.' . $extension;
            $ruta_foto = $carpeta_destino . $nombre_archivo;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_foto)) {
                $ruta_foto = NULL; // Si falla la subida, continuar sin foto
            }
        }
    }

    // Insertar en la base de datos (con consulta preparada)
    $sql = "INSERT INTO propietarios (nombre, correo, contrasena, telefono, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssss", $nombre, $correo, $contrasena, $telefono, $ruta_foto);
        
        if ($stmt->execute()) {
            // Redirección exitosa
            header("Location: consultar.php");
            exit();
        } else {
            // Error en la base de datos
            echo "Error al registrar. Por favor intenta nuevamente.";
        }
    } else {
        // Error en la preparación
        echo "Error en el sistema. Por favor intenta más tarde.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Si alguien intenta acceder directamente al archivo
    header("Location: registro.html");
    exit();
}
?>