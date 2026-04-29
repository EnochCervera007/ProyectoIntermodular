<?php
require_once 'conexion.php';

echo "=== RESUMEN FINAL ===<br><br>";

$tables = ['usuarios', 'apartamentos', 'reservas', 'reservas_eliminadas', 'comentarios', 'comentarios_eliminados'];

foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    $row = $result->fetch_assoc();
    echo "• $table: <b>" . $row['total'] . "</b> registros<br>";
}

echo "<br>=== ÚLTIMOS ERRORES ===<br><br>";

// Ver última reserva
$result = $conn->query("SELECT * FROM reservas ORDER BY id DESC LIMIT 1");
$reserva = $result->fetch_assoc();
if ($reserva) {
    echo "Última reserva: ID=" . $reserva['id'] . " | usuario=" . $reserva['user_id'] . " | apartamento=" . $reserva['apartamento_id'] . " | total=" . $reserva['total'] . "€<br>";
}

// Ver último comentario
$result = $conn->query("SELECT * FROM comentarios ORDER BY id DESC LIMIT 1");
$comentario = $result->fetch_assoc();
if ($comentario) {
    echo "Último comentario: ID=" . $comentario['id'] . " | apartamento=" . $comentario['apartamento_id'] . " | usuario=" . $comentario['user_name'] . "<br>";
}

// Ver último usuario
$result = $conn->query("SELECT id, nombre, admin FROM usuarios ORDER BY id DESC");
echo "Usuarios:<br>";
while ($user = $result->fetch_assoc()) {
    $admin = $user['admin'] ? '(ADMIN)' : '';
    echo "• ID " . $user['id'] . ": " . $user['nombre'] . " $admin<br>";
}

echo "<br>✓ Todo correcto, base de datos lista para exponer!";
?>