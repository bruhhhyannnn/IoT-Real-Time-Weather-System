<?php
require 'config.php';

$sql = "SELECT * FROM sensor_data ORDER BY id DESC LIMIT 30";
$result = $db->query($sql);

$data = [];
if ($result) {
	while ($row = mysqli_fetch_assoc($result)) {
		$row['created_date'] = date("Y-m-d h:i:s A", strtotime($row['created_date']));
		$data[] = $row;
	}
}

header('Content-Type: application/json');
echo json_encode($data);
