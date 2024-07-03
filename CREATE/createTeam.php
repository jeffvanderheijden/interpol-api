<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeam($conn, $params) {
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
        $file_path = 'uploads/default.png';
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO groups (name, image_url, class) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $params['name'], $file_path, $params['class']);

    // Execute the statement and check for errors
    if ($stmt->execute() === false) {
        die("Error inserting data into groups table: " . $stmt->error);
    }

    // Capture the newly created group ID
    $group_id = $conn->insert_id;

    // Close the statement
    $stmt->close();

    // Insert multiple student rows with the captured group ID
    if (!empty(json_decode($params['students'])) && is_array(json_decode($params['students']))) {
        $stmt = $conn->prepare("INSERT INTO students (student_name, student_number, group_id) VALUES (?, ?, ?)");
        foreach ($params['students'] as $student) {
            $stmt->bind_param("ssi", $student['name'], $student['number'], $group_id);
            if ($stmt->execute() === false) {
                die("Error inserting data into students table: " . $stmt->error);
            }
        }
        // Close the statement
        $stmt->close();
    } else {
        echo $params['students'];
    }
    echo json_encode(['message' => 'Records inserted successfully']);
}