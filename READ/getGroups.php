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
        return json_encode(['error' => 'No groups found']);
    }

    return json_encode($groups);
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
            return json_encode($result->fetch_assoc());
        } else {
            return json_encode(['error' => 'Group not found']);
        }
    } else {
        return json_encode(['error' => 'ID parameter missing']);  
    }
}
// ============================
// Get points by group id
// ============================
function getPointsByGroupId($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT SUM(points) as total_points FROM group_challenges WHERE group_id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return json_encode($result->fetch_assoc());
        } else {
            return json_encode(['error' => 'Group not found']);
        }
    } else {
        return json_encode(['error' => 'ID parameter missing']);
    }
}
// ============================
// Get top 3 groups by points
// ============================
function getTopThreeGroups($conn) {
    $sql = "SELECT group_id, SUM(points) AS total_points
            FROM group_challenges
            GROUP BY group_id
            ORDER BY total_points DESC
            LIMIT 3;";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return json_encode($result->fetch_assoc());
    } else {
        return json_encode(['error' => 'Something went wrong fetching top 3 groups']);
    }
}
