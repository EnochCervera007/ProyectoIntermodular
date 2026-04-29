<?php
require_once 'conexion.php';

$action = $_POST['action'] ?? '';
$comentario_id = intval($_POST['comentario_id'] ?? 0);
$apartamento_id = intval($_POST['apartamento_id'] ?? 1);
$admin_id = $_SESSION['user_id'] ?? 0;

if ($action === 'delete' && $comentario_id > 0) {
    // Obtener datos del comentario
    $result = $conn->query("SELECT * FROM comentarios WHERE id = $comentario_id");
    $comentario = $result->fetch_assoc();
    
    if ($comentario) {
        // Insertar en tabla de eliminados
        $sql = "INSERT INTO comentarios_eliminados (comentario_id_original, apartamento_id, user_id, user_name, rating, comentario, eliminado_por) VALUES (
            " . intval($comentario['id']) . ", 
            " . intval($comentario['apartamento_id']) . ", 
            " . intval($comentario['user_id']) . ", 
            '" . $conn->real_escape_string($comentario['user_name']) . "', 
            " . floatval($comentario['rating']) . ", 
            '" . $conn->real_escape_string($comentario['comentario']) . "', 
            $admin_id
        )";
        $conn->query($sql);
        
        // Eliminar de la tabla principal
        $conn->query("DELETE FROM comentarios WHERE id = $comentario_id");
    }
} elseif ($action === 'edit' && $comentario_id > 0) {
    $nuevo_comentario = $_POST['nuevo_comentario'];
    $conn->query("UPDATE comentarios SET comentario = '" . $conn->real_escape_string($nuevo_comentario) . "' WHERE id = $comentario_id");
}

header("Location: apartamento.php?id=$apartamento_id");
exit;
?>