<?php
require_once 'conexion.php';

session_start();

$action = $_POST['action'] ?? '';
$comentario_id = intval($_POST['comentario_id'] ?? 0);
$apartamento_id = intval($_POST['apartamento_id'] ?? 1);
$admin_id = $_SESSION['user_id'] ?? 0;

if ($action === 'delete' && $comentario_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM comentarios WHERE id = ?");
    $stmt->bind_param("i", $comentario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comentario = $result->fetch_assoc();
    
    if ($comentario) {
        $stmt2 = $conn->prepare("INSERT INTO comentarios_eliminados (comentario_id_original, apartamento_id, user_id, user_name, rating, comentario, eliminado_por) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiisdsi", $comentario['id'], $comentario['apartamento_id'], $comentario['user_id'], $comentario['user_name'], $comentario['rating'], $comentario['comentario'], $admin_id);
        $stmt2->execute();
        
        $stmt3 = $conn->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt3->bind_param("i", $comentario_id);
        $stmt3->execute();
    }
} elseif ($action === 'edit' && $comentario_id > 0) {
    $nuevo_comentario = $_POST['nuevo_comentario'];
    $stmt4 = $conn->prepare("UPDATE comentarios SET comentario = ? WHERE id = ?");
    $stmt4->bind_param("si", $nuevo_comentario, $comentario_id);
    $stmt4->execute();
}

header("Location: apartamento.php?id=$apartamento_id");
exit;
?>