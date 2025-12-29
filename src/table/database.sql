-- Crear la base de datos sino existe

CREATE DATABASE IF NOT EXISTS mi_tienda;
USE mi_tienda;

-- Crear la tabla de productos

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);