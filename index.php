<?php

// ==========================================
// DELETE THIS AFTER DEPLOYMENT !!!!!!!!!
// ==========================================

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

header('Content-Type: application/json');

// Allow from any origin
header("Access-Control-Allow-Origin: .interpol.sd-lab.nl");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Send preflight response
    http_response_code(204);
    exit;
}

// ==========================================
// END OF DELETE AFTER DEPLOYMENT !!!!!!!!!
// ==========================================

// Include the database connection file
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        include 'READ/read.php';
        break;
    case 'POST':
        include 'CREATE/create.php';
        break;
    // case 'PUT':
    //     include 'put.php';
    //     break;
    // case 'DELETE':
    //     include 'delete.php';
    //     break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

$conn->close();