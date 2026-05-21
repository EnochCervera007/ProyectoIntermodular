<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || !($_SESSION['admin'] ?? 0)) {
    header("Location: login.php");
    exit;
}

$mensaje = '';

// Crear usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $admin = isset($_POST['admin']) ? 1 : 0;

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $mensaje = '<div class="msg error">El email ya está registrado</div>';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, admin, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssi", $nombre, $email, $hash, $admin);
            if ($stmt->execute()) {
                $mensaje = '<div class="msg ok">Usuario creado correctamente</div>';
            } else {
                $mensaje = '<div class="msg error">Error al crear usuario</div>';
            }
        }
    } else {
        $mensaje = '<div class="msg error">Todos los campos son obligatorios</div>';
    }
}

// Eliminar usuario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $mensaje = '<div class="msg ok">Usuario eliminado</div>';
    } else {
        $mensaje = '<div class="msg error">No puedes eliminarte a ti mismo</div>';
    }
}

// Hacer admin / quitar admin
if (isset($_GET['toggle_admin'])) {
    $id = intval($_GET['toggle_admin']);
    if ($id != $_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE usuarios SET admin = NOT admin WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

$buscar = $_GET['buscar'] ?? '';
if ($buscar) {
    $like = "%$buscar%";
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre LIKE ? OR email LIKE ? ORDER BY created_at DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM usuarios ORDER BY created_at DESC");
}
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios – Admin ApBarcelona</title>
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

    .bar {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
    }
    .bar form { display: flex; gap: 8px; }
    .bar input[type="text"] {
      padding: 10px 14px; border-radius: 8px; border: 1px solid #ddd; font-size: 0.9rem; width: 260px;
    }
    .bar button, .btn {
      padding: 10px 20px; border-radius: 8px; border: none;
      font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 600; cursor: pointer;
    }
    .btn-primary { background: #C8102E; color: #fff; }
    .btn-primary:hover { background: #9e0c24; }
    .btn-secondary { background: #fff; color: #111; border: 1px solid #ddd; }
    .btn-secondary:hover { background: #f5f5f5; }
    .btn-danger { background: #c62828; color: #fff; }
    .btn-danger:hover { background: #9e0c24; }

    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
    th { background: #fafafa; text-align: left; padding: 14px 16px; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #eee; }
    td { padding: 14px 16px; font-size: 0.9rem; color: #333; border-bottom: 1px solid #f5f5f5; }
    tr:hover td { background: #fafafa; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .badge.admin { background: #fff3e0; color: #e65100; }
    .badge.user { background: #e3f2fd; color: #1565c0; }
    .acciones { display: flex; gap: 6px; }
    .acciones a, .acciones button { padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; text-decoration: none; cursor: pointer; border: none; font-family: 'DM Sans', sans-serif; font-weight: 500; }

    .modal-overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
      z-index: 1000; justify-content: center; align-items: center;
    }
    .modal-overlay.show { display: flex; }
    .modal {
      background: #fff; border-radius: 16px; padding: 36px; width: 100%; max-width: 440px;
      box-shadow: 0 24px 48px rgba(0,0,0,0.2);
    }
    .modal h2 { font-family: 'Playfair Display', serif; font-size: 1.4rem; margin-bottom: 20px; }
    .modal .form-group { margin-bottom: 16px; }
    .modal label { display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 6px; }
    .modal input[type="text"], .modal input[type="email"], .modal input[type="password"] {
      width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid #ddd; font-size: 0.9rem;
    }
    .modal input:focus { outline: none; border-color: #C8102E; box-shadow: 0 0 0 3px rgba(200,16,46,0.1); }
    .modal .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
    .checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .checkbox-label input[type="checkbox"] { width: 18px; height: 18px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Ap<span>Barcelona</span></h2>
    <a href="admin.php">Dashboard</a>
    <a href="admin_usuarios.php" class="active">Usuarios</a>
    <a href="admin_comentarios.php">Comentarios</a>
    <a href="admin_apartamentos.php">Apartamentos</a>
    <a href="admin_backup.php">Backup / Restaurar</a>
    <a href="index.php">← Volver a la web</a>
    <a href="logout.php" class="salir">Cerrar sesión</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Usuarios</h1>
      <div><strong><?php echo count($usuarios); ?></strong> usuarios</div>
    </div>

    <?php echo $mensaje; ?>

    <div class="bar">
      <form method="GET">
        <input type="text" name="buscar" placeholder="Buscar por nombre o email..." value="<?php echo htmlspecialchars($buscar); ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <?php if ($buscar): ?><a href="admin_usuarios.php" class="btn btn-secondary">Limpiar</a><?php endif; ?>
      </form>
      <button class="btn btn-primary" onclick="document.getElementById('modalCrear').classList.add('show')">+ Nuevo usuario</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Rol</th>
          <th>Registro</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
          <td><?php echo $u['id']; ?></td>
          <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
          <td><?php echo htmlspecialchars($u['email']); ?></td>
          <td><?php echo htmlspecialchars($u['telefono'] ?? '-'); ?></td>
          <td>
            <?php if ($u['admin']): ?>
              <span class="badge admin">Admin</span>
            <?php else: ?>
              <span class="badge user">Usuario</span>
            <?php endif; ?>
          </td>
          <td><?php echo date('d/m/Y', strtotime($u['created_at'])); ?></td>
          <td>
            <div class="acciones">
              <?php if ($u['id'] != $_SESSION['user_id']): ?>
                <a href="?toggle_admin=<?php echo $u['id']; ?>" class="btn btn-secondary" style="padding:4px 10px;font-size:0.75rem;">
                  <?php echo $u['admin'] ? 'Quitar admin' : 'Hacer admin'; ?>
                </a>
                <a href="?eliminar=<?php echo $u['id']; ?>" class="btn btn-danger" style="padding:4px 10px;font-size:0.75rem;" onclick="return confirm('¿Eliminar a <?php echo htmlspecialchars(addslashes($u['nombre'])); ?>?')">Eliminar</a>
              <?php else: ?>
                <span style="color:#888;font-size:0.8rem;">(tú)</span>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($usuarios)): ?>
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#888;">No hay usuarios</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal crear usuario -->
  <div class="modal-overlay" id="modalCrear">
    <div class="modal">
      <h2>Crear nuevo usuario</h2>
      <form method="POST">
        <div class="form-group">
          <label for="nombre">Nombre completo</label>
          <input type="text" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" required minlength="6">
        </div>
        <div class="form-group">
          <label class="checkbox-label">
            <input type="checkbox" name="admin" value="1">
            Administrador
          </label>
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalCrear').classList.remove('show')">Cancelar</button>
          <button type="submit" name="crear" class="btn btn-primary">Crear usuario</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
