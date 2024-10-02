<?php

function getStudent() {
    if (!isset($_SESSION['info'])) {
        return json_encode(['No student data found in session.']);
    } else {
        $jsonData = json_encode($_SESSION['info'], JSON_PARTIAL_OUTPUT_ON_ERROR);

        if ($jsonData === false) {
            return "Error encoding JSON: " . json_last_error_msg();
        } else {
            return $jsonData;
        }
    }
}

function getAdditionalStudentData($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT * FROM students WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return json_encode($result->fetch_assoc());
        } else {
            return json_encode(['error' => 'Student not found']);
        }
    } else {
        return json_encode(['error' => 'ID parameter missing']);  
    }
}