<?php
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
            echo json_encode($students);
        } else {
            echo json_encode(["error" => "Failed to prepare statement"]);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']); 
    }
}
function getStudentById($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT * FROM students WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Student not found']);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']);  
    }
}