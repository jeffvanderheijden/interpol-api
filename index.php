<?php

// ==========================================
// ERROR LOGGING ============================
// ==========================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ==========================================
// ==========================================
// ==========================================

$allowedOrigins = [
    'https://admin.interpol.sd-lab.nl',
    'https://leaderboard.interpol.sd-lab.nl',
    'https://dashboard.interpol.sd-lab.nl',
    'https://html-css.interpol.sd-lab.nl',
    'https://kijk-op-de-wijk.interpol.sd-lab.nl',
    'https://creative-coding.interpol.sd-lab.nl',
    // Update with student projects 
    'https://088484.stu.sd-lab.nl',
    'https://cp-webshop.sd-lab.nl'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header('Content-Type: application/json');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}

// Include the database connection file
include 'connection.php';

$method = $_SERVER['REQUEST_METHOD'];

// Handle different request methods
switch ($method) {
    case 'GET':
        include 'READ/read.php';
        break;
    case 'POST':
        include 'CREATE/create.php';
        break;
    case 'PUT':
        include 'UPDATE/update.php';
        break;
    case 'DELETE':
        include 'DELETE/delete.php';
        break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

$conn->close();