CREATE TABLE slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    ruta TEXT,
    activo TINYINT(1) DEFAULT 1
);