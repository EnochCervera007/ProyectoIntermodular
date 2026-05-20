<?php
session_start();
require_once 'conexion.php';

$mensajes = [];

// 1. Añadir columna admin si no existe
$result = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'admin'");
if ($result->num_rows === 0) {
    $conn->query("ALTER TABLE usuarios ADD admin TINYINT(1) DEFAULT 0");
    $mensajes[] = "Columna 'admin' añadida a usuarios";
}

// 2. Crear admin
$admin_email = 'admin@apbarcelona.com';
$admin_pass_hash = '$2y$12$lfWSY/w2/eGlLrr4bmhXhONGWsP2rwB7t8rOLhWzzzo4fCJRwCOC2';

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $admin_email);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, admin, created_at) VALUES (?, ?, ?, 1, NOW())");
    $nombre = 'Admin';
    $stmt->bind_param("sss", $nombre, $admin_email, $admin_pass_hash);
    $stmt->execute();
    $mensajes[] = "Admin creado: admin@apbarcelona.com / admin123";
} else {
    $mensajes[] = "El admin ya existe";
}

// 3. Crear usuario demo para los apartamentos
$demo_email = 'demo@apbarcelona.com';
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $demo_email);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, admin, created_at) VALUES (?, ?, ?, 0, NOW())");
    $nombre = 'Demo Propietario';
    $demo_hash = password_hash('demo123', PASSWORD_DEFAULT);
    $stmt->bind_param("sss", $nombre, $demo_email, $demo_hash);
    $stmt->execute();
    $mensajes[] = "Usuario demo creado: demo@apbarcelona.com / demo123";
}

// 4. Obtener user_id del demo
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $demo_email);
$stmt->execute();
$demo_user = $stmt->get_result()->fetch_assoc();
$demo_id = $demo_user['id'];

// 5. Insertar los 6 apartamentos si no existen
$apartamentos = [
    ['nombre' => 'Piso Luminoso Barceloneta', 'barrio' => 'Barceloneta', 'precio' => 95, 'habitaciones' => 2, 'descripcion' => 'Precioso apartamento situado en el corazón de la Barceloneta, a solo 2 minutos de la playa. Totalmente reformado con cocina equipada, lavadora y WiFi de alta velocidad.', 'telefono' => '+34 600 000 001'],
    ['nombre' => 'Loft Moderno Eixample', 'barrio' => 'Eixample Dret', 'precio' => 120, 'habitaciones' => 1, 'descripcion' => 'Luminoso loft en el elegante barrio del Eixample. Decoración moderna y minimalista. Cerca de la Sagrada Familia y Passeig de Gràcia.', 'telefono' => '+34 600 000 002'],
    ['nombre' => 'Penthouse con Vistas al Mar', 'barrio' => 'Port Olímpic', 'precio' => 280, 'habitaciones' => 3, 'descripcion' => 'Espectacular penthouse con vistas panorámicas al mar. Terraza privada de 30m², jacuzzi y cocina gourmet.', 'telefono' => '+34 600 000 003'],
    ['nombre' => 'Apartamento El Born', 'barrio' => 'El Born', 'precio' => 88, 'habitaciones' => 2, 'descripcion' => 'Encantador apartamento en el barrio más bohemio de Barcelona. Cerca del Parc de la Ciutadella y la Barceloneta.', 'telefono' => '+34 600 000 004'],
    ['nombre' => 'Estudio Vista Mar Poblenou', 'barrio' => 'Poblenou', 'precio' => 74, 'habitaciones' => 1, 'descripcion' => 'Acogedor estudio con vistas al mar. Perfecto para surferos y amantes de la playa. Zona tranquila a 10 minutos del centro.', 'telefono' => '+34 600 000 005'],
    ['nombre' => 'Piso Clásico Gràcia', 'barrio' => 'Gràcia', 'precio' => 105, 'habitaciones' => 2, 'descripcion' => 'Auténtico piso barcelonés en el barrio de Gràcia. Techos altos, molduras originales y ambiente bohemio.', 'telefono' => '+34 600 000 006']
];

foreach ($apartamentos as $apt) {
    $stmt = $conn->prepare("SELECT id FROM apartamentos WHERE nombre = ?");
    $stmt->bind_param("s", $apt['nombre']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $email = 'propietario@apbarcelona.com';
        $stmt = $conn->prepare("INSERT INTO apartamentos (user_id, nombre, email, telefono, barrio, habitaciones, precio, descripcion, direccion, estado, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aprobado', NOW())");
        $direccion = 'Carrer de ' . $apt['barrio'] . ', Barcelona';
        $stmt->bind_param("isssssdss", $demo_id, $apt['nombre'], $email, $apt['telefono'], $apt['barrio'], $apt['habitaciones'], $apt['precio'], $apt['descripcion'], $direccion);
        $stmt->execute();
        $mensajes[] = "Apartamento '{$apt['nombre']}' creado";
    } else {
        $mensajes[] = "Apartamento '{$apt['nombre']}' ya existe";
    }
}

// 6. Crear tablas necesarias si no existen
$tables = [
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
        FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        apartamento_id INT NOT NULL,
        user_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        rating DECIMAL(2,1) NOT NULL,
        comentario TEXT NOT NULL,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (apartamento_id) REFERENCES apartamentos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    "CREATE TABLE IF NOT EXISTS comentarios_eliminados (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comentario_id_original INT NOT NULL,
        apartamento_id INT NOT NULL,
        user_id INT NOT NULL,
        user_name VARCHAR(100),
        rating DECIMAL(2,1),
        comentario TEXT,
        eliminado_por INT,
        fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($tables as $sql) {
    if ($conn->query($sql)) {
        preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/', $sql, $m);
        $mensajes[] = "Tabla '{$m[1]}' lista";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Setup – ApBarcelona</title>
  <style>
    body { font-family: 'DM Sans', sans-serif; background: #0a0a0a; color: #fff; padding: 40px; max-width: 700px; margin: 0 auto; }
    h1 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #C8102E; margin-bottom: 8px; }
    .mensajes { background: #141414; border: 1px solid #222; border-radius: 12px; padding: 24px; margin: 24px 0; }
    .mensaje { padding: 8px 0; border-bottom: 1px solid #222; color: #4e4; }
    .mensaje:last-child { border-bottom: none; }
    .info { background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 16px; margin-top: 16px; }
    .info h3 { color: #c9a55c; margin-bottom: 8px; }
    .info p { color: #888; margin: 4px 0; }
    a { color: #C8102E; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <h1>Setup ApBarcelona</h1>
  <p>Configuración completada</p>

  <div class="mensajes">
    <?php foreach ($mensajes as $m): ?>
      <div class="mensaje">✓ <?php echo htmlspecialchars($m); ?></div>
    <?php endforeach; ?>
  </div>

  <div class="info">
    <h3>Accesos:</h3>
    <p><strong>Admin:</strong> admin@apbarcelona.com / admin123</p>
    <p><strong>Usuario demo:</strong> demo@apbarcelona.com / demo123</p>
  </div>

  <div style="margin-top: 24px; display: flex; gap: 12px;">
    <a href="login.php" style="background: #C8102E; color: #fff; padding: 12px 24px; border-radius: 8px; font-weight: 600;">Ir al login</a>
    <a href="index.php" style="background: #1a1a1a; color: #fff; padding: 12px 24px; border-radius: 8px; border: 1px solid #333;">Ver inicio</a>
  </div>
</body>
</html>
