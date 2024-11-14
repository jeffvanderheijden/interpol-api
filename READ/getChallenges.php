<?php
// ============================
// Gets all challenges
// ============================
function getChallenges($conn) {
    $sql = "SELECT * FROM challenges";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();

        $challenges = [];
        while ($row = $result->fetch_assoc()) {
            $challenges[] = $row;
        }

        $stmt->close();

        if (empty($challenges)) {
            return json_encode(['error' => 'No challenges found']);
        }

        return json_encode($challenges);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(["error" => "Failed to retrieve challenges"]);
    }
}

// ============================
// Gets challenges by student group ID
// ============================
function getChallengesByGroupId($conn, $params) {
    if (!isset($params['id']) || !is_numeric($params['id'])) {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }

    $group_id = (int) $params['id'];
    $sql = "SELECT * FROM group_challenges WHERE group_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $challenges = [];
        while ($row = $result->fetch_assoc()) {
            $challenges[] = $row;
        }

        $stmt->close();

        if (empty($challenges)) {
            return json_encode(['error' => 'No challenges found for the specified group ID']);
        }

        return json_encode($challenges);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(["error" => "Failed to retrieve challenges for group ID"]);
    }
}

// ============================
// Gets challenges by ID
// ============================
function getChallengeById($conn, $params) {
    if (!isset($params['id']) || !is_numeric($params['id'])) {
        return json_encode(['error' => 'Invalid or missing ID parameter']);
    }

    $challenge_id = (int) $params['id'];
    $sql = "SELECT * FROM challenges WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $challenge_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $challenges = [];
        while ($row = $result->fetch_assoc()) {
            $challenges[] = $row;
        }

        $stmt->close();

        if (empty($challenges)) {
            return json_encode(['error' => 'No challenge found for the specified ID']);
        }

        return json_encode($challenges);
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
        return json_encode(["error" => "Failed to retrieve challenge by ID"]);
    }
}
