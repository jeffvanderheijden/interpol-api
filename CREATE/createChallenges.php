<?php 
// ============================
// Creates all challenges for a student group with generated keys
// ============================
function createChallengesPerGroup($conn, $params) {
    // First get all groups
    

    if (isset($params['group_id'])) {
        $group_id = $conn->real_escape_string($params['group_id']);
        echo $group_id;
    } else {
        echo json_encode(['error' => 'Group ID parameter missing']);
    }
}