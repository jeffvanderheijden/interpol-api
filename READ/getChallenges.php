<?php

header('Content-Type: application/json');

// Include the database connection file
include 'connection.php';

function getChallenges($conn) {
    $sql = "SELECT * FROM challenges";
    $result = $conn->query($sql);

    $groups = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
    } else {
        echo json_encode(['error' => 'No challenges found']);
    }

    echo json_encode($groups);
}

function getChallengesByGroupId($conn, $params) {
    if (isset($params['id'])) {
        $id = $conn->real_escape_string($params['id']);
        $sql = "SELECT * FROM group_challenges WHERE group_id = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Group challenges not found']);
        }
    } else {
        echo json_encode(['error' => 'ID parameter missing']);  
    }
}