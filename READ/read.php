<?php

// Include the groups functions file
include 'READ/getGroups.php';
// Include the students functions file
include 'READ/getStudents.php';
// Include the challenges functions file
include 'READ/getChallenges.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api':
        echo json_encode(['welcome' => 'Available routes: /api/groups, /api/group, /api/students-by-group, /api/student, /api/challenges, /api/challenges-by-group']);
        break;
    // GROUPS
    case '/api/groups':
        echo getGroups($conn);
        break;
    case '/api/group':
        echo getGroupsById($conn, $_GET);
        break;
    // STUDENTS
    case '/api/students-by-group':
        echo getStudentsByGroup($conn, $_GET);
        break;
    case '/api/student':
        echo getStudentById($conn, $_GET);
        break;
    // CHALLENGES
    case '/api/challenges':
        echo getChallenges($conn);
        break;
    case '/api/challenges-by-group':
        echo getChallengesByGroupId($conn, $_GET);
        break;
    case '/api/challenge-by-id':
        echo getChallengeById($conn, $_GET);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}