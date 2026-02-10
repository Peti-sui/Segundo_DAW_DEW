-- Base de datos principal
CREATE DATABASE tienda_llaveros;
USE tienda_llaveros;

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','user') DEFAULT 'user'
);

-- Tabla de productos disponibles en la tienda
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    precio DECIMAL(6,2),
    tipo ENUM('llaves','bolso','mochila'),
    imagen VARCHAR(255),
    -- RESTRICCION UNICA: No puede haber dos productos con mismo nombre y tipo
    UNIQUE KEY unique_nombre_tipo (nombre, tipo)
);

-- Tabla del carrito de compras (productos seleccionados por usuarios)
CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    cantidad INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- Tabla de pedidos realizados
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'procesado', 'completado', 'cancelado') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de detalle de cada pedido (lineas de productos)
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

-- Indices para mejor rendimiento en tablas de pedidos
CREATE INDEX idx_pedidos_usuario ON pedidos(usuario_id);
CREATE INDEX idx_pedidos_fecha ON pedidos(fecha);
CREATE INDEX idx_detalle_pedido ON detalle_pedido(pedido_id);

-- Tabla de lista de deseos (favoritos) de usuarios
CREATE TABLE IF NOT EXISTS lista_deseos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    producto_id INT NOT NULL,
    fecha_agregado DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
    UNIQUE KEY unique_usuario_producto_deseos (usuario_id, producto_id)
);

-- Indices para mejor rendimiento en lista de deseos
CREATE INDEX idx_lista_deseos_usuario ON lista_deseos(usuario_id);
CREATE INDEX idx_lista_deseos_producto ON lista_deseos(producto_id);


-- Insertar usuario admin si no existe (password:1234)
INSERT INTO usuarios (usuario, password, rol)
SELECT 'admin', 
       '$2y$10$25zQOOAo2UESzPksFA6tsuOIsUJu5yBHckQkIr5BR6Uu4Br3C0i3W',
       'admin'
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE usuario = 'admin'
);

INSERT INTO productos (nombre, precio, tipo, imagen) VALUES
('Llavero Nailong Estandar', 4.99, 'llaves', '1.png'),
('Llavero Nailong Mosca', 6.50, 'llaves', '2.png'),
('Llavero Nailong Estandar', 29.99, 'bolso', '1.png'),
('Llavero Nailong Mosca', 24.50, 'bolso', '2.png'),
('Llavero Nailong Estandar', 49.99, 'mochila', '1.png'),
('Llavero Nailong Mosca', 59.90, 'mochila', '2.png');

-- Para eliminar la base de datos (borra todo)
DROP DATABASE tienda_llaveros;

-- Consultas para verificar datos en cada tabla
SELECT * FROM usuarios;
SELECT * FROM productos;
SELECT * FROM carrito;
SELECT * FROM pedidos;
SELECT * FROM lista_deseos;

-- Consulta para verificar la restricción unica de nombre y tipo en productos
SELECT nombre, tipo, COUNT(*) as cantidad 
FROM productos 
GROUP BY nombre, tipo 
HAVING cantidad > 1;