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

CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    apartamento_id INT NOT NULL,
    checkin DATE NOT NULL,
    checkout DATE NOT NULL,
    noches INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'pendiente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservas_eliminadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    user_id INT NOT NULL,
    apartamento_id INT NOT NULL,
    checkin DATE,
    checkout DATE,
    noches INT,
    total DECIMAL(10,2),
    estado VARCHAR(20),
    eliminado_por INT,
    fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apartamento_id INT NOT NULL,
    user_id INT NOT NULL,
    user_name VARCHAR(100) NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    comentario TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (apartamento_id) REFERENCES apartamentos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comentarios_eliminados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comentario_id_original INT NOT NULL,
    apartamento_id INT NOT NULL,
    user_id INT NOT NULL,
    user_name VARCHAR(100),
    rating DECIMAL(2,1),
    comentario TEXT,
    eliminado_por INT,
    fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO usuarios (nombre, email, password, admin, created_at) VALUES
('Admin', 'admin@apbarcelona.com', '$2y$12$lfWSY/w2/eGlLrr4bmhXhONGWsP2rwB7t8rOLhWzzzo4fCJRwCOC2', 1, NOW()),
('Demo Propietario', 'demo@apbarcelona.com', '$2y$12$lfWSY/w2/eGlLrr4bmhXhONGWsP2rwB7t8rOLhWzzzo4fCJRwCOC2', 0, NOW());

INSERT IGNORE INTO apartamentos (user_id, nombre, email, telefono, barrio, habitaciones, precio, descripcion, direccion, estado, created_at) VALUES
(2, 'Piso Luminoso Barceloneta', 'propietario@apbarcelona.com', '+34 600 000 001', 'Barceloneta', '2', 95, 'Precioso apartamento situado en el corazón de la Barceloneta, a solo 2 minutos de la playa. Totalmente reformado con cocina equipada, lavadora y WiFi de alta velocidad.', 'Carrer de la Barceloneta, Barcelona', 'aprobado', NOW()),
(2, 'Loft Moderno Eixample', 'propietario@apbarcelona.com', '+34 600 000 002', 'Eixample Dret', '1', 120, 'Luminoso loft en el elegante barrio del Eixample. Decoración moderna y minimalista. Cerca de la Sagrada Familia y Passeig de Gràcia.', 'Carrer de l\'Eixample, Barcelona', 'aprobado', NOW()),
(2, 'Penthouse con Vistas al Mar', 'propietario@apbarcelona.com', '+34 600 000 003', 'Port Olímpic', '3', 280, 'Espectacular penthouse con vistas panorámicas al mar. Terraza privada de 30m², jacuzzi y cocina gourmet.', 'Port Olímpic, Barcelona', 'aprobado', NOW()),
(2, 'Apartamento El Born', 'propietario@apbarcelona.com', '+34 600 000 004', 'El Born', '2', 88, 'Encantador apartamento en el barrio más bohemio de Barcelona. Cerca del Parc de la Ciutadella y la Barceloneta.', 'Carrer d\'El Born, Barcelona', 'aprobado', NOW()),
(2, 'Estudio Vista Mar Poblenou', 'propietario@apbarcelona.com', '+34 600 000 005', 'Poblenou', '1', 74, 'Acogedor estudio con vistas al mar. Perfecto para surferos y amantes de la playa. Zona tranquila a 10 minutos del centro.', 'Carrer de Poblenou, Barcelona', 'aprobado', NOW()),
(2, 'Piso Clásico Gràcia', 'propietario@apbarcelona.com', '+34 600 000 006', 'Gràcia', '2', 105, 'Auténtico piso barcelonés en el barrio de Gràcia. Techos altos, molduras originales y ambiente bohemio.', 'Carrer de Gràcia, Barcelona', 'aprobado', NOW());
