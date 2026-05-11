<?php
session_start();
require_once 'conexion.php';

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios';
    } else {
        $stmt = $conn->prepare("SELECT id, nombre, password, admin FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['admin'] = $user['admin'] ?? 0;
                header("Location: index.php");
                exit;
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Email no registrado';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – ApBarcelona</title>
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
    .auth-links {display: flex; justify-content: space-between; margin-top: 16px;}
    .auth-links a {color: #888; font-size: 0.9rem; text-decoration: none;}
    .auth-links a:hover {color: #c9a55c;}
  </style>
</head>
<body>
  <div class="auth-page">
    <div class="auth-card">
      <h1>Bienvenido</h1>
      <p>Inicia sesión en tu cuenta</p>
      
      <?php if ($error): ?>
        <div class="auth-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="POST" class="auth-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="joan@ejemplo.com" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-auth">Iniciar sesión</button>
      </form>
      <div class="auth-links">
        <a href="registro.php">¿No tienes cuenta? Regístrate</a>
      </div>
    </div>
  </div>
</body>
</html>
