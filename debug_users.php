<?php
require_once 'conexion.php';
$res = $conn->query('SELECT * FROM users LIMIT 5');
if(!$res){ echo json_encode(['error'=>$conn->error]); exit; }
$rows=[]; while($row=$res->fetch_assoc()){ $rows[]=$row; }
echo json_encode($rows);
?>