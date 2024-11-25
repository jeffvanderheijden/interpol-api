<?php

// Include the groups functions file
include 'READ/getGroups.php';
// Include the students functions file
include 'READ/getStudents.php';
// Include the get student and group file
include 'READ/getStudent.php';
// Include the challenges functions file
include 'READ/getChallenges.php';
// Check if user is teacher or student
include 'READ/getLoggedInType.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

// Function to validate and sanitize GET parameters
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Switch based on the route
switch ($route) {
    case '/api':
        echo json_encode(['welcome' => "Available routes: TODO: add available routes"]);
        break;

    // GROUPS
    case '/api/groups':
        echo getGroups($conn);
        break;
    case '/api/group':
        if (isset($_GET['id'])) {
            $group_id = sanitize_input($_GET['id']);
            echo getGroupById($conn, ['id' => $group_id]);
        } else {
            echo json_encode(['error' => 'ID parameter missing']);
        }
        break;
    case '/api/group-points':
        if (isset($_GET['id'])) {
            $group_id = sanitize_input($_GET['id']);
            echo getPointsByGroupId($conn, ['id' => $group_id]);
        } else {
            echo json_encode(['error' => 'ID parameter missing']);
        }
        break;
    case '/api/top-three-groups':
        echo getTopThreeGroups($conn);
        break;
    case '/api/groups-by-class':
        if (isset($_GET['class'])) {
            $class = sanitize_input($_GET['class']);
            echo getGroupsByClass($conn, ['class' => $class]);
        } else {
            echo json_encode(['error' => 'Class parameter missing']);
        }
        break;

    // STUDENTS
    case '/api/students-by-group':
        if (isset($_GET['id'])) {
            $group_id = sanitize_input($_GET['id']);
            echo getStudentsByGroup($conn, ['id' => $group_id]);
        } else {
            echo json_encode(['error' => $_GET['id']]);
        }
        break;
    case '/api/student':
        if (isset($_GET['id'])) {
            $student_id = sanitize_input($_GET['id']);
            echo getStudentById($conn, ['id' => $student_id]);
        } else {
            echo json_encode(['error' => 'ID parameter missing']);
        }
        break;
    case '/api/student-data':
        echo getStudent();
        break;
    case '/api/student-additional-data':
        if (isset($_GET['student_id'])) {
            $student_id = sanitize_input($_GET['student_id']);
            echo getAdditionalStudentData($conn, ['student_id' => $student_id]);
        } else {
            echo json_encode(['error' => 'Student ID parameter missing']);
        }
        break;

    // CHALLENGES
    case '/api/challenges':
        echo getChallenges($conn);
        break;
    case '/api/challenges-by-group':
        if (isset($_GET['group_id'])) {
            $group_id = sanitize_input($_GET['group_id']);
            echo getChallengesByGroupId($conn, ['group_id' => $group_id]);
        } else {
            echo json_encode(['error' => 'Group ID parameter missing']);
        }
        break;
    case '/api/challenge-by-id':
        if (isset($_GET['id'])) {
            $challenge_id = sanitize_input($_GET['id']);
            echo getChallengeById($conn, ['id' => $challenge_id]);
        } else {
            echo json_encode(['error' => 'ID parameter missing']);
        }
        break;

    // CHECK LOGGED IN TYPE (TEACHER OR STUDENT)
    case '/api/check-type':
        echo getLoggedInType();
        break;

    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}
