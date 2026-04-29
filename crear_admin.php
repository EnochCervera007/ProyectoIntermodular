<?php
require_once 'conexion.php';

// Crear usuario ADMIN con email conocido
$password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT INTO usuarios (nombre, email, password, admin) VALUES ('ADMIN', 'admin@apbarcelona.com', '$password', 1) ON DUPLICATE KEY UPDATE nombre='ADMIN', email='admin@apbarcelona.com', admin=1");

echo "ADMIN creado/actualizado<br>";
echo "Email: admin@apbarcelona.com<br>";
echo "Password: admin123";
?>