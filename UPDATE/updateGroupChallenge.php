<?php
header('Content-Type: application/json');

// ============================
// Updates points for a group + challenge combination
// ============================
function updateGroupChallengePoints($conn, $data)
{
    // Validate required fields
    if (!isset($data['group_id']) || !isset($data['challenge_id']) || !isset($data['points'])) {
        echo json_encode(['error' => 'Missing group_id, challenge_id, or points']);
        exit;
    }

    $group_id = intval($data['group_id']);
    $challenge_id = intval($data['challenge_id']);
    $points = intval($data['points']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE group_challenges SET points = ? WHERE group_id = ? AND id = ?");
    if (!$stmt) {
        die("Error preparing statement for group_challenges table: " . $conn->error);
    }

    $stmt->bind_param("iii", $points, $group_id, $challenge_id);

    // Execute the statement and check for errors
    if ($stmt->execute() === false) {
        die("Error updating data in group_challenges table: " . $stmt->error);
    }

    $stmt->close();

    // Check if any row was actually updated
    if ($conn->affected_rows === 0) {
        echo json_encode(['message' => 'No rows updated — check if group_id and challenge_id exist.']);
    } else {
        echo json_encode(['message' => 'Group challenge points updated successfully.']);
    }
}
