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
  <title>Términos y Condiciones – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #fff; }
    .legal-page { padding: 100px 20px 60px; min-height: 100vh; }
    .legal-container { max-width: 800px; margin: 0 auto; }
    .legal-header { text-align: center; margin-bottom: 48px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #ddd;
      border-radius: 8px; color: #333; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s; margin-bottom: 32px;
    }
    .btn-back:hover { background: #f5f5f5; color: #000; }
    .legal-title { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #000; margin-bottom: 12px; }
    .legal-subtitle { color: #666; font-size: 0.9rem; }
    .legal-content { background: #f8f8f8; border-radius: 16px; padding: 40px; }
    .legal-section { margin-bottom: 32px; }
    .legal-section:last-child { margin-bottom: 0; }
    .legal-section h3 { color: #000; font-size: 1.2rem; margin-bottom: 12px; }
    .legal-section p { color: #444; line-height: 1.7; }
    .legal-section ul { color: #444; line-height: 1.7; padding-left: 20px; }
    .legal-section li { margin-bottom: 8px; }
  </style>
</head>
<body>
  <div class="legal-page">
    <div class="legal-container">
      <div class="legal-header">
        <a href="index.php" class="btn-back">← Volver</a>
        <h1 class="legal-title">Términos y <span style="color:#C8102E;">Condiciones</span></h1>
        <p class="legal-subtitle">Última actualización: Abril 2026</p>
      </div>
      
      <div class="legal-content">
        <div class="legal-section">
          <h3>1. Aceptación de términos</h3>
          <p>Al acceder y utilizar ApBarcelona, aceptas estos términos y condiciones. Si no estás de acuerdo, por favor no utilices nuestro sitio.</p>
        </div>
        
        <div class="legal-section">
          <h3>2. Uso del servicio</h3>
          <p>Puedes utilizar nuestro sitio para:</p>
          <ul>
            <li>Buscar y visualizar apartamentos</li>
            <li>Realizar reservas</li>
            <li>Publicar tu apartamento (como propietario)</li>
            <li>Gestionar tus reservas</li>
          </ul>
        </div>
        
        <div class="legal-section">
          <h3>3. Cuentas de usuario</h3>
          <p>Al crear una cuenta, debes proporcionar información veraz y manténla actualizada. Eres responsable de la seguridad de tu cuenta y contraseña.</p>
        </div>
        
        <div class="legal-section">
          <h3>4. Reservas y pagos</h3>
          <p>Las reservas se confirman sujeto a disponibilidad. Los pagos se procesan de forma segura. Las políticas de cancelación varían según el apartamento.</p>
        </div>
        
        <div class="legal-section">
          <h3>5. Publicación de apartamentos</h3>
          <p>Como propietario, debes:</p>
          <ul>
            <li>Proporcionar información precisa del apartamento</li>
            <li>Mantener el apartamento en buenas condiciones</li>
            <li>Responder a las consultas de huéspedes</li>
            <li>Respetar las políticas de cancelación</li>
          </ul>
        </div>
        
        <div class="legal-section">
          <h3>6. Limitación de responsabilidad</h3>
          <p>ApBarcelona actúa como plataforma intermedio. No somos responsables de disputas entre huéspedes y propietarios.</p>
        </div>
        
        <div class="legal-section">
          <h3>7. Modificaciones</h3>
          <p>Podemos modificar estos términos en cualquier momento. El uso continuado del sitio implica la aceptación de los nuevos términos.</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>