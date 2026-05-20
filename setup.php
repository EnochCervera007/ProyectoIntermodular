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

    // 5. Insertar los 6 apartamentos
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
