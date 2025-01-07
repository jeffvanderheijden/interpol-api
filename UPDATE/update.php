<?php

// Include the groups functions file
include 'UPDATE/updateGroup.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

// Handle the request based on the route
switch ($route) {
    case '/api/update-group':
        // Make sure to parse the PUT data and call the updateGroup function
        $params = parsePutData(); // Parse the PUT data
        if ($params) {
            echo updateGroup($conn, $params);
        } else {
            echo json_encode(['error' => 'Invalid or missing data.']);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}
