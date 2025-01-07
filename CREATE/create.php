<?php

// Include the challenges functions file
include 'CREATE/createSession.php';
include 'CREATE/createChallenges.php';
include 'CREATE/createGroup.php';
include 'CREATE/destroySession.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api/create-session':
        echo createSession($_POST);
        break;
    case '/api/logout':
        echo destroySession();
        break;
    case '/api/create-challenges':
        echo createChallengesPerGroup($conn, $_POST);
        break;
    case '/api/create-team':
        echo createGroup($conn, $_POST);
        break;
    case '/api/set-challenge-points':
        echo setChallengePoints($conn, $_POST);
        break;
    
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}