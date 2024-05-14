<?php

require 'config.php';

// Handles incoming POST request data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check if all required POST variables are set
	if (isset($_POST["api_key"], $_POST["location"], $_POST["temperature"], $_POST["humidity"], $_POST["heat_index"])) {
		$api_key = escape_data($_POST["api_key"]);

		if ($api_key === PROJECT_API_KEY) {
			$location = escape_data($_POST["location"]);
			$temperature = escape_data($_POST["temperature"]);
			$humidity = escape_data($_POST["humidity"]);
			$heat_index = escape_data($_POST["heat_index"]);

			// Use prepared statements to prevent SQL injection
			$stmt = $db->prepare("INSERT INTO sensor_data (location, temperature, humidity, heat_index, created_date) VALUES (?, ?, ?, ?, ?)");
			$current_date = date("Y-m-d H:i:s");

			if ($stmt) {
				$stmt->bind_param("sssss", $location, $temperature, $humidity, $heat_index, $current_date);

				if ($stmt->execute()) {
					echo "OK. INSERT ID: " . $stmt->insert_id;
				} else {
					echo "Execute Error: " . $stmt->error;
				}

				$stmt->close();
			} else {
				echo "Prepare Error: " . $db->error;
			}
		} else {
			echo "Wrong API Key";
		}
	} else {
		echo "Missing required POST parameters";
	}
} else {
	echo "No HTTP POST request found";
}

// Function to escape data for safe SQL queries
function escape_data($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
