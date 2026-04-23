<?php
session_start();
require_once 'conexion.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Todos los campos son obligatorios';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } else {
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El email ya está registrado';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $nombre, $email, $password_hash);

            if ($stmt->execute()) {
                $stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                header("Location: index.php");
                exit;
            } else {
                $error = 'Error al registrar. Inténtalo de nuevo.';
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
  <title>Registro – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    .auth-page {min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0a0a0a; padding: 20px;}
    .auth-card {background: #141414; border-radius: 24px; padding: 48px; width: 100%; max-width: 440px; border: 1px solid #222;}
    .auth-card h1 {font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #fff; margin-bottom: 8px;}
    .auth-card p {color: #888; margin-bottom: 32px;}
    .auth-form .form-group {margin-bottom: 20px;}
    .auth-form label {display: block; color: #aaa; font-size: 0.875rem; margin-bottom: 8px;}
    .auth-form input {width: 100%; padding: 14px 16px; background: #1a1a1a; border: 1px solid #333; border-radius: 12px; color: #fff; font-size: 1rem; transition: border-color 0.2s;}
    .auth-form input:focus {outline: none; border-color: #c9a55c;}
    .auth-form input::placeholder {color: #555;}
    .btn-auth {width: 100%; padding: 16px; background: linear-gradient(135deg, #c9a55c, #a3843e); border: none; border-radius: 12px; color: #000; font-size: 1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s, opacity 0.2s;}
    .btn-auth:hover {transform: translateY(-2px);}
    .auth-error {background: #2a1515; border: 1px solid #4a2020; color: #e55; padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem;}
    .auth-success {background: #152a15; border: 1px solid #204a20; color: #5e5; padding: 14px 16px; border-radius: 12px; margin-bottom: 20px; font-size: 0.9rem;}
    .auth-footer {text-align: center; margin-top: 24px; color: #666;}
    .auth-footer a {color: #c9a55c; text-decoration: none;}
    .auth-footer a:hover {text-decoration: underline;}
  </style>
</head>
<body>
  <div class="auth-page">
    <div class="auth-card">
      <h1>Crear cuenta</h1>
      <p>Únete a ApBarcelona</p>
      
      <?php if ($error): ?>
        <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      
      <?php if ($success): ?>
        <div class="auth-success"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" placeholder="Joan García" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="joan@ejemplo.com" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        <div class="form-group">
          <label for="password_confirm">Confirmar contraseña</label>
          <input type="password" id="password_confirm" name="password_confirm" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-auth">Crear cuenta</button>
      </form>
      <p class="auth-footer">¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
    </div>
  </div>
</body>
</html>