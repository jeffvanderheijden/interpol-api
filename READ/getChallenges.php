<?php

header('Content-Type: application/json');

// Include the database connection file
include 'connection.php';

function getChallenges($conn) {
    $sql = "SELECT * FROM challenges";
    $result = $conn->query($sql);

    $challenges = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $challenges[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No challenges found']);
    }

    echo json_encode($challenges);
}

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
            echo json_encode($challenges);
        } else {
            echo json_encode(["error" => "Failed to prepare statement"]);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']); 
    }
}