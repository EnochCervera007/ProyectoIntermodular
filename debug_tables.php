<?php
require_once 'conexion.php';
$res = $conn->query('SHOW TABLES');
if(!$res){ echo json_encode(['error'=>$conn->error]); exit; }
$tables=[]; while($row=$res->fetch_array()){ $tables[]=$row[0]; }
echo json_encode($tables);
?>