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
    if (isset($params['student_id'])) {
        $student_id = $conn->real_escape_string($params['student_id']);
        $sql = "SELECT *
        FROM students s
        LEFT JOIN groups g ON s.group_id = g.id
        WHERE s.student_number = $student_id;";
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