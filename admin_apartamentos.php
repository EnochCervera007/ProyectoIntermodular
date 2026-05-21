<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || !($_SESSION['admin'] ?? 0)) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Cambiar estado
if (isset($_GET['estado']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $estado = $_GET['estado'];
    if (in_array($estado, ['pendiente', 'aprobado', 'rechazado'])) {
        $stmt = $conn->prepare("UPDATE apartamentos SET estado = ? WHERE id = ?");
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
        $mensaje = '<div class="msg ok">Estado actualizado</div>';
    }
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM apartamentos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $mensaje = '<div class="msg ok">Apartamento eliminado</div>';
}

$result = $conn->query("
    SELECT a.*, u.nombre as propietario, u.email as email_prop 
    FROM apartamentos a 
    LEFT JOIN usuarios u ON a.user_id = u.id 
    ORDER BY a.created_at DESC
");
$apartamentos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apartamentos – Admin ApBarcelona</title>
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

    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    th { background: #fafafa; text-align: left; padding: 14px 16px; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #eee; }
    td { padding: 14px 16px; font-size: 0.9rem; color: #333; border-bottom: 1px solid #f5f5f5; }
    tr:hover td { background: #fafafa; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .badge.pendiente { background: #fff3e0; color: #e65100; }
    .badge.aprobado { background: #e8f5e9; color: #2e7d32; }
    .badge.rechazado { background: #ffebee; color: #c62828; }

    .acciones { display: flex; gap: 4px; flex-wrap: wrap; }
    .acciones a { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; text-decoration: none; font-weight: 500; }
    .btn-aprobar { background: #e8f5e9; color: #2e7d32; }
    .btn-rechazar { background: #ffebee; color: #c62828; }
    .btn-pendiente { background: #fff3e0; color: #e65100; }
    .btn-ver { background: #e3f2fd; color: #1565c0; }
    .btn-eliminar { background: #c62828; color: #fff; }

    .vacio { text-align: center; padding: 60px 20px; color: #888; }
    .vacio .icon { font-size: 3rem; margin-bottom: 12px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Ap<span>Barcelona</span></h2>
    <a href="admin.php">Dashboard</a>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_comentarios.php">Comentarios</a>
    <a href="admin_apartamentos.php" class="active">Apartamentos</a>
    <a href="admin_backup.php">Backup / Restaurar</a>
    <a href="index.php">← Volver a la web</a>
    <a href="logout.php" class="salir">Cerrar sesión</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Apartamentos</h1>
      <div><strong><?php echo count($apartamentos); ?></strong> apartamentos</div>
    </div>

    <?php echo $mensaje; ?>

    <?php if (count($apartamentos) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Propietario</th>
          <th>Barrio</th>
          <th>Precio</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($apartamentos as $a): ?>
        <tr>
          <td><?php echo $a['id']; ?></td>
          <td><strong><?php echo htmlspecialchars($a['nombre']); ?></strong></td>
          <td><?php echo htmlspecialchars($a['propietario'] ?? 'Desconocido'); ?></td>
          <td><?php echo htmlspecialchars($a['barrio']); ?></td>
          <td>€<?php echo number_format($a['precio'], 2); ?></td>
          <td><span class="badge <?php echo $a['estado']; ?>"><?php echo $a['estado']; ?></span></td>
          <td><?php echo date('d/m/Y', strtotime($a['created_at'])); ?></td>
          <td>
            <div class="acciones">
              <?php if ($a['estado'] != 'aprobado'): ?>
                <a href="?id=<?php echo $a['id']; ?>&estado=aprobado" class="btn-aprobar">Aprobar</a>
              <?php endif; ?>
              <?php if ($a['estado'] != 'rechazado'): ?>
                <a href="?id=<?php echo $a['id']; ?>&estado=rechazado" class="btn-rechazar">Rechazar</a>
              <?php endif; ?>
              <a href="?id=<?php echo $a['id']; ?>&estado=pendiente" class="btn-pendiente">Pendiente</a>
              <a href="apartamento.php?id=<?php echo $a['id']; ?>" target="_blank" class="btn-ver">Ver</a>
              <a href="?eliminar=<?php echo $a['id']; ?>" class="btn-eliminar" onclick="return confirm('¿Eliminar apartamento?')">Eliminar</a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="vacio">
      <div class="icon">🏠</div>
      <p>No hay apartamentos registrados</p>
    </div>
    <?php endif; ?>
  </div>
</body>
</html>
