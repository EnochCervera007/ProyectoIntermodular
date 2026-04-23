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
  <title>Tarifas – ApBarcelona</title>
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
    .price-table { width: 100%; border-collapse: collapse; }
    .price-table th { text-align: left; padding: 16px; color: #888; font-weight: 500; border-bottom: 1px solid #333; }
    .price-table td { padding: 20px 16px; color: #fff; border-bottom: 1px solid #222; }
    .price-table tr:last-child td { border-bottom: none; }
    .price-table .price { color: #c9a55c; font-weight: 600; font-size: 1.1rem; }
    .price-table .check { color: #4a4; }
    .price-table .xmark { color: #a44; }
    .includes { margin-top: 40px; }
    .includes h3 { color: #fff; font-size: 1.3rem; margin-bottom: 20px; }
    .includes-list { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .includes-list li { color: #888; display: flex; align-items: center; gap: 10px; }
    .includes-list li::before { content: "✓"; color: #4a4; }
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
        <h1 class="info-title">Nuestras <span>tarifas</span></h1>
        <p class="info-subtitle">Sin comisiones ocultas. Solo pagarás cuando consigas reservas.</p>
        
        <table class="price-table">
          <thead>
            <tr>
              <th>Servicio</th>
              <th>Básico</th>
              <th>Profesional</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Publicación en la plataforma</td>
              <td class="check">✓</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Gestión de reservas</td>
              <td class="check">✓</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Motor de búsquedas optimizado</td>
              <td class="check">✓</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Soporte 24/7</td>
              <td class="xmark">—</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Fotografía profesional</td>
              <td class="xmark">—</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Gestión de pagos</td>
              <td class="xmark">—</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td>Limpieza entre huéspedes</td>
              <td class="xmark">—</td>
              <td class="check">✓</td>
            </tr>
            <tr>
              <td><strong>Comisión por reserva</strong></td>
              <td class="price">15%</td>
              <td class="price">25%</td>
            </tr>
          </tbody>
        </table>
        
        <div class="includes">
          <h3>¿Qué está incluido?</h3>
          <ul class="includes-list">
            <li>Visibilidad en Google</li>
            <li>Certificación de calidad</li>
            <li>Asistencia técnica</li>
            <li>Seguro de responsabilidad civil</li>
            <li>Gestión de opiniones</li>
            <li>App de gestión gratuita</li>
          </ul>
        </div>
        
        <div class="cta-box">
          <h3>¿Cuál plan elijo?</h3>
          <p>Empieza con el plan Básico y actualiza cuando quieras.</p>
          <?php if ($logged_in): ?>
            <a href="guardar_apartamento.php" class="btn-cta">Empezar ahora</a>
          <?php else: ?>
            <a href="registro.php" class="btn-cta">Registrarse gratis</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>