<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || !($_SESSION['admin'] ?? 0)) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'Admin';

$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
$total_apartamentos = $conn->query("SELECT COUNT(*) FROM apartamentos")->fetch_row()[0];
$total_reservas = $conn->query("SELECT COUNT(*) FROM reservas")->fetch_row()[0];
$total_comentarios = $conn->query("SELECT COUNT(*) FROM comentarios")->fetch_row()[0];
$pendientes = $conn->query("SELECT COUNT(*) FROM apartamentos WHERE estado='pendiente'")->fetch_row()[0];
$nuevos_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios WHERE created_at >= NOW() - INTERVAL 7 DAY")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; background: #f5f5f5; display: flex; }
    .sidebar {
      width: 260px; background: #111; min-height: 100vh; padding: 30px 20px;
      position: fixed; left: 0; top: 0;
    }
    .sidebar h2 { font-family: 'Playfair Display', serif; color: #fff; font-size: 1.5rem; margin-bottom: 30px; }
    .sidebar h2 span { color: #C8102E; }
    .sidebar a {
      display: block; color: #888; padding: 12px 16px; border-radius: 10px;
      font-size: 0.92rem; font-weight: 500; text-decoration: none;
      transition: all 0.2s; margin-bottom: 4px;
    }
    .sidebar a:hover, .sidebar a.active { background: #1a1a1a; color: #fff; }
    .sidebar a.active { background: #C8102E; color: #fff; }
    .sidebar .salir { margin-top: 30px; color: #e55; }
    .sidebar .salir:hover { background: #2a1515; }

    .main { margin-left: 260px; flex: 1; padding: 30px; }
    .topbar {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e0e0e0;
    }
    .topbar h1 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #111; }
    .topbar .user { color: #666; font-size: 0.9rem; }
    .topbar .user strong { color: #111; }

    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card {
      background: #fff; border-radius: 16px; padding: 24px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;
    }
    .stat-card .num { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: #111; }
    .stat-card .label { color: #888; font-size: 0.85rem; margin-top: 4px; }
    .stat-card .num.rojo { color: #C8102E; }
    .stat-card .num.ambar { color: #e67e22; }
    .stat-card .num.verde { color: #27ae60; }

    .quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .quick-link {
      background: #fff; border-radius: 16px; padding: 28px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee;
      text-decoration: none; transition: all 0.2s;
    }
    .quick-link:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .quick-link .icon { font-size: 2rem; margin-bottom: 12px; }
    .quick-link h3 { font-family: 'Playfair Display', serif; color: #111; font-size: 1.1rem; margin-bottom: 6px; }
    .quick-link p { color: #888; font-size: 0.85rem; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Ap<span>Barcelona</span></h2>
    <a href="admin.php" class="active">Dashboard</a>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_comentarios.php">Comentarios</a>
    <a href="admin_apartamentos.php">Apartamentos</a>
    <a href="admin_backup.php">Backup / Restaurar</a>
    <a href="index.php">← Volver a la web</a>
    <a href="logout.php" class="salir">Cerrar sesión</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
      <div class="user">Bienvenido, <strong><?php echo htmlspecialchars($user_name); ?></strong></div>
    </div>

    <div class="stats">
      <div class="stat-card">
        <div class="num rojo"><?php echo $total_usuarios; ?></div>
        <div class="label">Usuarios registrados</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $total_apartamentos; ?></div>
        <div class="label">Apartamentos publicados</div>
      </div>
      <div class="stat-card">
        <div class="num ambar"><?php echo $pendientes; ?></div>
        <div class="label">Pendientes de revisión</div>
      </div>
      <div class="stat-card">
        <div class="num verde"><?php echo $total_reservas; ?></div>
        <div class="label">Reservas realizadas</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $total_comentarios; ?></div>
        <div class="label">Comentarios</div>
      </div>
      <div class="stat-card">
        <div class="num"><?php echo $nuevos_usuarios; ?></div>
        <div class="label">Nuevos (7 días)</div>
      </div>
    </div>

    <h2 style="font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #111; margin-bottom: 16px;">Acceso rápido</h2>
    <div class="quick-links">
      <a href="admin_usuarios.php" class="quick-link">
        <div class="icon">👥</div>
        <h3>Gestionar Usuarios</h3>
        <p>Crear, editar y eliminar usuarios del sistema</p>
      </a>
      <a href="admin_comentarios.php" class="quick-link">
        <div class="icon">💬</div>
        <h3>Gestionar Comentarios</h3>
        <p>Moderar y eliminar comentarios</p>
      </a>
      <a href="admin_apartamentos.php" class="quick-link">
        <div class="icon">🏠</div>
        <h3>Gestionar Apartamentos</h3>
        <p>Aprobar, rechazar y administrar apartamentos</p>
      </a>
      <a href="admin_backup.php" class="quick-link">
        <div class="icon">💾</div>
        <h3>Backup / Restaurar</h3>
        <p>Copias de seguridad y restauración</p>
      </a>
    </div>
  </div>
</body>
</html>
