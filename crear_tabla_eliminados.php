<?php
require_once 'conexion.php';

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

if ($conn->query($sql)) {
    echo "Tabla creada correctamente";
} else {
    echo "Error: " . $conn->error;
}
?>