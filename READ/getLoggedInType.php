<?php

function getLoggedInType() {
    if (!isset($_SESSION['ingelogdAls'])) {
        return json_encode(['error' => 'Not logged in']);  // Return a consistent error message
    } else {
        // Be cautious with the session data and avoid exposing sensitive details.
        return json_encode($_SESSION['ingelogdAls']);  
    }
}
