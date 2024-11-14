<?php

// Include the delete groups functions file
include 'DELETE/deleteGroup.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    // GROUPS
    case '/api/remove-group':
        $data = ['group_id' => $_GET['group_id']]; // Extract group_id from URL
        echo deleteGroup($conn, $data);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}