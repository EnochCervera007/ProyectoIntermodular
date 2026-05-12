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
    $imagenes = [];

    if (empty($nombre) || empty($email) || empty($barrio) || empty($habitaciones) || empty($precio) || empty($descripcion) || empty($direccion)) {
        $error = 'Todos los campos son obligatorios';
    } else {
        if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $total = count($_FILES['imagenes']['name']);

            for ($i = 0; $i < $total; $i++) {
                if ($_FILES['imagenes']['error'][$i] === UPLOAD_ERR_OK) {
                    if (!in_array($_FILES['imagenes']['type'][$i], $tipos_permitidos)) {
                        $error = 'Solo se permiten imágenes (JPG, PNG, GIF, WebP)';
                        break;
                    }
                    if ($_FILES['imagenes']['size'][$i] > 5 * 1024 * 1024) {
                        $error = 'Cada imagen no puede superar los 5MB';
                        break;
                    }
                    $ext = pathinfo($_FILES['imagenes']['name'][$i], PATHINFO_EXTENSION);
                    $nombre_img = uniqid('apt_') . '_' . $i . '.' . $ext;
                    $ruta_destino = __DIR__ . '/uploads/' . $nombre_img;
                    if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $ruta_destino)) {
                        $imagenes[] = $nombre_img;
                    } else {
                        $error = 'Error al subir una de las imágenes';
                        break;
                    }
                }
            }
        }

        if (empty($error)) {
            $imagen_str = !empty($imagenes) ? implode(',', $imagenes) : '';
            $sql = "INSERT INTO apartamentos (user_id, nombre, email, telefono, barrio, habitaciones, precio, descripcion, direccion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                $error = 'Error en la consulta: ' . $conn->error;
            } else {
                $stmt->bind_param("issssssdss", $_SESSION['user_id'], $nombre, $email, $telefono, $barrio, $habitaciones, $precio, $descripcion, $direccion, $imagen_str);

                if ($stmt->execute()) {
                    $success = 'Apartamento enviado correctamente. Te contactaremos pronto.';
                } else {
                    $error = 'Error al enviar. Inténtalo de nuevo.';
                }
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
    body { background: #fff; }
    .form-page { padding: 100px 20px 60px; min-height: 100vh; }
    .form-container { max-width: 800px; margin: 0 auto; }
    .form-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #ddd;
      border-radius: 8px; color: #333; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s;
    }
    .btn-back:hover { background: #f5f5f5; color: #000; border-color: #ccc; }
    .form-card {
      background: #f8f8f8; border: 1px solid #ddd; border-radius: 16px; padding: 40px;
    }
    .form-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #000; margin-bottom: 8px; }
    .form-title span { color: #C8102E; }
    .form-subtitle { color: #666; margin-bottom: 32px; }
    .user-bar { display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #fff; border: 1px solid #eee; border-radius: 8px; margin-bottom: 24px; }
    .user-bar span { color: #666; font-size: 0.9rem; }
    .user-bar a { color: #C8102E; text-decoration: none; font-size: 0.9rem; }
    .user-bar a:hover { text-decoration: underline; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; color: #444; font-size: 0.85rem; margin-bottom: 8px; }
    .form-group input, .form-group select, .form-group textarea {
      width: 100%; padding: 14px 16px; background: #fff; border: 1px solid #ddd;
      border-radius: 8px; color: #000; font-size: 1rem; font-family: inherit;
    }
    .form-group select option { background: #fff; }
    .form-group textarea { min-height: 100px; resize: vertical; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
      outline: none; border-color: #C8102E;
    }
    .form-group.full { grid-column: 1 / -1; }
    .btn-send {
      width: 100%; padding: 16px; background: #C8102E;
      border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600;
      cursor: pointer; transition: transform 0.2s;
    }
    .btn-send:hover { transform: translateY(-2px); background: #a00d25; }
    .alert { padding: 14px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; }
    .alert.success { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; }
    .alert.error { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; }
    .photo-box {
      border: 2px dashed #ddd; border-radius: 12px; padding: 32px 20px;
      text-align: center; cursor: pointer; transition: all 0.2s;
      background: #fff; position: relative;
    }
    .photo-box:hover { border-color: #C8102E; background: #fafafa; }
    .photo-box input[type="file"] {
      position: absolute; opacity: 0; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;
    }
    .photo-box .icon { font-size: 2.2rem; margin-bottom: 8px; }
    .photo-box p { color: #666; font-size: 0.95rem; margin: 0; }
    .photo-strip { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
    .photo-strip .thumb {
      position: relative; width: 64px; height: 64px; border-radius: 6px;
      overflow: hidden; border: 1px solid #ddd;
    }
    .photo-strip .thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .photo-strip .thumb .x {
      position: absolute; top: 2px; right: 2px;
      width: 18px; height: 18px; border-radius: 50%;
      background: rgba(0,0,0,0.7); color: #fff; border: none;
      font-size: 0.6rem; cursor: pointer; line-height: 18px; text-align: center;
    }
    .photo-strip .thumb .x:hover { background: #C8102E; color: #fff; }
    .photo-info { color: #555; font-size: 0.8rem; margin-top: 6px; }
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

        <form method="POST" enctype="multipart/form-data">
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
          <div class="form-group full">
            <label>Fotos del apartamento</label>
            <div class="photo-box" id="photoBox">
              <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/jpeg,image/png,image/gif,image/webp">
              <div class="icon">📷</div>
              <p>Añadir fotos</p>
            </div>
            <div class="photo-info" id="photoInfo"></div>
            <div class="photo-strip" id="photoStrip"></div>
          </div>
          <button type="submit" class="btn-send">Enviar apartamento →</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
