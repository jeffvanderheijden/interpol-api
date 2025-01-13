<?php
header('Content-Type: application/json');

// ============================
// Updates an existing group
// ============================
function updateGroup($conn, $data) {
    // Handle the image if it's provided
    // if (isset($data['image'])) {
    //     // Save the image
    //     $image = $data['image'];
    //     $image_data = file_get_contents($image['tmp_name']);
    //     $filename = uniqid() . '.png';
    //     $file_path = 'uploads/' . $filename;

    //     // Ensure the directory exists and is writable
    //     if (!file_exists('uploads')) {
    //         mkdir('uploads', 0777, true);
    //     }

    //     if (move_uploaded_file($image['tmp_name'], $file_path) === false) {
    //         die("Failed to save the image.");
    //     }
    // } else {
    //     // Initialize $existing_image_url to avoid unassigned variable warning
    //     $existing_image_url = '';
        
    //     // If no new image is provided, fetch the existing image URL from the database
    //     $stmt = $conn->prepare("SELECT image_url FROM groups WHERE group_id = ?");
    //     if (!$stmt) {
    //         die("Error preparing statement to fetch existing image: " . $conn->error);
    //     }
    //     $stmt->bind_param("i", $data['group_id']);
    //     $stmt->execute();
    //     $stmt->bind_result($existing_image_url);
    //     $stmt->fetch();
    //     $stmt->close();

    //     // If no image exists, use the default image
    //     if (empty($existing_image_url)) {
    //         if(empty($image_data)) {
    //             $file_path = 'uploads/default.png';
    //         } else {
    //             $file_path = $image_data;
    //         }
    //     } else {
    //         $file_path = $existing_image_url;
    //     }
    // }

    $file_path = "";

    // Prepare the SQL statement to update the group
    $stmt = $conn->prepare("UPDATE groups SET name = ?, image_url = ?, class = ? WHERE id = ?");
    if (!$stmt) {
        die("Error preparing statement for groups table: " . $conn->error);
    }
    $stmt->bind_param("sssi", $data['name'], $file_path, $data['class'], $data['group_id']);

    // Execute the statement and check for errors
    if ($stmt->execute() === false) {
        die("Error updating data in groups table: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();

    // Decode students JSON once
    // Check if 'students' exists and decode it properly
    if (isset($data['students'])) {
        // Check if the students data is a string (i.e., a JSON string)
        if (is_string($data['students'])) {
            // Decode the students field from JSON string to array
            $students = json_decode($data['students'], true);  // Decode the students field as JSON

            // Check if decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['error' => 'Invalid JSON in students field']);
                exit;
            }
        } else {
            // If it's already an array, use it directly
            $students = $data['students'];
        }
    } else {
        $students = [];
    }

    // Update students information if provided
    if (!empty($students) && is_array($students)) {
        // First, delete existing students for the group (optional, based on your use case)
        $stmt = $conn->prepare("DELETE FROM students WHERE group_id = ?");
        if (!$stmt) {
            die("Error preparing statement for deleting students: " . $conn->error);
        }
        $stmt->bind_param("i", $data['group_id']);
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
            $stmt->bind_param("ssi", $student['name'], $student['number'], $data['group_id']);
            if ($stmt->execute() === false) {
                die("Error inserting data into students table: " . $stmt->error);
            }
        }
        // Close the statement
        $stmt->close();
    }

    echo json_encode(['message' => 'Group updated successfully.']);
}