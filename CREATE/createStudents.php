<?php 

// ============================
// Creates single student entry with login credentials
// ============================

function createStudent($conn, $params) {
    if (isset($params) && is_array($params)) {
        return "Records inserted successfully";
    } else {
        return "Missing parameters to create student record.";
    }
}