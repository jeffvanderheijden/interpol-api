<?php 
// ============================
// Deletes a group and associated records
// ============================

function deleteGroup($conn, $data) {
    // Step 1: Sanitize and validate the group_id
    if (!isset($data['group_id']) || !is_numeric($data['group_id']) || (int)$data['group_id'] <= 0) {
        return json_encode(['error' => 'Invalid or missing group_id parameter.']);
    }

    $group_id = (int) $data['group_id']; // Sanitize the group_id as an integer

    // Step 2: Check if user has permission to delete the group (example role check)
    if (!isAuthorizedToDeleteGroup()) {
        return json_encode(['error' => 'Unauthorized access']);
    }

    // Step 3: Begin a database transaction
    $conn->begin_transaction();

    try {
        // Step 4: Delete associated records in a transaction

        // Delete from group_challenges
        $stmt = $conn->prepare("DELETE FROM group_challenges WHERE group_id = ?");
        $stmt->bind_param('i', $group_id);
        $stmt->execute();
        $stmt->close();

        // Delete from students
        $stmt = $conn->prepare("DELETE FROM students WHERE group_id = ?");
        $stmt->bind_param('i', $group_id);
        $stmt->execute();
        $stmt->close();

        // Delete from groups
        $stmt = $conn->prepare("DELETE FROM groups WHERE id = ?");
        $stmt->bind_param('i', $group_id);
        $stmt->execute();
        $stmt->close();

        // Step 5: Commit the transaction
        $conn->commit();

        // Return success response
        return json_encode(['success' => 'Group and associated records successfully deleted.']);

    } catch (mysqli_sql_exception $e) {
        // Step 6: Rollback the transaction if something goes wrong
        $conn->rollback();

        // Log the error message securely for debugging purposes (avoid showing in response)
        error_log('Error deleting group: ' . $e->getMessage());

        return json_encode(['error' => 'Failed to delete group due to a system error. Please try again later.']);
    }
}

// Function to check if the user has permission to delete a group
function isAuthorizedToDeleteGroup() {
    // Implement your role-based check logic here (e.g., check if user is admin)
    // Example: Assuming $_SESSION['role'] is set to 'admin' or 'teacher'
    return isset($_SESSION['role']) && $_SESSION['role'] === 'DOCENT';
}
