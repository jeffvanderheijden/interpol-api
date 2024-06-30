<?php

// Include the groups functions file
include 'READ/getGroups.php';
// Include the students functions file
include 'READ/getStudents.php';
// Include the challenges functions file
include 'READ/getChallenges.php';

// Get the request URI and query string
$request_uri = $_SERVER['REQUEST_URI'];
$query_string = $_SERVER['QUERY_STRING'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

switch ($route) {
    case '/api':
        echo json_encode(['welcome' => 'Available routes: /api/groups, /api/group, /api/students-by-group, /api/student, /api/challenges, /api/challenges-by-group']);
        break;
    // GROUPS
    case '/api/groups':
        getGroups($conn);
        break;
    case '/api/group':
        getGroupsById($conn, $_GET);
        break;
    // STUDENTS
    case '/api/students-by-group':
        getStudentsByGroup($conn, $_GET);
        break;
    case '/api/student':
        getStudentById($conn, $_GET);
        break;
    // CHALLENGES
    case '/api/challenges':
        getChallenges($conn);
        break;
    case '/api/challenges-by-group':
        getChallengesByGroupId($conn, $_GET);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}