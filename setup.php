<?php
require_once 'conexion.php';
$mensajes = [];
$error = false;

try {
    // 1. Admin column
    $r = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'admin'");
    if ($r->num_rows === 0) {
        $conn->query("ALTER TABLE usuarios ADD admin TINYINT(1) DEFAULT 0");
        $mensajes[] = "Columna admin añadida";
    }

    // 2. Crear admin con email diferente
    $email = 'enoch@admin.com';
    $pass = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, admin) VALUES (?, ?, ?, 1)");
        $n = 'Enoch Admin';
        $stmt->bind_param("sss", $n, $email, $pass);
        $stmt->execute();
        $mensajes[] = "Admin creado: enoch@admin.com / admin123";
    } else {
        $mensajes[] = "enoch@admin.com ya existe";
    }

    // 3. Crear usuario demo
    $demo_email = 'demo@apbarcelona.com';
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $demo_email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $n = 'Demo';
        $p = password_hash('demo123', PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $n, $demo_email, $p);
        $stmt->execute();
        $mensajes[] = "Demo creado: demo@apbarcelona.com / demo123";
    }

    // 4. Obtener demo ID
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $demo_email);
    $stmt->execute();
    $demo = $stmt->get_result()->fetch_assoc();
    $demo_id = $demo['id'];

    // 5. Crear tablas faltantes
    $conn->query("CREATE TABLE IF NOT EXISTS comentarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        apartamento_id INT NOT NULL,
        user_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        rating DECIMAL(2,1) NOT NULL,
        comentario TEXT NOT NULL,
        fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (apartamento_id) REFERENCES apartamentos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $conn->query("CREATE TABLE IF NOT EXISTS comentarios_eliminados (
        id INT AUTO_INCREMENT PRIMARY KEY,
        comentario_id_original INT NOT NULL,
        apartamento_id INT NOT NULL,
        user_id INT NOT NULL,
        user_name VARCHAR(100),
        rating DECIMAL(2,1),
        comentario TEXT,
        eliminado_por INT,
        fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $conn->query("CREATE TABLE IF NOT EXISTS reservas (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $mensajes[] = "Tablas creadas";

    // 6. Insertar los 6 apartamentos
    $lista = [
        ['Piso Luminoso Barceloneta', 'Barceloneta', 95, '2'],
        ['Loft Moderno Eixample', 'Eixample Dret', 120, '1'],
        ['Penthouse con Vistas al Mar', 'Port Olímpic', 280, '3'],
        ['Apartamento El Born', 'El Born', 88, '2'],
        ['Estudio Vista Mar Poblenou', 'Poblenou', 74, '1'],
        ['Piso Clásico Gràcia', 'Gràcia', 105, '2']
    ];

    foreach ($lista as $a) {
        $stmt = $conn->prepare("SELECT id FROM apartamentos WHERE nombre = ?");
        $stmt->bind_param("s", $a[0]);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO apartamentos (user_id, nombre, email, telefono, barrio, habitaciones, precio, descripcion, direccion, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'aprobado')");
            $e = 'propietario@apbarcelona.com';
            $t = '+34 600 000 00' . rand(1,9);
            $d = "Apartamento en {$a[1]}, Barcelona.";
            $dir = "Carrer {$a[1]}, Barcelona";
            $stmt->bind_param("isssssdss", $demo_id, $a[0], $e, $t, $a[1], $a[3], $a[2], $d, $dir);
            $stmt->execute();
            $mensajes[] = "Apartamento '{$a[0]}' creado";
        }
    }

    // 6. Eliminar comentarios anteriores y volver a insertar
    $conn->query("DELETE FROM comentarios WHERE apartamento_id IN (SELECT id FROM apartamentos)");

    $comentarios = [
        // Piso Luminoso Barceloneta
        ['Piso Luminoso Barceloneta', 'Carlos López', 5, 'Excelente ubicación, a un paso de la playa. El apartamento es muy acogedor y tiene todo lo necesario. La dueña fue muy amable. Volveremos sin duda!'],
        ['Piso Luminoso Barceloneta', 'María García', 4, 'Muy buen apartamento, bien equipado. La única pega es que se oye algo de ruido por la noche, pero por lo demás perfecto.'],
        ['Piso Luminoso Barceloneta', 'Pedro Ruiz', 5, 'Todo genial, limpieza impecable y decoración bonita. La cama muy cómoda. Recomendado al 100%.'],
        // Loft Moderno Eixample
        ['Loft Moderno Eixample', 'Ana Martínez', 5, 'El loft es precioso, ideal para parejas. Muy bien situado, cerca de todo. La decoración es espectacular.'],
        ['Loft Moderno Eixample', 'Javier Sánchez', 4, 'Me encantó el estilo moderno del loft. Bien comunicado y con todos los servicios. Repetiría.'],
        ['Loft Moderno Eixample', 'Laura Fernández', 3, 'Bonito pero algo pequeño para dos personas. La ubicación es buena.'],
        // Penthouse con Vistas al Mar
        ['Penthouse con Vistas al Mar', 'David Rodríguez', 5, 'Las vistas son increíbles, el mejor alojamiento en el que he estado. El jacuzzi en la terraza es un plus.'],
        ['Penthouse con Vistas al Mar', 'Sara Pérez', 5, 'Espectacular. Merece cada euro. El atardecer desde la terraza es impresionante.'],
        ['Penthouse con Vistas al Mar', 'Marta López', 4, 'Muy lujoso y bien cuidado. El servicio de atención fue excelente.'],
        // Apartamento El Born
        ['Apartamento El Born', 'Pablo Sánchez', 5, 'El Born es el mejor barrio de Barcelona y este apartamento está en el corazón. Muy recomendable.'],
        ['Apartamento El Born', 'Elena Díaz', 4, 'Apartamento encantador, bien decorado. Cerca de restaurantes y tiendas.'],
        ['Apartamento El Born', 'Miguel Ángel', 5, 'Nos encantó la zona, muy auténtica. El apartamento cómodo y limpio.'],
        // Estudio Vista Mar Poblenou
        ['Estudio Vista Mar Poblenou', 'Jorge Gómez', 4, 'Perfecto para una escapada tranquila. Las vistas al mar son relajantes.'],
        ['Estudio Vista Mar Poblenou', 'Carmen Ruiz', 5, 'Relación calidad-precio excelente. Muy acogedor y limpio.'],
        ['Estudio Vista Mar Poblenou', 'Raúl Torres', 3, 'Estudio correcto para lo que pagas. La zona está un poco alejada del centro.'],
        // Piso Clásico Gràcia
        ['Piso Clásico Gràcia', 'Sofía Navarro', 5, 'El piso es precioso, con techos altos y mucha luz. Gràcia es un barrio con mucho encanto.'],
        ['Piso Clásico Gràcia', 'Andrés Martín', 4, 'Muy auténtico y bien situado. La plaza del Sol está cerca y hay muchos bares.'],
        ['Piso Clásico Gràcia', 'Isabel Ferrer', 5, 'Volveremos! El apartamento es ideal para grupos, amplio y bien equipado.']
    ];

    $insert_comment = $conn->prepare("INSERT INTO comentarios (apartamento_id, user_id, user_name, rating, comentario) VALUES (?, ?, ?, ?, ?)");

    foreach ($comentarios as $c) {
        $nombre_apt = $c[0];
        $user_name = $c[1];
        $rating = $c[2];
        $texto = $c[3];
        $apt_id = $apt_map[$nombre_apt] ?? null;
        if (!$apt_id) continue;
        $insert_comment->bind_param("iisds", $apt_id, $demo_user['id'], $user_name, $rating, $texto);
        $insert_comment->execute();
        $mensajes[] = "Comentario de {$user_name} en '{$nombre_apt}'";
    }

} catch (Exception $e) {
    $error = true;
    $mensajes[] = "ERROR: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Setup ApBarcelona</title>
  <style>
    body { font-family: sans-serif; background: #0a0a0a; color: #fff; padding: 40px; max-width: 600px; margin: auto; }
    h1 { color: #C8102E; }
    .ok { color: #4e4; padding: 4px 0; }
    .err { color: #e44; padding: 4px 0; }
    .box { background: #141414; border-radius: 12px; padding: 20px; margin: 20px 0; }
    a { display: inline-block; margin-top: 12px; margin-right: 8px; padding: 10px 20px; background: #C8102E; color: #fff; text-decoration: none; border-radius: 6px; }
  </style>
</head>
<body>
  <h1>Setup</h1>
  <div class="box">
    <?php foreach ($mensajes as $m): ?>
      <div class="<?php echo strpos($m, 'ERROR') !== false ? 'err' : 'ok'; ?>"><?php echo htmlspecialchars($m); ?></div>
    <?php endforeach; ?>
  </div>
  <?php if (!$error): ?>
    <div class="box" style="border-left: 3px solid #c9a55c;">
      <strong>Admin:</strong> enoch@admin.com / admin123<br>
      <strong>Demo:</strong> demo@apbarcelona.com / demo123
    </div>
    <a href="login.php">Ir al login</a>
    <a href="index.php" style="background:transparent;border:1px solid #333;">Ver inicio</a>
  <?php endif; ?>
</body>
</html>
