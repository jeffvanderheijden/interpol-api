<?php
// Database credentials
$servername = "localhost";
$username = "interpol";
$password = "Bps_0t762";
$dbname = "interpol";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}