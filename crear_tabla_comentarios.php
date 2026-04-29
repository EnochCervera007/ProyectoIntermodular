<?php
require_once 'conexion.php';

$sql = "CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apartamento_id INT NOT NULL,
    user_id INT NOT NULL,
    user_name VARCHAR(100),
    rating DECIMAL(2,1),
    comentario TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);
?>