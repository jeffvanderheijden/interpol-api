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
        // Check if the request method is PUT
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Debug: Check if $_POST is populated correctly
            // Check the raw input data
            $rawData = file_get_contents("php://input");
            error_log("Raw PUT Data: " . $rawData);

            // Manually parse the input data
            parse_str($rawData, $_POST);
            error_log("Parsed POST Data: " . print_r($_POST, true));

            // // Now $_POST contains the form fields and $_FILES contains the file data
            if (isset($_POST['group_id'])) {
                // Call the updateGroup function, passing the connection and $_POST data
                echo updateGroup($conn);
            } else {
                echo json_encode(['error' => 'Group ID is required for updating.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request method. Only PUT is allowed.']);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Invalid route']);
        break;
}
