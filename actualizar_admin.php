<?php
require_once 'conexion.php';

// Actualizar usuarios
$conn->query("UPDATE usuarios SET admin = 0, nombre = 'Gerard' WHERE nombre = 'gerard'");
$conn->query("UPDATE usuarios SET admin = 1 WHERE nombre = 'ADMIN'");

// Verificar
$result = $conn->query("SELECT id, nombre, admin FROM usuarios");
echo "Usuarios actualizados:<br>";
while ($row = $result->fetch_assoc()) {
    echo "- " . $row['nombre'] . " (admin: " . $row['admin'] . ")<br>";
}
?>