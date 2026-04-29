<?php
require_once 'conexion.php';

echo "=== OPTIMIZANDO BASE DE DATOS ===<br><br>";

// Crear tablas que faltan con estructura correcta
$queries = [
    // Tabla usuarios
    "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        telefono VARCHAR(20),
        admin TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    // Tabla apartamentos
    "CREATE TABLE IF NOT EXISTS apartamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        nombre VARCHAR(150) NOT NULL,
        email VARCHAR(150),
        telefono VARCHAR(20),
        barrio VARCHAR(50),
        habitaciones VARCHAR(20),
        precio DECIMAL(10,2),
        descripcion TEXT,
        direccion TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES usuarios(id)
    )",
    
    // Tabla reservas
    "CREATE TABLE IF NOT EXISTS reservas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        apartamento_id INT NOT NULL,
        checkin DATE NOT NULL,
        checkout DATE NOT NULL,
        noches INT NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        estado VARCHAR(20) DEFAULT 'pendiente',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES usuarios(id)
    )",
    
    // Tabla reservas_eliminadas
    "CREATE TABLE IF NOT EXISTS reservas_eliminadas (
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
    )",
    
    // Tabla comentarios
    "CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        apartamento_id INT NOT NULL,
        user_id INT,
        user_name VARCHAR(100),
        rating DECIMAL(2,1),
        comentario TEXT,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Tabla comentarios_eliminados
    "CREATE TABLE IF NOT EXISTS comentarios_eliminados (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comentario_id_original INT NOT NULL,
        apartamento_id INT NOT NULL,
        user_id INT,
        user_name VARCHAR(100),
        rating DECIMAL(2,1),
        comentario TEXT,
        eliminado_por INT,
        fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($queries as $sql) {
    // Extraer nombre de tabla
    preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/', $sql, $match);
    $table = $match[1] ?? 'desconocida';
    
    if ($conn->query($sql)) {
        echo "✓ $table - OK<br>";
    } else {
        echo "✗ $table - Error: " . $conn->error . "<br>";
    }
}

echo "<br>=== VERIFICANDO INTEGRIDAD ===<br><br>";

// Verificar usuarios huérfanos en reservas
$result = $conn->query("SELECT r.id FROM reservas r LEFT JOIN usuarios u ON r.user_id = u.id WHERE u.id IS NULL");
if ($result->num_rows > 0) {
    echo "⚠️ Reservas sin usuario: " . $result->num_rows . "<br>";
} else {
    echo "✓ Reservas con usuarios correctos<br>";
}

// Verificar apartamentos huérfanos
$result = $conn->query("SELECT a.id FROM apartamentos a LEFT JOIN usuarios u ON a.user_id = u.id WHERE u.id IS NULL AND a.user_id IS NOT NULL");
if ($result->num_rows > 0) {
    echo "⚠️ Apartamentos sin usuario: " . $result->num_rows . "<br>";
} else {
    echo "✓ Apartamentos con usuarios correctos<br>";
}

echo "<br>=== BASE DE DATOS OPTIMIZADA ===";
?>