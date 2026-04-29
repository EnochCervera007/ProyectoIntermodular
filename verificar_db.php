<?php
require_once 'conexion.php';

// Ver todas las tablas y sus estados
echo "=== VERIFICANDO BASE DE DATOS ===<br><br>";

$tables = ['usuarios', 'apartamentos', 'reservas', 'reservas_eliminadas', 'comentarios', 'comentarios_eliminados'];

foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as total FROM $table");
    $row = $result->fetch_assoc();
    echo "- $table: " . $row['total'] . " registros<br>";
}

echo "<br>=== VERIFICANDO ÍNDICES ===<br><br>";

// Ver índices de cada tabla
foreach ($tables as $table) {
    echo "<b>$table:</b><br>";
    $result = $conn->query("SHOW INDEX FROM $table");
    while ($row = $result->fetch_assoc()) {
        echo "  - " . $row['Key_name'] . " (" . $row['Column_name'] . ")<br>";
    }
}

echo "<br>=== VERIFICANDO CLAVES FORÁNEAS ===<br><br>";

$result = $conn->query("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'apbarcelona' AND REFERENCED_TABLE_NAME IS NOT NULL");

while ($row = $result->fetch_assoc()) {
    echo "- " . $row['TABLE_NAME'] . "." . $row['COLUMN_NAME'] . " → " . $row['REFERENCED_TABLE_NAME'] . "." . $row['REFERENCED_COLUMN_NAME'] . "<br>";
}
?>