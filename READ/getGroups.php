<?php

// ============================
// Gets all student groups
// ============================
function getGroups($conn) {
    $sql = "SELECT id, name, class, image_url FROM groups"; // Select specific columns as needed

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }

        $stmt->close();

        if (empty($groups)) {
            return json_encode(['error' => 'No groups found']);
        }

        return json_encode($groups);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(['error' => 'Failed to retrieve groups']);
    }
}

// ============================
// Gets student groups by ID
// ============================
function getGroupById($conn, $params) {
    if (!isset($params['id']) || !is_numeric($params['id'])) {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }

    $sql = "SELECT id, name, class, image_url FROM groups WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $params['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $group = $result->fetch_assoc();
            $stmt->close();
            return json_encode($group);
        } else {
            $stmt->close();
            return json_encode(['error' => 'Group not found']);
        }
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(['error' => 'Failed to retrieve group']);
    }
}

// ============================
// Get points by group id
// ============================
function getPointsByGroupId($conn, $params) {
    if (!isset($params['id']) || !is_numeric($params['id'])) {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }

    $sql = "SELECT SUM(points) as total_points FROM group_challenges WHERE group_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $params['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $points = $result->fetch_assoc();
            $stmt->close();
            return json_encode($points);
        } else {
            $stmt->close();
            return json_encode(['error' => 'Group not found']);
        }
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(['error' => 'Failed to retrieve points']);
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
            LIMIT 3";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }

        $stmt->close();

        if (empty($groups)) {
            return json_encode(['error' => 'No groups found']);
        }

        return json_encode($groups);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(['error' => 'Failed to retrieve top groups']);
    }
}

// ============================
// Get groups by class
// ============================
function getGroupsByClass($conn, $params) {
    if (!isset($params['class'])) {
        return json_encode(['error' => 'Class parameter missing']);
    }

    $sql = "SELECT id, name FROM groups WHERE class = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $params['class']);
        $stmt->execute();
        $result = $stmt->get_result();

        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }

        $stmt->close();

        if (empty($groups)) {
            return json_encode(['error' => 'No groups found for the specified class']);
        }

        return json_encode($groups);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(['error' => 'Failed to retrieve groups by class']);
    }
}
