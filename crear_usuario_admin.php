<?php
require_once 'conexion.php';

// Ver usuarios actuales
$result = $conn->query("SELECT id, nombre, email FROM usuarios");
echo "Usuarios actuales:<br>";
while ($row = $result->fetch_assoc()) {
    echo "- ID: " . $row['id'] . " | " . $row['nombre'] . " | " . $row['email'] . "<br>";
}

// Crear usuario ADMIN si no existe
$check = $conn->query("SELECT id FROM usuarios WHERE nombre = 'ADMIN'");
if ($check->num_rows == 0) {
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO usuarios (nombre, email, password, admin) VALUES ('ADMIN', 'admin@apbarcelona.com', '$password', 1)");
    echo "<br>Usuario ADMIN creado (password: admin123)";
} else {
    $conn->query("UPDATE usuarios SET admin = 1 WHERE nombre = 'ADMIN'");
    echo "<br>ADMIN actualizado como admin";
}
?>