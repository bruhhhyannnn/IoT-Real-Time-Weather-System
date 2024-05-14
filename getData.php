<?php
require 'config.php';

$sql = "SELECT * FROM sensor_data WHERE 1 ORDER BY id DESC";
$result = $db->query($sql);
if (!$result) {
  { echo "Error: " . $sql . "<br>" . $db->error; }
}

$row = $result->fetch_assoc();
echo json_encode($row);
?>