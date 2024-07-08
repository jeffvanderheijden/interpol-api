<?php

// Include the challenges functions file
include 'CREATE/createSession.php';
include 'CREATE/createChallenges.php';
include 'CREATE/createTeam.php';
include 'CREATE/destroySession.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    // LOGIN 
    case '/api/create-session':
        echo createSession($_POST);
        break;
    case '/api/create-challenges':
        echo createChallengesPerGroup($conn, $_POST);
        break;
    case '/api/create-team':
        echo createTeam($conn, $_POST);
        break;
    case '/api/logout':
        echo destroySession();
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}