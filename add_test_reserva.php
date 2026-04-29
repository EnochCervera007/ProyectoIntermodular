<?php
require_once 'conexion.php';
$user_id = 1;
$apartamento_id = 1;
$checkin = '2026-05-01';
$checkout = '2026-05-03';
$noches = 2;
$total = 95 * $noches; // use price placeholder
$estado = 'pendiente';
$stmt = $conn->prepare('INSERT INTO reservas (user_id, apartamento_id, checkin, checkout, noches, total, estado, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('issidds', $user_id, $apartamento_id, $checkin, $checkout, $noches, $total, $estado);
if($stmt->execute()){
    echo "Reservation added";
} else {
    echo "Error: " . $stmt->error;
}
?>