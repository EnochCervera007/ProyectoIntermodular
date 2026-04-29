<?php
require_once 'conexion.php';
$userId = 1;
$newPass = 'test123';
$hash = password_hash($newPass, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE usuarios SET password = ? WHERE id = ?');
$stmt->bind_param('si', $hash, $userId);
if($stmt->execute()){
    echo "Password updated for user $userId";
} else {
    echo "Error: " . $stmt->error;
}
?>