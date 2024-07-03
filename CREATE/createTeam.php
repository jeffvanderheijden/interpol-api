<?php 
header('Content-Type: application/json');

// ============================
// Creates team image and uploads to server
// ============================
function createTeam($conn, $params) {

    $convertedParams = [
        'name' => $params['name'] ?? null,
        'class' => $params['class'] ?? null,
        'image' => $params['image'] ?? null,
        'students' => json_decode($params['students'], true) ?? [] // Expecting students data as JSON string
    ];

    if (isset($convertedParams['image'])) {
        // Save the image
        $base64_image = $convertedParams['image'];
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
    $stmt->bind_param("sss", $convertedParams['name'], $file_path, $convertedParams['class']);

    // Execute the statement and check for errors
    if ($stmt->execute() === false) {
        die("Error inserting data into groups table: " . $stmt->error);
    }

    // Capture the newly created group ID
    $group_id = $conn->insert_id;

    // Close the statement
    $stmt->close();

    // Insert multiple student rows with the captured group ID
    if (!empty($convertedParams['students']) && is_array($convertedParams['students'])) {
        $stmt = $conn->prepare("INSERT INTO students (student_name, student_number, group_id) VALUES (?, ?, ?)");
        foreach ($convertedParams['students'] as $student) {
            $stmt->bind_param("ssi", $student['name'], $student['number'], $group_id);
            if ($stmt->execute() === false) {
                die("Error inserting data into students table: " . $stmt->error);
            }
        }
        // Close the statement
        $stmt->close();
    }
    echo json_encode(['message' => 'Records inserted successfully']);
}