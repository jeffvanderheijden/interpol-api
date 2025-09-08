<?php
header('Content-Type: application/json');

// ============================
// Helper function for JSON errors
// ============================
function jsonError($msg) {
    echo json_encode(['error' => $msg]);
    exit;
}

// ============================
// Updates an existing group
// ============================
function updateGroup($conn, $data) {
    // Validate required fields
    if (!isset($data['group_id'], $data['name'], $data['class'])) {
        jsonError('Missing required fields.');
    }

    $group_id = intval($data['group_id']);
    $name = $data['name'];
    $class = $data['class'];
    $file_path = '';

    // Handle image upload if provided
    if (isset($data['image']) && isset($data['image']['tmp_name']) && !empty($data['image']['tmp_name'])) {
        $image = $data['image'];
        $filename = uniqid() . '.png';
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_path = $upload_dir . $filename;

        if (!move_uploaded_file($image['tmp_name'], $file_path)) {
            jsonError('Failed to save the uploaded image.');
        }
    } else {
        // No new image, fetch existing image from DB
        $stmt = $conn->prepare("SELECT image_url FROM groups WHERE id = ?");
        if (!$stmt) jsonError('Prepare failed: ' . $conn->error);
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $stmt->bind_result($existing_image_url);
        $stmt->fetch();
        $stmt->close();

        $file_path = !empty($existing_image_url) ? $existing_image_url : 'uploads/default.png';
    }

    // Update group info
    $stmt = $conn->prepare("UPDATE groups SET name = ?, image_url = ?, class = ? WHERE id = ?");
    if (!$stmt) jsonError('Prepare failed: ' . $conn->error);
    $stmt->bind_param("sssi", $name, $file_path, $class, $group_id);

    if (!$stmt->execute()) {
        jsonError('Failed to update group: ' . $stmt->error);
    }
    $stmt->close();

    // Handle students if provided
    $students = [];
    if (isset($data['students'])) {
        if (is_string($data['students'])) {
            $students = json_decode($data['students'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                jsonError('Invalid JSON in students field.');
            }
        } elseif (is_array($data['students'])) {
            $students = $data['students'];
        }
    }

    if (!empty($students)) {
        $conn->begin_transaction();
        try {
            // Delete old students
            $stmt = $conn->prepare("DELETE FROM students WHERE group_id = ?");
            if (!$stmt) jsonError('Prepare failed: ' . $conn->error);
            $stmt->bind_param("i", $group_id);
            $stmt->execute();
            $stmt->close();

            // Insert new students
            $stmt = $conn->prepare("INSERT INTO students (name, student_number, group_id) VALUES (?, ?, ?)");
            if (!$stmt) jsonError('Prepare failed: ' . $conn->error);

            foreach ($students as $student) {
                if (!isset($student['name'], $student['student_number'])) continue;
                $stmt->bind_param("ssi", $student['name'], $student['student_number'], $group_id);
                $stmt->execute();
            }
            $stmt->close();
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            jsonError('Transaction failed: ' . $e->getMessage());
        }
    }

    echo json_encode(['message' => 'Group updated successfully.']);
}
