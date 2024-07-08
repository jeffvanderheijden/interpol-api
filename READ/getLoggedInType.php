<?php
function getLoggedInType() {
   // Endpoint to check session and return session data
    echo var_dump($_SESSION['ingelogdAls']);
    die();
    if (isset($_SESSION['ingelogdAls'])) {
        $userData = ['inlogDocent' => $_SESSION['inlogDocent']];

        echo json_encode($userData);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Not logged in as teacher']);
    }
}