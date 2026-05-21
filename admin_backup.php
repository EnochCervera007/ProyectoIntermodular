<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['user_id']) || !($_SESSION['admin'] ?? 0)) {
    header("Location: login.php");
    exit;
}

$mensaje = '';
$backup_dir = 'C:\backup';
$log_file = "$backup_dir\\logs\\backup.log";

// Hacer backup
if (isset($_POST['hacer_backup'])) {
    $bat_path = __DIR__ . '\sistema_backup\backup.bat';
    if (file_exists($bat_path)) {
        $output = shell_exec("\"$bat_path\" 2>&1");
        $mensaje = '<div class="msg ok">Backup ejecutado. Revisa el log para más detalles.</div>';
    } else {
        $mensaje = '<div class="msg error">No se encuentra backup.bat en sistema_backup/</div>';
    }
}

// Restaurar
if (isset($_POST['restaurar']) && isset($_POST['archivo_db'])) {
    $db_file = basename($_POST['archivo_db']);
    $db_path = "$backup_dir\\db\\$db_file";
    $mysql_bin = 'C:\xampp\mysql\bin';

    if (file_exists($db_path)) {
        $cmd = "\"$mysql_bin\\mysql.exe\" --protocol=TCP --host=127.0.0.1 --port=3306 -u root -e \"DROP DATABASE IF EXISTS apbarcelona;\" 2>&1";
        shell_exec($cmd);
        $cmd = "\"$mysql_bin\\mysql.exe\" --protocol=TCP --host=127.0.0.1 --port=3306 -u root -e \"CREATE DATABASE apbarcelona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\" 2>&1";
        shell_exec($cmd);
        $cmd = "\"$mysql_bin\\mysql.exe\" --protocol=TCP --host=127.0.0.1 --port=3306 -u root apbarcelona < \"$db_path\" 2>&1";
        shell_exec($cmd);
        $mensaje = '<div class="msg ok">Base de datos restaurada desde: ' . htmlspecialchars($db_file) . '</div>';
    } else {
        $mensaje = '<div class="msg error">Archivo no encontrado: ' . htmlspecialchars($db_file) . '</div>';
    }
}

// Leer backups disponibles
$backups_db = [];
if (is_dir("$backup_dir\\db")) {
    $files = glob("$backup_dir\\db\\*.sql");
    rsort($files);
    foreach ($files as $f) {
        $backups_db[] = [
            'name' => basename($f),
            'size' => filesize($f),
            'date' => date('d/m/Y H:i', filemtime($f))
        ];
    }
}

// Leer log
$log_content = '';
if (file_exists($log_file)) {
    $log_content = file_get_contents($log_file);
}

$backup_dir_files = 'C:\backup\files';
$backups_files = [];
if (is_dir($backup_dir_files)) {
    $files = glob("$backup_dir_files\\*.zip");
    rsort($files);
    foreach ($files as $f) {
        $backups_files[] = [
            'name' => basename($f),
            'size' => filesize($f),
            'date' => date('d/m/Y H:i', filemtime($f))
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backup – Admin ApBarcelona</title>
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
    .msg.info { background: #e3f2fd; color: #1565c0; border: 1px solid #90caf9; }

    .card { background: #fff; border-radius: 12px; padding: 28px; margin-bottom: 24px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #eee; }
    .card h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 16px; }
    .card p { color: #666; font-size: 0.9rem; margin-bottom: 16px; line-height: 1.6; }

    .btn-backup {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 14px 28px; background: #27ae60; color: #fff; border: none;
      border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 600; cursor: pointer;
      transition: all 0.2s;
    }
    .btn-backup:hover { background: #219a52; transform: translateY(-2px); }
    .btn-restore {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; background: #e67e22; color: #fff; border: none;
      border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 600; cursor: pointer;
      transition: all 0.2s;
    }
    .btn-restore:hover { background: #d35400; }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 14px; font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #eee; }
    td { padding: 12px 14px; font-size: 0.85rem; color: #333; border-bottom: 1px solid #f5f5f5; }
    tr:hover td { background: #fafafa; }
    .size { color: #888; font-size: 0.8rem; }

    .log-box {
      background: #111; color: #0f0; padding: 20px; border-radius: 10px;
      font-family: 'Consolas', monospace; font-size: 0.8rem; line-height: 1.5;
      max-height: 300px; overflow-y: auto; white-space: pre-wrap;
    }
    .vacio { text-align: center; padding: 40px; color: #888; }
    .vacio .icon { font-size: 2.5rem; margin-bottom: 8px; }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Ap<span>Barcelona</span></h2>
    <a href="admin.php">Dashboard</a>
    <a href="admin_usuarios.php">Usuarios</a>
    <a href="admin_comentarios.php">Comentarios</a>
    <a href="admin_apartamentos.php">Apartamentos</a>
    <a href="admin_backup.php" class="active">Backup / Restaurar</a>
    <a href="index.php">← Volver a la web</a>
    <a href="logout.php" class="salir">Cerrar sesión</a>
  </div>

  <div class="main">
    <div class="topbar">
      <h1>Backup y Restauración</h1>
    </div>

    <?php echo $mensaje; ?>

    <div class="card">
      <h2>💾 Hacer copia de seguridad</h2>
      <p>Genera un backup completo de la base de datos y los archivos del proyecto. Los backups se guardan en <code>C:\backup\</code></p>
      <form method="POST">
        <button type="submit" name="hacer_backup" class="btn-backup" onclick="return confirm('¿Iniciar copia de seguridad ahora?')">🔥 Ejecutar backup</button>
      </form>
    </div>

    <div class="card">
      <h2>📦 Backups de base de datos disponibles</h2>
      <?php if (count($backups_db) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Archivo</th>
            <th>Tamaño</th>
            <th>Fecha</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($backups_db as $b): ?>
          <tr>
            <td><code><?php echo htmlspecialchars($b['name']); ?></code></td>
            <td class="size"><?php echo number_format($b['size'] / 1024, 1); ?> KB</td>
            <td><?php echo $b['date']; ?></td>
            <td>
              <form method="POST" onsubmit="return confirm('¿Restaurar BD desde <?php echo htmlspecialchars($b['name']); ?>? Se perderán los datos actuales.')">
                <input type="hidden" name="archivo_db" value="<?php echo htmlspecialchars($b['name']); ?>">
                <button type="submit" name="restaurar" class="btn-restore">Restaurar</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
      <div class="vacio">
        <div class="icon">📭</div>
        <p>No hay backups disponibles. Ejecuta el backup primero.</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>📁 Backups de archivos disponibles</h2>
      <?php if (count($backups_files) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Archivo</th>
            <th>Tamaño</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($backups_files as $b): ?>
          <tr>
            <td><code><?php echo htmlspecialchars($b['name']); ?></code></td>
            <td class="size"><?php echo number_format($b['size'] / 1024, 1); ?> KB</td>
            <td><?php echo $b['date']; ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
      <div class="vacio">
        <div class="icon">📁</div>
        <p>No hay backups de archivos todavía.</p>
      </div>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>📋 Último log de backup</h2>
      <?php if ($log_content): ?>
      <div class="log-box"><?php echo htmlspecialchars($log_content); ?></div>
      <?php else: ?>
      <p style="color:#888;">No hay logs disponibles.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
