<?php
// Incluir el archivo de conexión al inicio
require_once 'conexion.php';

// Verificar conexión a la base de datos
if(!isset($conn) || !($conn instanceof mysqli)) {
    die("Error de conexión: No se pudo establecer conexión con la base de datos");
}

// Verificar si se recibió el ID del aprendiz
if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: consultar.php?error=ID+no+proporcionado");
    exit();
}

$id = intval($_GET['id']); // Convertir a entero por seguridad

// Consulta preparada para obtener los datos del aprendiz
$sql = "SELECT * FROM propietarios WHERE id_pro = ?";
$stmt = $conn->prepare($sql);

if(!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    header("Location: consultar.php?error=Registro+no+encontrado");
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aprendiz - ID <?php echo $id; ?></title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            background-color: #2c2a4a;
            font-family: Arial, sans-serif;
            color: #f3f0ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        form {
            background-color: #3e3b68;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            width: 350px;
        }
        
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #f3f0ff;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            background-color: #5a5794;
            color: #f3f0ff;
        }
        
        .photo-preview {
            margin: 15px 0;
            text-align: center;
        }
        
        .photo-preview img {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            border: 2px solid #6a5acd;
        }
        
        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
        }
        
        .btn-submit {
            background-color: #6a5acd;
            color: white;
        }
        
        .btn-submit:hover {
            background-color: #7b6ee4;
        }
        
        .btn-cancel {
            background-color: #d33f49;
            color: white;
        }
        
        .btn-cancel:hover {
            background-color: #e04b55;
        }
        
        small {
            display: block;
            margin-top: -10px;
            margin-bottom: 15px;
            font-size: 0.8em;
            color: #b8b5e0;
        }
        
        .no-photo {
            color: #b8b5e0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <form action="actualizar.php" method="POST" enctype="multipart/form-data">
        <h2>Editar Aprendiz</h2>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div>
            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($row['nombre'] ?? ''); ?>" 
                   required maxlength="30">
        </div>
        
        <div>
            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" 
                   value="<?php echo htmlspecialchars($row['correo'] ?? ''); ?>" 
                   required maxlength="50">
        </div>
        
        <div>
            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" 
                   value="<?php echo htmlspecialchars($row['telefono'] ?? ''); ?>" 
                   pattern="[0-9]{7,10}" title="7 a 10 dígitos numéricos">
            <small>Formato: 7 a 10 dígitos</small>
        </div>
        
        <div>
            <label for="nueva_contrasena">Nueva contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" 
                   placeholder="Dejar en blanco para no cambiar">
            <small>Mínimo 8 caracteres</small>
        </div>
        
        <div class="photo-preview">
            <label>Foto actual:</label>
            <?php if(!empty($row['foto'])): ?>
                <img src="<?php echo htmlspecialchars($row['foto']); ?>" 
                     alt="Foto actual del aprendiz">
            <?php else: ?>
                <p class="no-photo">No hay foto registrada</p>
            <?php endif; ?>
        </div>
        
        <div>
            <label for="foto">Cambiar foto:</label>
            <input type="file" id="foto" name="foto" 
                   accept="image/jpeg, image/png, image/gif">
            <small>Formatos: JPG, PNG, GIF (Máx. 2MB)</small>
        </div>
        
        <div class="btn-container">
            <button type="submit" class="btn btn-submit">Guardar cambios</button>
            <a href="consultar.php" class="btn btn-cancel">Cancelar</a>
        </div>
    </form>

    <script>
        // Validación básica del formulario antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const telefono = document.getElementById('telefono').value;
            const nuevaContrasena = document.getElementById('nueva_contrasena').value;
            
            if(telefono && !/^\d{7,10}$/.test(telefono)) {
                alert('El teléfono debe contener entre 7 y 10 dígitos numéricos');
                e.preventDefault();
                return false;
            }
            
            if(nuevaContrasena && nuevaContrasena.length < 8) {
                alert('La nueva contraseña debe tener al menos 8 caracteres');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>

<?php
// Cerrar conexión al final del script
$conn->close();
?>