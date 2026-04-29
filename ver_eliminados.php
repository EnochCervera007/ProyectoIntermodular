<?php
require_once 'conexion.php';
$result = $conn->query("SELECT * FROM comentarios_eliminados");
if ($result) {
    echo "Tabla comentarios_eliminados:<br>";
    while ($row = $result->fetch_assoc()) {
        echo "- ID: " . $row['id'] . " | Usuario: " . $row['user_name'] . " | Comentario: " . substr($row['comentario'], 0, 30) . "... | Eliminado por: " . $row['eliminado_por'] . "<br>";
    }
} else {
    echo "Error: " . $conn->error;
}
?>