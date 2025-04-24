<?php
include 'conexion.php';

// Verificar conexión
if(!isset($conn) || !($conn instanceof mysqli)) {
    die("Error de conexión: No se pudo establecer conexión con la base de datos");
}

$sql = "SELECT * FROM propietarios";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Aprendices</title>
    <style>
        body {
            background-color: #2c2a4a;
            font-family: Arial, sans-serif;
            color: #f3f0ff;
            padding: 20px;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #3e3b68;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }
        
        h2 {
            text-align: center;
            color: #f3f0ff;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #6a5acd;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #5a5794;
            color: #f3f0ff;
        }
        
        tr:hover {
            background-color: #4a476b;
        }
        
        img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
            border: 2px solid #6a5acd;
        }
        
        .actions a {
            color: #6a5acd;
            text-decoration: none;
            margin: 0 5px;
            transition: color 0.3s;
        }
        
        .actions a:hover {
            color: #7b6ee4;
            text-decoration: underline;
        }
        
        .add-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6a5acd;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .add-btn:hover {
            background-color: #7b6ee4;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #b8b5e0;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Listado de Aprendices</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Foto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>".htmlspecialchars($row["id"])."</td>
                            <td>".htmlspecialchars($row["nombre"])."</td>
                            <td>".htmlspecialchars($row["correo"])."</td>
                            <td>".htmlspecialchars($row["telefono"])."</td>
                            <td><img src='".htmlspecialchars($row["foto"])."' alt='Foto del aprendiz'></td>
                            <td class='actions'>
                                <a href='editar.php?id=".$row["id"]."'>Editar</a> | 
                                <a href='eliminar.php?id=".$row["id"]."' onclick='return confirm(\"¿Estás seguro que deseas eliminar este aprendiz?\")'>Eliminar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>No hay aprendices registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <a href="registro.html" class="add-btn">Agregar Nuevo Aprendiz</a>
    </div>
</body>
</html>

<?php 
$conn->close();
?>