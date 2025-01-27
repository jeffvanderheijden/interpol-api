<?php

// ============================
// Gets student data from session
// ============================
function getStudent() {
    var_dump($_SESSION);
    if (!isset($_SESSION['info'])) {
        return json_encode(['error' => 'No student data found in session.']);
    } else {
        // Safely encode session data to JSON with error handling
        $jsonData = json_encode($_SESSION['info'], JSON_PARTIAL_OUTPUT_ON_ERROR);

        if ($jsonData === false) {
            return json_encode(['error' => 'Error encoding JSON: ' . json_last_error_msg()]);
        } else {
            return $jsonData;
        }
    }
}

// ============================
// Gets additional student data by student_id
// ============================
function getAdditionalStudentData($conn, $params) {
    if (isset($params['student_id']) && is_numeric($params['student_id'])) {
        $student_id = (int) $params['student_id']; // Cast to integer for security

        // Use prepared statements to prevent SQL injection
        $sql = "SELECT s.student_number, s.name, g.name AS group_name
                FROM students s
                LEFT JOIN groups g ON s.group_id = g.id
                WHERE s.student_number = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $student_id);  // Bind parameter as integer
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $studentData = $result->fetch_assoc();
                $stmt->close();
                return json_encode($studentData);
            } else {
                $stmt->close();
                return json_encode(['error' => 'Student not found']);
            }
        } else {
            error_log("SQL preparation failed: " . $conn->error);
            return json_encode(['error' => 'Failed to retrieve student data']);
        }
    } else {
        return json_encode(['error' => 'Invalid or missing student_id']);
    }
}
