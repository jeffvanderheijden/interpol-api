<?php
// Database credentials
// $servername = "localhost";
// $username = "jeffinterpol";
// $password = "4mkf?890W";
// $dbname = "interpol-api";

$servername = "localhost";
$username = "interpolapi";
$password = "25641$fk@21mdQ";
$dbname = "interpol-api";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}