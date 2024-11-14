<?php

// Include the delete groups functions file
include 'DELETE/deleteGroup.php';

// Start the session if not already started (for role checks or authorization)
session_start();

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
    // Example authorization check: only allow deletion if user is a teacher or admin
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'teacher']);
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

    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}
