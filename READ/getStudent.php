<?php

function getStudent() {
    if (!isset($_SESSION['info'])) {
        return json_encode(['No student data found in session.']);
    } else {
        var_dump($_SESSION['info']);
        $jsonData = json_encode($_SESSION['info'], JSON_PARTIAL_OUTPUT_ON_ERROR);

        if ($jsonData === false) {
            return "Error encoding JSON: " . json_last_error_msg();
        } else {
            return $jsonData;
        }
    }
}