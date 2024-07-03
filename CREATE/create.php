<?php

// Include the challenges functions file
include 'CREATE/createChallenges.php';
include 'CREATE/createImages.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api/create-challenges':
        echo createChallengesPerGroup($conn, $_POST);
        break;
    case '/api/upload-team-image':
        echo createTeamImage($conn, $_POST);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}