<?php
include 'connection.php'; // include your database connection file

function getStudentsByGroup($conn, $params) {
    if (isset($params['id'])) {
        $sql = "SELECT *
            FROM students
            INNER JOIN groups ON students.group_id = groups.id
            WHERE groups.id = $params[id]";

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