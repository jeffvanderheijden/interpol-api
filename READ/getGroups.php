<?php

// ============================
// Gets all student groups
// ============================
function getGroups($conn) {
    $sql = "SELECT * FROM groups";
    $result = $conn->query($sql);

    $groups = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No groups found']);
    }

    echo json_encode($groups);
}
// ============================
// Gets student groups by ID
// ============================
function getGroupsById($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT * FROM groups WHERE id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Group not found']);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']);  
    }
}