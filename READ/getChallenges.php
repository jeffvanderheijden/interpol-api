<?php
// ============================
// Gets all challenges
// ============================
function getChallenges($conn) {
    $sql = "SELECT * FROM challenges";
    $result = $conn->query($sql);

    $challenges = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $challenges[] = $row;
        }
    } else {
        return json_encode(['error' => 'No challenges found']);
    }

    return json_encode($challenges);
}
// ============================
// Gets challenges by student group ID
// ============================
function getChallengesByGroupId($conn, $params) {
    if (isset($params['id'])) {
        $sql = "SELECT *
            FROM group_challenges
            WHERE group_id = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $params['id']); // "i" indicates integer type for the parameter
            $stmt->execute();
            $result = $stmt->get_result();

            $challenges = [];
            while ($row = $result->fetch_assoc()) {
                $challenges[] = $row;
            }

            $stmt->close();
            return json_encode($challenges);
        } else {
            return json_encode(["error" => "Failed to prepare statement"]);
        }
    } else {
        return json_encode(['error' => 'ID parameter missing']); 
    }
}