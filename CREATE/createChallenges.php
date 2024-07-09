<?php 

include '_helpers/generateKey.php';

// ============================
// Creates all challenges for a student group with generated keys
// ============================
function createChallengesPerGroup($conn, $params) {
    if (isset($params)) {
        $groupId = $params;
        // Fetch all challenge IDs
        $challengeIds = [];
        $result = $conn->query("SELECT id FROM challenges");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $challengeIds[] = $row['id'];
            }
        }

        // Prepare the insert statement
        $stmt = $conn->prepare("INSERT INTO group_challenges (group_id, challenge_id, keycode) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        // Loop through all challenges and insert them into the group_challenges table
        foreach ($challengeIds as $challengeId) {
            $keycode = generateKey();
            $stmt->bind_param("iis", $groupId, $challengeId, $keycode);
            $stmt->execute();
        }

        return "Records inserted successfully";
    } else {
        return "Group ID parameter missing";
    }
}
// ============================
// Set points for specific challenge for a group by ID's
// ============================
function setChallengePoints($conn, $params) {
    if (isset($params)) {
        return var_dump($params);
        die();
        $groupId = $params['group_id'];
        $challengeId = $params['challenge_id'];
        $points = $params['points'];
        $completed = $params['completed'];
        // TODO: only set points after inserting correct keycode
        $stmt = $conn->prepare("UPDATE group_challenges SET points = ?, completed = ? WHERE group_id = ? AND challenge_id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("iii", $points, $completed, $groupId, $challengeId);
        $stmt->execute();

        return "Points updated successfully";
    } else {
        return "Parameters missing";
    }
}