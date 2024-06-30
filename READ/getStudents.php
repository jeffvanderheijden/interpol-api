<?php
// ============================
// Gets all students in a group by group ID
// ============================
function getStudentsByGroup($conn, $params) {
    if (isset($params['id'])) {
        $sql = "SELECT id, name, student_number
            FROM students
            WHERE group_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $params['id']); // "i" indicates integer type for the parameter
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
        return json_encode(['error' => 'ID parameter missing']); 
    }
}
// ============================
// Gets student by ID
// ============================
function getStudentById($conn, $params) {
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