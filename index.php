<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

header('Content-Type: application/json');

// Include the database connection file
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        include 'READ/read.php';
        break;
    // case 'POST':
    //     include 'post.php';
    //     break;
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