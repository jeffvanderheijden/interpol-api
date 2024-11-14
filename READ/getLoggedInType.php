<?php

// Secure session settings
if (session_status() == PHP_SESSION_ACTIVE) {
    // Set session cookie parameters (if not already done in php.ini)
    if (ini_get("session.use_only_cookies") == 0) {
        ini_set("session.use_only_cookies", 1);
    }
}

function getLoggedInType() {
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['ingelogdAls'])) {
        return json_encode(['Not logged in']);  // Return a consistent error message
    } else {
        // Be cautious with the session data and avoid exposing sensitive details.
        return json_encode($_SESSION['ingelogdAls']);  // Return a structured, sanitized response
    }
}
