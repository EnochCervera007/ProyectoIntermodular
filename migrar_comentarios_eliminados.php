<?php
require_once 'conexion.php';

// Crear tabla de comentarios eliminados si no existe
$sql = "CREATE TABLE IF NOT EXISTS comentarios_eliminados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comentario_id_original INT NOT NULL,
    apartamento_id INT NOT NULL,
    user_id INT,
    user_name VARCHAR(100),
    rating DECIMAL(2,1),
    comentario TEXT,
    eliminado_por INT,
    fecha_eliminacion DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Insertar comentario eliminado desde la tabla principal
$sql2 = "INSERT INTO comentarios_eliminados (comentario_id_original, apartamento_id, user_id, user_name, rating, comentario, eliminado_por)
        SELECT c.id, c.apartamento_id, c.user_id, c.user_name, c.rating, c.comentario, ?
        FROM comentarios c";
$stmt = $conn->prepare($sql2);
$admin_id = $_SESSION['user_id'] ?? 0;
$stmt->bind_param("i", $admin_id);
$stmt->execute();

echo "Comentarios eliminados guardados en la nueva tabla";
?>