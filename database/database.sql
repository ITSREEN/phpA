DROP DATABASE IF EXISTS PETS_HEAVEN;
CREATE DATABASE IF NOT EXISTS PETS_HEAVEN;
CREATE TABLE PETS_HEAVEN.propietarios(
    id_pro INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL, 
    correo VARCHAR(100) NOT NULL,
    contrasena VARCHAR(100) NOT NULL, 
    telefono VARCHAR(20) NOT NULL, 
    foto TEXT NOT NULL
);
INSERT INTO PETS_HEAVEN.propietarios 
(nombre, correo, contrasena, telefono, foto) 
VALUES
('María González', 'maria.gonzalez@example.com', 'password123', '3001234567', '/uploads/propietarios/maria.jpg'),
('Carlos Rodríguez', 'carlos.rodriguez@example.com', 'securepass', '3102345678', '/uploads/propietarios/carlos.jpg'),
('Ana Martínez', 'ana.martinez@example.com', 'mypassword', '3203456789', '/uploads/propietarios/ana.jpg'),
('Luis Fernández', 'luis.fernandez@example.com', 'luispass', '3154567890', '/uploads/propietarios/luis.jpg'),
('Sofía López', 'sofia.lopez@example.com', 'sofia1234', '3015678901', '/uploads/propietarios/sofia.jpg');