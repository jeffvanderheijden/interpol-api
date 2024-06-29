<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

header('Content-Type: application/json');

// Include the database connection file
include 'connection.php';
// Include the groups functions file
include 'READ/getGroups.php';
// Include the students functions file
include 'READ/getStudents.php';

// Get the request URI and query string
$request_uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

// Handle different routes
switch ($route) {
    // GROUPS
    case '/api/groups':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getGroups($conn);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    case '/api/group':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getGroupsById($conn, $_GET);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    // STUDENTS
    case '/api/students-by-group':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getStudentsByGroup($conn, $_GET);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    case '/api/student':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            getStudentById($conn, $_GET);
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}

$conn->close();