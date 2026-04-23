<?php
session_start();
require_once 'conexion.php';

$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cómo funciona – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #0a0a0a; }
    .info-page { padding: 100px 20px 60px; min-height: 100vh; }
    .info-container { max-width: 900px; margin: 0 auto; }
    .info-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #333;
      border-radius: 8px; color: #888; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s;
    }
    .btn-back:hover { background: #1a1a1a; color: #fff; border-color: #444; }
    .nav-buttons-top { display: flex; gap: 12px; }
    .btn-nav-top {
      padding: 10px 20px; background: #C8102E; border-radius: 50px;
      color: #fff; font-size: 0.85rem; font-weight: 600; text-decoration: none;
    }
    .info-card {
      background: #141414; border: 1px solid #222; border-radius: 16px; padding: 40px;
    }
    .info-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #fff; margin-bottom: 16px; }
    .info-title span { color: #c9a55c; }
    .info-subtitle { color: #888; font-size: 1.1rem; margin-bottom: 40px; }
    .step { display: flex; gap: 24px; margin-bottom: 32px; }
    .step-num {
      width: 48px; height: 48px; background: linear-gradient(135deg, #c9a55c, #a3843e);
      border-radius: 50%; display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; font-weight: 700; color: #000; flex-shrink: 0;
    }
    .step-content h3 { color: #fff; font-size: 1.2rem; margin-bottom: 8px; }
    .step-content p { color: #888; line-height: 1.6; }
    .cta-box {
      background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 32px;
      text-align: center; margin-top: 40px;
    }
    .cta-box h3 { color: #fff; font-size: 1.3rem; margin-bottom: 12px; }
    .cta-box p { color: #888; margin-bottom: 20px; }
    .btn-cta {
      display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #c9a55c, #a3843e);
      border-radius: 8px; color: #000; font-weight: 600; text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="info-page">
    <div class="info-container">
      <div class="info-header">
        <a href="index.php" class="btn-back">← Volver</a>
        <div class="nav-buttons-top">
          <?php if ($logged_in): ?>
            <a href="guardar_apartamento.php" class="btn-nav-top">Publicar</a>
            <a href="logout.php" class="btn-nav-top" style="background:transparent;border:1px solid #333;color:#888;"><?php echo htmlspecialchars($user_name); ?></a>
          <?php else: ?>
            <a href="login.php" class="btn-nav-top">Iniciar Sesión</a>
            <a href="registro.php" class="btn-nav-top" style="background:transparent;border:1px solid #C8102E;color:#C8102E;">Registrarse</a>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="info-card">
        <h1 class="info-title">Cómo <span>funciona</span></h1>
        <p class="info-subtitle">Publica tu apartamento en 4 simples pasos</p>
        
        <div class="step">
          <div class="step-num">1</div>
          <div class="step-content">
            <h3>Regístrate o inicia sesión</h3>
            <p>Crea una cuenta gratis o inicia sesión si ya tienes una. Solo necesitas tu email y una contraseña.</p>
          </div>
        </div>
        
        <div class="step">
          <div class="step-num">2</div>
          <div class="step-content">
            <h3>Rellena el formulario</h3>
            <p>Completa los datos de tu apartamento: ubicación, número de habitaciones, precio por noche y descripción.</p>
          </div>
        </div>
        
        <div class="step">
          <div class="step-num">3</div>
          <div class="step-content">
            <h3>Revisamos tu solicitud</h3>
            <p>Nuestro equipo revisará tu apartamento en menos de 24 horas para verificar que cumple nuestros estándares.</p>
          </div>
        </div>
        
        <div class="step">
          <div class="step-num">4</div>
          <div class="step-content">
            <h3>¡Listo para recibir reservas!</h3>
            <p>Una vez aprobado, tu apartamento aparecerá en nuestra plataforma y empezarás a recibir reservas.</p>
          </div>
        </div>
        
        <div class="cta-box">
          <h3>¿Tienes un apartamento en Barcelona?</h3>
          <p>Únete a más de 240 propietarios en ApBarcelona</p>
          <?php if ($logged_in): ?>
            <a href="guardar_apartamento.php" class="btn-cta">Publicar mi apartamento</a>
          <?php else: ?>
            <a href="registro.php" class="btn-cta">Empezar ahora</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>