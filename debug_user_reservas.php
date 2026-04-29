<?php
require_once 'conexion.php';
$userId = 1;
$res = $conn->query("SELECT * FROM reservas WHERE user_id = $userId");
if(!$res){ echo json_encode(['error'=>$conn->error]); exit; }
$rows=[]; while($row=$res->fetch_assoc()){ $rows[]=$row; }
echo json_encode($rows);
?>