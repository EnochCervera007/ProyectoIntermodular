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
  <title>Política de Privacidad – ApBarcelona</title>
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
        <h1 class="legal-title">Política de <span style="color:#C8102E;">Privacidad</span></h1>
        <p class="legal-subtitle">Última actualización: Abril 2026</p>
      </div>
      
      <div class="legal-content">
        <div class="legal-section">
          <h3>1. Introducción</h3>
          <p>En ApBarcelona respectamos tu privacidad y nos comprometemos a proteger tus datos personales. Esta política de privacidad explica cómo recopilamos, usamos y protegemos tu información.</p>
        </div>
        
        <div class="legal-section">
          <h3>2. Datos que recopilamos</h3>
          <ul>
            <li>Nombre y apellidos</li>
            <li>Dirección de correo electrónico</li>
            <li>Número de teléfono</li>
            <li>Dirección IP y datos de navegación</li>
          </ul>
        </div>
        
        <div class="legal-section">
          <h3>3. Uso de tus datos</h3>
          <p>Utilizamos tus datos para:</p>
          <ul>
            <li>Gestionar tu cuenta de usuario</li>
            <li>Procesar tus reservas</li>
            <li>Enviarte comunicaciones sobre tus reservas</li>
            <li>Mejorar nuestros servicios</li>
          </ul>
        </div>
        
        <div class="legal-section">
          <h3>4. Protección de datos</h3>
          <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tus datos contra accesos no autorizados, pérdida o destrucción.</p>
        </div>
        
        <div class="legal-section">
          <h3>5. Tus derechos</h3>
          <p>Tienes derecho a:</p>
          <ul>
            <li>Acceder a tus datos personales</li>
            <li>Rectificar datos inexactos</li>
            <li>Solicitar la eliminación de tus datos</li>
            <li>Oponerte al tratamiento de tus datos</li>
          </ul>
        </div>
        
        <div class="legal-section">
          <h3>6. Contacto</h3>
          <p>Si tienes alguna pregunta sobre esta política, contacta con nosotros en apbarcelona@gmail.es</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>