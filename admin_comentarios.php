<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || !($_SESSION['admin'] ?? 0)) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Eliminar comentario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $admin_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM comentarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $comentario = $stmt->get_result()->fetch_assoc();

    if ($comentario) {
        $stmt2 = $conn->prepare("INSERT INTO comentarios_eliminados (comentario_id_original, apartamento_id, user_id, user_name, rating, comentario, eliminado_por) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiisdsi", $comentario['id'], $comentario['apartamento_id'], $comentario['user_id'], $comentario['user_name'], $comentario['rating'], $comentario['comentario'], $admin_id);
        $stmt2->execute();

        $stmt3 = $conn->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt3->bind_param("i", $id);
        $stmt3->execute();

        $mensaje = '<div class="msg ok">Comentario eliminado</div>';
    }
}

$result = $conn->query("
    SELECT c.*, a.nombre as apt_nombre 
    FROM comentarios c 
    LEFT JOIN apartamentos a ON c.apartamento_id = a.id 
    ORDER BY c.fecha DESC
");
$comentarios = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comentarios – Admin ApBarcelona</title>
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
      margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e0e0e0;
    }
    .topbar h1 { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #111; }
    .msg { padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 0.9rem; }
    .msg.ok { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
    .msg.error { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }

    .card {
      background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 16px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #eee;
    }
    .card-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .card-user { font-weight: 600; color: #111; }
    .card-apt { font-size: 0.82rem; color: #888; }
    .card-apt a { color: #C8102E; text-decoration: none; }
    .card-rating { color: #f39c12; font-size: 0.9rem; }
    .card-text { color: #444; line-height: 1.6; font-size: 0.92rem; margin-bottom: 10px; }
    .card-footer { display: flex; justify-content: space-between; align-items: center; }
    .card-date { font-size: 0.8rem; color: #aaa; }
    .btn-danger { background: #c62828; color: #fff; padding: 6px 14px; border-radius: 6px; border: none; font-family: 'DM Sans', sans-serif; font-size: 0.8rem; cursor: pointer; text-decoration: none; }
    .btn-danger:hover { background: #9e0c24; }
    .vacio { text-align: center; padding: 60px 20px; color: #888; }
    .vacio .icon { font-size: 3rem; margin-bottom: 12px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Ap<span>Barcelona</span></h2>
    <a href="admin.php">Dashboard</a>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_comentarios.php" class="active">Comentarios</a>
    <a href="admin_apartamentos.php">Apartamentos</a>
    <a href="admin_backup.php">Backup / Restaurar</a>
    <a href="index.php">← Volver a la web</a>
    <a href="logout.php" class="salir">Cerrar sesión</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Comentarios</h1>
      <div><strong><?php echo count($comentarios); ?></strong> comentarios</div>
    </div>

    <?php echo $mensaje; ?>

    <?php if (count($comentarios) > 0): ?>
      <?php foreach ($comentarios as $c): ?>
      <div class="card">
        <div class="card-top">
          <div>
            <div class="card-user"><?php echo htmlspecialchars($c['user_name']); ?></div>
            <div class="card-apt">
              Apartamento: 
              <a href="apartamento.php?id=<?php echo $c['apartamento_id']; ?>" target="_blank">
                <?php echo htmlspecialchars($c['apt_nombre'] ?? ('ID ' . $c['apartamento_id'])); ?>
              </a>
            </div>
          </div>
          <div class="card-rating"><?php for($i=0; $i<$c['rating']; $i++) echo '⭐'; ?></div>
        </div>
        <div class="card-text"><?php echo nl2br(htmlspecialchars($c['comentario'])); ?></div>
        <div class="card-footer">
          <div class="card-date"><?php echo date('d/m/Y H:i', strtotime($c['fecha'])); ?></div>
          <a href="?eliminar=<?php echo $c['id']; ?>" class="btn-danger" onclick="return confirm('¿Eliminar este comentario?')">Eliminar</a>
        </div>
      </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="vacio">
        <div class="icon">💬</div>
        <p>No hay comentarios en la plataforma</p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
