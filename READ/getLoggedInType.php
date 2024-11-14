<?php

// Secure session settings
if (session_status() == PHP_SESSION_ACTIVE) {
    // Set session cookie parameters (if not already done in php.ini)
    if (ini_get("session.use_only_cookies") == 0) {
        ini_set("session.use_only_cookies", 1);
    }

    $params = session_get_cookie_params();
    session_set_cookie_params(
        $params["lifetime"],
        $params["path"],
        $params["domain"],
        isset($_SERVER["HTTPS"]),
        true  // Only send the cookie over secure connections
    );
}

function getLoggedInType() {
    if (!isset($_SESSION['ingelogdAls'])) {
        return json_encode(['error' => 'Not logged in']);  // Return a consistent error message
    } else {
        // Be cautious with the session data and avoid exposing sensitive details.
        return json_encode($_SESSION['ingelogdAls']);  
    }
}
