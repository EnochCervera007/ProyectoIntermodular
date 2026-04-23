<?php
session_start();
require_once 'conexion.php';
require_once 'email_config.php';

$logged_in = isset($_SESSION['user_id']);

if (!$logged_in) {
    header("Location: login.php");
    exit;
}

$apartamento_id = (int)($_POST['apartamento_id'] ?? 1);
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

$user_email = '';
$user_name = $_SESSION['user_name'] ?? 'Cliente';

$stmt = $conn->prepare("SELECT email FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_email = $row['email'];
}

$stmt = $conn->prepare("INSERT INTO reservas (user_id, apartamento_id, checkin, checkout, noches, total, estado, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("iissis", $_SESSION['user_id'], $apartamento_id, $checkin, $checkout, $noches, $total);
$stmt->execute();

enviarEmail($user_email, $user_name, $apt['titulo'], $apt['barrio'], $checkin, $checkout, $noches, $total);
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
    body { background: #fff; }
    .res-page { padding: 100px 20px 60px; min-height: 100vh; }
    .res-container { max-width: 600px; margin: 0 auto; text-align: center; }
    .res-card {
      background: #fff; border: 1px solid #ddd; border-radius: 16px; padding: 48px;
    }
    .res-icon { font-size: 4rem; margin-bottom: 24px; }
    .res-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #000; margin-bottom: 12px; }
    .res-subtitle { color: #666; margin-bottom: 32px; }
    .res-details {
      background: #f8f8f8; border-radius: 12px; padding: 24px; margin-bottom: 32px; text-align: left;
    }
    .res-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #eee; }
    .res-row:last-child { border-bottom: none; }
    .res-row span:first-child { color: #666; }
    .res-row span:last-child { color: #000; font-weight: 500; }
    .res-email {
      background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: 8px; padding: 16px; margin-bottom: 24px; text-align: center;
    }
    .res-email p { color: #2e7d32; font-weight: 500; }
    .btn-home {
      display: inline-block; padding: 14px 32px; background: #C8102E;
      border-radius: 8px; color: #fff; font-weight: 600; text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="res-page">
    <div class="res-container">
      <div class="res-card">
        <div class="res-icon">✅</div>
        <h1 class="res-title">¡Reserva realizada!</h1>
        <p class="res-subtitle">Tu reserva ha sido confirmada. Gracias por confiar en ApBarcelona.</p>
        
        <div class="res-email">
          <p>📧 Confirmación enviada a: <?php echo htmlspecialchars($user_email); ?></p>
        </div>
        
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