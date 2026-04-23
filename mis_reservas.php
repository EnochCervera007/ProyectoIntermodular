<?php
session_start();
require_once 'conexion.php';
$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';

if (!$logged_in) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM reservas WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$apartamentos = [
    1 => ['titulo' => 'Piso Luminoso Barceloneta', 'barrio' => 'Barceloneta', 'precio' => 95, 'imagen' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400'],
    2 => ['titulo' => 'Loft Moderno Eixample', 'barrio' => 'Eixample Dret', 'precio' => 120, 'imagen' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400'],
    3 => ['titulo' => 'Penthouse con Vistas al Mar', 'barrio' => 'Port Olímpic', 'precio' => 280, 'imagen' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=400'],
    4 => ['titulo' => 'Apartamento El Born', 'barrio' => 'El Born', 'precio' => 88, 'imagen' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400'],
    5 => ['titulo' => 'Estudio Vista Mar Poblenou', 'barrio' => 'Poblenou', 'precio' => 74, 'imagen' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=400'],
    6 => ['titulo' => 'Piso Clásico Gràcia', 'barrio' => 'Gràcia', 'precio' => 105, 'imagen' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #fff; }
    .res-page { padding: 100px 20px 60px; min-height: 100vh; }
    .res-container { max-width: 900px; margin: 0 auto; }
    .res-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #ddd;
      border-radius: 8px; color: #333; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s;
    }
    .btn-back:hover { background: #f5f5f5; color: #000; border-color: #ccc; }
    .res-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #000; }
    .res-title span { color: #C8102E; }
    .res-empty {
      background: #f8f8f8; border: 1px solid #ddd; border-radius: 16px; padding: 60px; text-align: center;
    }
    .res-empty-icon { font-size: 4rem; margin-bottom: 16px; }
    .res-empty h3 { color: #000; font-size: 1.3rem; margin-bottom: 8px; }
    .res-empty p { color: #666; margin-bottom: 24px; }
    .btn-primary {
      display: inline-block; padding: 14px 28px; background: #C8102E;
      border-radius: 8px; color: #fff; font-weight: 600; text-decoration: none;
    }
    .res-list { display: flex; flex-direction: column; gap: 20px; }
    .res-card {
      background: #fff; border: 1px solid #eee; border-radius: 16px; padding: 20px; display: flex; gap: 20px;
    }
    .res-card-img { width: 200px; height: 140px; object-fit: cover; border-radius: 12px; }
    .res-card-info { flex: 1; }
    .res-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
    .res-card-title { font-size: 1.2rem; color: #000; font-weight: 600; }
    .res-card-status {
      padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600;
    }
    .res-card-status.pendiente { background: #fff3e0; color: #f57c00; }
    .res-card-status.confirmada { background: #e8f5e9; color: #2e7d32; }
    .res-card-status.cancelada { background: #ffebee; color: #c62828; }
    .res-card-location { color: #666; font-size: 0.9rem; margin-bottom: 12px; }
    .res-card-dates { display: flex; gap: 24px; margin-bottom: 8px; }
    .res-card-dates span { color: #666; font-size: 0.9rem; }
    .res-card-total { color: #000; font-size: 1.1rem; font-weight: 600; }
    .res-card-total span { color: #666; font-size: 0.85rem; font-weight: 400; }
    .total-gastado { background: #f8f8f8; border-radius: 12px; padding: 24px; margin-top: 32px; text-align: center; }
    .total-gastado span { color: #666; font-size: 0.9rem; }
    .total-gastado strong { display: block; font-size: 2rem; color: #000; margin-top: 8px; }
  </style>
</head>
<body>
  <div class="res-page">
    <div class="res-container">
      <div class="res-header">
        <a href="index.php" class="btn-back">← Volver</a>
      </div>
      
      <h1 class="res-title">Mis <span>Reservas</span></h1>
      
      <?php if ($result->num_rows === 0): ?>
        <div class="res-empty">
          <div class="res-empty-icon">📅</div>
          <h3>No tienes reservas</h3>
          <p>Descubre nuestros apartamentos en Barcelona</p>
          <a href="index.php" class="btn-primary">Ver apartamentos</a>
        </div>
      <?php else: ?>
        <div class="res-list">
          <?php 
          $total_gastado = 0;
          while ($reserva = $result->fetch_assoc()): 
            $apt = $apartamentos[$reserva['apartamento_id']] ?? ['titulo' => 'Apartamento', 'barrio' => 'Barcelona', 'imagen' => ''];
            $total_gastado += $reserva['total'];
          ?>
            <div class="res-card">
              <img src="<?php echo $apt['imagen']; ?>" alt="<?php echo htmlspecialchars($apt['titulo']); ?>" class="res-card-img">
              <div class="res-card-info">
                <div class="res-card-header">
                  <h3 class="res-card-title"><?php echo htmlspecialchars($apt['titulo']); ?></h3>
                  <span class="res-card-status <?php echo $reserva['estado']; ?>"><?php echo ucfirst($reserva['estado']); ?></span>
                </div>
                <p class="res-card-location">📍 <?php echo htmlspecialchars($apt['barrio']); ?>, Barcelona</p>
                <div class="res-card-dates">
                  <span>📅 Entrada: <?php echo $reserva['checkin']; ?></span>
                  <span>📅 Salida: <?php echo $reserva['checkout']; ?></span>
                </div>
                <p class="res-card-total">€<?php echo number_format($reserva['total'], 0); ?> <span>(<?php echo $reserva['noches']; ?> noches)</span></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
        
        <div class="total-gastado">
          <span>Total gastado en reservas</span>
          <strong>€<?php echo number_format($total_gastado, 0); ?></strong>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>