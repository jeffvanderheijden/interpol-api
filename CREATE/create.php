<?php

// Include the challenges functions file
include 'CREATE/createChallenges.php';

// Get the request URI and query string
$request_uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api/create-challenges':
        createChallengesPerGroup($conn, $_GET);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}