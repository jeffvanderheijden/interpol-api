<?php

function getStudentAndGroup($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "
            SELECT
                s.id AS student_id,
                s.name AS student_name,
                s.student_number as student_number,
                s.group_id,
                g.id AS group_id,
                g.name AS group_name,
                g.image_url AS group_image_url,
                g.class AS group_class
            FROM
                students s
            JOIN
                groups g
            ON
                s.group_id = g.id
            WHERE
                s.id = $id;
        ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return json_encode($result->fetch_assoc());
        } else {
            return json_encode(['error' => 'Student and/or group not found']);
        }
    } else {
        return json_encode(['error' => 'ID parameter missing']);  
    }
}