<?php 
header('Content-Type: application/json');

// ============================
// Destroy session cookie, logs user out
// ============================

// Unset all session variables
$_SESSION = array();

function destroySession() {
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Return response
    return json_encode(['message' => 'Logged out successfully']);
}