<?php
require_once 'conexion.php';

// Crear tabla de eliminados si no existe
$sql = "CREATE TABLE IF NOT EXISTS reservas_eliminadas (
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
)";
$conn->query($sql);

// Procesar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva'])) {
    $reserva_id = intval($_POST['reserva_id']);
    $user_id = intval($_POST['user_id']);
    
    // Obtener datos de la reserva antes de eliminar
    $stmt = $conn->prepare("SELECT * FROM reservas WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $reserva_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($reserva = $result->fetch_assoc()) {
        // Insertar en tabla de eliminados
        $sql2 = "INSERT INTO reservas_eliminadas (reserva_id, user_id, apartamento_id, checkin, checkout, noches, total, estado, eliminado_por) VALUES (
            " . intval($reserva['id']) . ", 
            " . intval($reserva['user_id']) . ", 
            " . intval($reserva['apartamento_id']) . ", 
            '" . $conn->real_escape_string($reserva['checkin']) . "', 
            '" . $conn->real_escape_string($reserva['checkout']) . "', 
            " . intval($reserva['noches']) . ", 
            " . floatval($reserva['total']) . ", 
            '" . $conn->real_escape_string($reserva['estado']) . "', 
            " . intval($user_id) . "
        )";
        $conn->query($sql2);
        
        // Eliminar la reserva
        $stmt3 = $conn->prepare("DELETE FROM reservas WHERE id = ? AND user_id = ?");
        $stmt3->bind_param("ii", $reserva_id, $user_id);
        $stmt3->execute();
        
        header("Location: mis_reservas.php");
        exit;
    }
}
?>