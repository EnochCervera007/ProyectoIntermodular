<?php
session_start();
require_once 'conexion.php';

$success = '';
$error = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=guardar_apartamento.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $barrio = $_POST['barrio'] ?? '';
    $habitaciones = $_POST['habitaciones'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    if (empty($nombre) || empty($email) || empty($barrio) || empty($habitaciones) || empty($precio) || empty($descripcion) || empty($direccion)) {
        $error = 'Todos los campos son obligatorios';
    } else {
        $sql = "INSERT INTO apartamentos (user_id, nombre, email, telefono, barrio, habitaciones, precio, descripcion, direccion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt === false) {
            $error = 'Error en la consulta: ' . $conn->error;
        } else {
            $stmt->bind_param("isssssdss", $_SESSION['user_id'], $nombre, $email, $telefono, $barrio, $habitaciones, $precio, $descripcion, $direccion);

            if ($stmt->execute()) {
                $success = 'Apartamento enviado correctamente. Te contactaremos pronto.';
            } else {
                $error = 'Error al enviar. Inténtalo de nuevo.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicar apartamento – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #0a0a0a; }
    .form-page { padding: 100px 20px 60px; min-height: 100vh; }
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #333;
      border-radius: 8px; color: #888; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s;
    }
    .btn-back:hover { background: #1a1a1a; color: #fff; border-color: #444; }
    .form-card {
      background: #141414; border: 1px solid #222; border-radius: 16px; padding: 40px;
    }
    .form-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #fff; margin-bottom: 8px; }
    .form-title span { color: #c9a55c; }
    .form-subtitle { color: #666; margin-bottom: 32px; }
    .user-bar { display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #1a1a1a; border-radius: 8px; margin-bottom: 24px; }
    .user-bar span { color: #888; font-size: 0.9rem; }
    .user-bar a { color: #c9a55c; text-decoration: none; font-size: 0.9rem; }
    .user-bar a:hover { text-decoration: underline; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: #888; font-size: 0.85rem; margin-bottom: 8px; }
    .form-group input, .form-group select, .form-group textarea {
      width: 100%; padding: 14px 16px; background: #0d0d0d; border: 1px solid #222;
      border-radius: 8px; color: #fff; font-size: 1rem; font-family: inherit;
    }
    .form-group select option { background: #1a1a1a; }
    .form-group textarea { min-height: 100px; resize: vertical; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none; border-color: #c9a55c;
    }
    .form-group.full { grid-column: 1 / -1; }
    .btn-send {
      width: 100%; padding: 16px; background: linear-gradient(135deg, #c9a55c, #a3843e);
      border: none; border-radius: 8px; color: #000; font-size: 1rem; font-weight: 600;
      cursor: pointer; transition: transform 0.2s;
    }
    .btn-send:hover { transform: translateY(-2px); }
    .alert { padding: 14px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
    .alert.success { background: #152a15; border: 1px solid #204a20; color: #5e5; }
    .alert.error { background: #2a1515; border: 1px solid #4a2020; color: #e55; }
  </style>
</head>
<body>
  <div class="form-page">
    <div class="form-container">
      <div class="form-header">
        <a href="index.php" class="btn-back">← Volver al inicio</a>
      </div>
      
      <div class="form-card">
        <h1 class="form-title">Publicar tu <span>apartamento</span></h1>
        <p class="form-subtitle">Rellena el formulario y nos pondremos en contacto.</p>
        
        <div class="user-bar">
          <span>Sesión: <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          <a href="logout.php">Cerrar sesión</a>
        </div>

        <?php if ($success): ?>
          <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
          <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="form-grid">
            <div class="form-group">
              <label for="nombre">Nombre completo *</label>
              <input type="text" id="nombre" name="nombre" placeholder="Joan García" required>
            </div>
            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" placeholder="joan@ejemplo.com" required>
            </div>
            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input type="tel" id="telefono" name="telefono" placeholder="+34 600 000 000">
            </div>
            <div class="form-group">
              <label for="barrio">Barrio *</label>
              <select id="barrio" name="barrio" required>
                <option value="">Selecciona un barrio</option>
                <option>Barceloneta</option>
                <option>Eixample</option>
                <option>Gràcia</option>
                <option>El Born</option>
                <option>Poblenou</option>
                <option>Sarrià</option>
                <option>Les Corts</option>
                <option>Otro</option>
              </select>
            </div>
            <div class="form-group">
              <label for="habitaciones">Habitaciones *</label>
              <select id="habitaciones" name="habitaciones" required>
                <option value="">Nº habitaciones</option>
                <option>Estudio</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4+</option>
              </select>
            </div>
            <div class="form-group">
              <label for="precio">Precio por noche (€) *</label>
              <input type="number" id="precio" name="precio" placeholder="80" min="1" required>
            </div>
          </div>
          <div class="form-group full">
            <label for="descripcion">Descripción *</label>
            <textarea id="descripcion" name="descripcion" placeholder="Describe tu apartamento..." required></textarea>
          </div>
          <div class="form-group full">
            <label for="direccion">Dirección (no se mostrará) *</label>
            <input type="text" id="direccion" name="direccion" placeholder="Carrer de..., Barcelona" required>
          </div>
          <button type="submit" class="btn-send">Enviar apartamento →</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>