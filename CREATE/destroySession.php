<?php 
header('Content-Type: application/json');

// ============================
// Destroy session cookie, logs user out
// ============================

// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

function destroySession($_POST) {
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
    echo json_encode(['message' => 'Logged out successfully']);
}