<?php
header('Content-Type: application/json');

// Include the database connection file
include 'connection.php';
// Include the groups functions file
include 'READ/getGroups.php';

// Get the request URI and query string
$request_uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];

// Parse the URI to get the route
$route = strtok($request_uri, '?');

// Handle different routes
switch ($route) {
    case '/api/groups':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getGroups($conn);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    case '/api/user':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getGroupsById($conn, $_GET);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}

$conn->close();