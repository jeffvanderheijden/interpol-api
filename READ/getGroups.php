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
            // Fetch image data if image_url is provided
            if (!empty($row['image_url'])) {
                $imagePath = 'uploads/' . $row['image_url'];
                $imageData = base64_encode(file_get_contents($imagePath));
                $row['image_data'] = $imageData;
            }
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