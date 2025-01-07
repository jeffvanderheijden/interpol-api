<?php

// Include the groups functions file
include 'UPDATE/updateGroup.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api/update-group':
        echo updateGroup($conn, $_PUT);
        break;
    
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}