CREATE DATABASE IF NOT EXISTS apbarcelona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE apbarcelona;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    admin TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS apartamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    barrio VARCHAR(50) NOT NULL,
    habitaciones VARCHAR(10) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(255),
    imagen VARCHAR(255) DEFAULT NULL,
    estado ENUM('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (nombre, email, password, admin, created_at) VALUES
('Admin', 'admin@apbarcelona.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW())
ON DUPLICATE KEY UPDATE id=id;
