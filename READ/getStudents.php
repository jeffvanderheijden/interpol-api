<?php
include 'connection.php'; // include your database connection file

function getStudentsByGroup($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT * 
                FROM students 
                INNER JOIN groups ON students.group_id = groups.id
                WHERE groups.id = $id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Students not found']);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']);  
    }
    // if (isset($params['id'])) {
    //     $sql = "SELECT *
    //             FROM students
    //             INNER JOIN groups ON students.group_id = groups.id
    //             WHERE groups.id = ?";

    //     if ($stmt = $conn->prepare($sql)) {
    //         $stmt->bind_param("i", $params['id']); // bind the group ID parameter
    //         $stmt->execute();
    //         $result = $stmt->get_result();

    //         $students = [];
    //         while ($row = $result->fetch_assoc()) {
    //             $students[] = $row;
    //         }

    //         $stmt->close();
    //         echo json_encode($students);
    //     } else {
    //         return ["error" => "Failed to prepare statement"];
    //     }
    // } else {
    //     echo json_encode(['error' => 'ID parameter missing']); 
    // }
}

$conn->close();