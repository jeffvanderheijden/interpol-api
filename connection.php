<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Database credentials
$servername = "localhost";
$username = "jeffinterpol";
$password = "4mkf?890W";
$dbname = "interpol-api";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}