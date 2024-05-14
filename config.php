<?php
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'id21975726_pallas_sensor');
define('DB_PASSWORD', 'Pallas@123');
define('DB_NAME', 'id21975726_pallas_sensor_database');

// LOCAL
// define('DB_HOST', 'localhost');
// define('DB_USERNAME', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'sensor_table');

// define('POST_DATA_URL', 'https://vpallas.000webhostapp.com/writeData.php');

// PROJECT_API_KEY is the exact duplicate of, PROJECT_API_KEY in NodeMCU sketch file
// Both values must be same
define('PROJECT_API_KEY', 'vintar_pallas');

// Set timezone to the Philippines
date_default_timezone_set('Asia/Manila');

// Connect with the database 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Display error if failed to connect 
if ($db->connect_errno) {
    echo "Connection to database is failed: " . $db->connect_error;
    exit();
}
