<?php
require_once 'conexion.php';

$conn->query("CREATE TABLE IF NOT EXISTS reservas_eliminadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NOT NULL,
    user_id INT NOT NULL,
    apartamento_id INT NOT NULL,
    checkin DATE,
    checkout DATE,
    noches INT,
    total DECIMAL(10,2),
    estado VARCHAR(20),
    eliminado_por INT,
    fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $user_id = intval($_POST['user_id']);
    
    $stmt = $conn->prepare("SELECT * FROM reservas WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reserva_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($reserva = $result->fetch_assoc()) {
        $stmt2 = $conn->prepare("INSERT INTO reservas_eliminadas (reserva_id, user_id, apartamento_id, checkin, checkout, noches, total, estado, eliminado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiiisissi", $reserva['id'], $reserva['user_id'], $reserva['apartamento_id'], $reserva['checkin'], $reserva['checkout'], $reserva['noches'], $reserva['total'], $reserva['estado'], $user_id);
        $stmt2->execute();
        
        $stmt3 = $conn->prepare("DELETE FROM reservas WHERE id = ? AND user_id = ?");
        $stmt3->bind_param("ii", $reserva_id, $user_id);
        $stmt3->execute();
        
        header("Location: mis_reservas.php");
        exit;
    }
}
?>