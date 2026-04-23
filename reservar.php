<?php
session_start();
require_once 'conexion.php';

$logged_in = isset($_SESSION['user_id']);

if (!$logged_in) {
    header("Location: login.php?redirect=reservar.php");
    exit;
}

$apartamento_id = $_POST['apartamento_id'] ?? 1;
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';

if (empty($checkin) || empty($checkout)) {
    header("Location: index.php");
    exit;
}

$apartamentos = [
    1 => ['titulo' => 'Piso Luminoso Barceloneta', 'barrio' => 'Barceloneta', 'precio' => 95],
    2 => ['titulo' => 'Loft Moderno Eixample', 'barrio' => 'Eixample Dret', 'precio' => 120],
    3 => ['titulo' => 'Penthouse con Vistas al Mar', 'barrio' => 'Port Olímpic', 'precio' => 280],
    4 => ['titulo' => 'Apartamento El Born', 'barrio' => 'El Born', 'precio' => 88],
    5 => ['titulo' => 'Estudio Vista Mar Poblenou', 'barrio' => 'Poblenou', 'precio' => 74],
    6 => ['titulo' => 'Piso Clásico Gràcia', 'barrio' => 'Gràcia', 'precio' => 105]
];

$apt = $apartamentos[$apartamento_id] ?? $apartamentos[1];

$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$noches = $checkout_date->diff($checkin_date)->days;
$total = $apt['precio'] * $noches;

$stmt = $conn->prepare("INSERT INTO reservas (user_id, apartamento_id, checkin, checkout, noches, total, estado, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("iissi", $_SESSION['user_id'], $apartamento_id, $checkin, $checkout, $total);
$stmt->execute();

$reserva_id = $conn->insert_id;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reserva confirmada – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #0a0a0a; }
    .res-page { padding: 100px 20px 60px; min-height: 100vh; }
    .res-container { max-width: 600px; margin: 0 auto; text-align: center; }
    .res-card {
      background: #141414; border: 1px solid #222; border-radius: 16px; padding: 48px;
    }
    .res-icon { font-size: 4rem; margin-bottom: 24px; }
    .res-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #fff; margin-bottom: 12px; }
    .res-subtitle { color: #888; margin-bottom: 32px; }
    .res-details {
      background: #1a1a1a; border-radius: 12px; padding: 24px; margin-bottom: 32px; text-align: left;
    }
    .res-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #222; }
    .res-row:last-child { border-bottom: none; }
    .res-row span:first-child { color: #888; }
    .res-row span:last-child { color: #fff; font-weight: 500; }
    .btn-home {
      display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #c9a55c, #a3843e);
      border-radius: 8px; color: #000; font-weight: 600; text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="res-page">
    <div class="res-container">
      <div class="res-card">
        <div class="res-icon">✅</div>
        <h1 class="res-title">¡Reserva confirmada!</h1>
        <p class="res-subtitle">Tu reserva ha sido enviada al propietario.</p>
        
        <div class="res-details">
          <div class="res-row">
            <span>Apartamento</span>
            <span><?php echo htmlspecialchars($apt['titulo']); ?></span>
          </div>
          <div class="res-row">
            <span>Ubicación</span>
            <span><?php echo htmlspecialchars($apt['barrio']); ?></span>
          </div>
          <div class="res-row">
            <span>Entrada</span>
            <span><?php echo $checkin; ?></span>
          </div>
          <div class="res-row">
            <span>Salida</span>
            <span><?php echo $checkout; ?></span>
          </div>
          <div class="res-row">
            <span>Noches</span>
            <span><?php echo $noches; ?></span>
          </div>
          <div class="res-row">
            <span>Total</span>
            <span>€<?php echo $total; ?></span>
          </div>
        </div>
        
        <a href="index.php" class="btn-home">Volver al inicio</a>
      </div>
    </div>
  </div>
</body>
</html>