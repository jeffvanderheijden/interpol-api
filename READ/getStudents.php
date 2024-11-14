<?php

// ============================
// Gets all students in a group by group ID
// ============================
function getStudentsByGroup($conn, $params) {
    if (isset($params['id']) && is_numeric($params['id'])) {  // Validate that 'id' is a number
        $group_id = (int) $params['id'];  // Safely cast to integer

        $sql = "SELECT id, name, student_number
                FROM students
                WHERE group_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $group_id); // Bind the group_id as an integer parameter
            $stmt->execute();
            $result = $stmt->get_result();

            $students = [];
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }

            $stmt->close();
            return json_encode($students);
        } else {
            return json_encode(["error" => "Failed to prepare statement"]);
        }
    } else {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }
}

// ============================
// Gets student by ID
// ============================
function getStudentById($conn, $params) {
    if (isset($params['id']) && is_numeric($params['id'])) {  // Validate that 'id' is a number
        $student_id = (int) $params['id'];  // Safely cast to integer

        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM students WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $student_id);  // Bind the student_id as an integer parameter
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt->close();
                return json_encode($result->fetch_assoc());
            } else {
                $stmt->close();
                return json_encode(['error' => 'Student not found']);
            }
        } else {
            return json_encode(['error' => 'Failed to prepare statement']);
        }
    } else {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }
}
