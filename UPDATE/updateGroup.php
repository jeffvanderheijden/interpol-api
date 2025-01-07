<?php 
header('Content-Type: application/json');

// ============================
// Updates an existing group
// ============================
function updateGroup($conn, $params) {
    // Ensure the group_id is provided for the update
    if (!isset($params['group_id'])) {
        die("Error: Group ID is required for updating.");
    }

    // Handle the image if it's provided
    if (isset($params['image'])) {
        // Save the image
        $base64_image = $params['image'];
        $base64_image = str_replace('data:image/png;base64,', '', $base64_image);
        $image_data = base64_decode($base64_image);
        $filename = uniqid() . '.png';
        $file_path = 'uploads/' . $filename;

        // Ensure the directory exists and is writable
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (file_put_contents($file_path, $image_data) === false) {
            die("Failed to save the image.");
        }
    } else {
        // Initialize $existing_image_url to avoid unassigned variable warning
        $existing_image_url = '';
        
        // If no new image is provided, fetch the existing image URL from the database
        $stmt = $conn->prepare("SELECT image_url FROM groups WHERE group_id = ?");
        if (!$stmt) {
            die("Error preparing statement to fetch existing image: " . $conn->error);
        }
        $stmt->bind_param("i", $params['group_id']);
        $stmt->execute();
        $stmt->bind_result($existing_image_url);
        $stmt->fetch();
        $stmt->close();

        // If no image exists, use the default image
        if (empty($existing_image_url)) {
            $file_path = 'uploads/default.png';
        } else {
            $file_path = $existing_image_url;
        }
    }

    // Prepare the SQL statement to update the group
    $stmt = $conn->prepare("UPDATE groups SET name = ?, image_url = ?, class = ? WHERE group_id = ?");
    if (!$stmt) {
        die("Error preparing statement for groups table: " . $conn->error);
    }
    $stmt->bind_param("sssi", $params['name'], $file_path, $params['class'], $params['group_id']);

    // Execute the statement and check for errors
    if ($stmt->execute() === false) {
        die("Error updating data in groups table: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Decode students JSON once
    $students = json_decode($params['students'], true);

    // Update students information if provided
    if (!empty($students) && is_array($students)) {
        // First, delete existing students for the group (optional, based on your use case)
        $stmt = $conn->prepare("DELETE FROM students WHERE group_id = ?");
        if (!$stmt) {
            die("Error preparing statement for deleting students: " . $conn->error);
        }
        $stmt->bind_param("i", $params['group_id']);
        if ($stmt->execute() === false) {
            die("Error deleting students from the group: " . $stmt->error);
        }
        $stmt->close();

        // Insert updated student data
        $stmt = $conn->prepare("INSERT INTO students (name, student_number, group_id) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error preparing statement for students table: " . $conn->error);
        }
        foreach ($students as $student) {
            // Check if student data is correctly structured
            if (!isset($student['name']) || !isset($student['number'])) {
                die("Error: Student data is incomplete.");
            }
            $stmt->bind_param("ssi", $student['name'], $student['number'], $params['group_id']);
            if ($stmt->execute() === false) {
                die("Error inserting data into students table: " . $stmt->error);
            }
        }
        // Close the statement
        $stmt->close();
    }

    echo json_encode(['message' => 'Group updated successfully.']);
}