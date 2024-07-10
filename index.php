<?php

// ==========================================
// ERROR LOGGING ============================
// ==========================================

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

// ==========================================
// ==========================================
// ==========================================

// $allowedOrigins = [
//     'https://admin.interpol.sd-lab.nl',
//     'https://leaderboard.interpol.sd-lab.nl',
//     'https://dashboard.interpol.sd-lab.nl',
//     'https://html-css.interpol.sd-lab.nl',
//     'https://kijk-op-de-wijk.interpol.sd-lab.nl',
//     'https://creative-coding.interpol.sd-lab.nl',
// ];

$allowedOrigins = [
    'https://admin.jeffvanderheijden.nl',
    'https://leaderboard.jeffvanderheijden.nl',
    'https://interpol.jeffvanderheijden.nl',
    'https://html-css.jeffvanderheijden.nl',
    'https://kijk-op-de-wijk.jeffvanderheijden.nl',
    'https://creative-coding.jeffvanderheijden.nl',
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    exit(0);
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
    // case 'PUT':
    //     include 'UPDATE/update.php';
    //     break;
    // case 'DELETE':
    //     include 'DELETE/delete.php';
    //     break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

$conn->close();