<?php
session_start();

// Include the delete groups functions file
include 'DELETE/deleteGroup.php';
include 'DELETE/deleteStudent.php';

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Parse the URI to get the route
$route = parse_url($request_uri, PHP_URL_PATH);

// Function to sanitize and validate group_id
function sanitize_group_id($id) {
    // Ensure group_id is an integer and sanitize
    return filter_var($id, FILTER_SANITIZE_NUMBER_INT);
}

// Check if user is authorized to delete groups (e.g., check if the user is a teacher or admin)
function isAuthorizedToDelete() {
    // Only allow deletion if ingelogdAls DOCENT
    return isset($_SESSION['ingelogdAls']) && $_SESSION['ingelogdAls'] === 'DOCENT';
}

switch ($route) {
    // GROUPS
    case '/api/remove-group':
        // Check if the user is authorized
        if (!isAuthorizedToDelete()) {
            echo json_encode(['error' => 'Unauthorized access']);
            break;
        }

        // Check if group_id is set and sanitize it
        if (isset($_GET['group_id'])) {
            $group_id = sanitize_group_id($_GET['group_id']);
            
            // Check if group_id is valid
            if ($group_id <= 0) {
                echo json_encode(['error' => 'Invalid group_id']);
                break;
            }

            // Prepare data for deletion
            $data = ['group_id' => $group_id];
            echo deleteGroup($conn, $data); // Call the delete function with sanitized group_id
        } else {
            echo json_encode(['error' => 'Missing group_id parameter']);
        }
        break;

        case '/api/remove-student':
            // Check if the user is authorized
            if (!isAuthorizedToDelete()) {
                echo json_encode(['error' => 'Unauthorized access']);
                break;
            }
    
            // Check if group_id is set and sanitize it
            if (isset($_GET['id'])) {
                $student_id = sanitize_group_id($_GET['id']);
                
                // Check if group_id is valid
                if ($student_id <= 0) {
                    echo json_encode(['error' => 'Invalid student_id']);
                    break;
                }
    
                // Prepare data for deletion
                $data = ['student_id' => $student_id];
                echo deleteStudent($conn, $data); // Call the delete function with sanitized student_id
            } else {
                echo json_encode(['error' => 'Missing student_id parameter']);
            }
            break;

    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}
