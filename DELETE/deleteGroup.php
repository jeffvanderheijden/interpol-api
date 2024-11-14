<?php 
// ============================
// Creates all challenges for a student group with generated keys
// ============================
// Function to delete a group and associated records
function deleteGroup($conn, $data) {
    if (!isset($data['group_id'])) {
        return json_encode(['error' => 'Missing group_id parameter.']);
    }

    $group_id = (int) $data['group_id']; // Sanitize group_id input

    try {
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

        return json_encode(['success' => 'Group and associated records successfully deleted.']);
    } catch (mysqli_sql_exception $e) {
        // Rollback transaction if something goes wrong
        $conn->rollback();
        return json_encode(['error' => 'Failed to delete group: ' . $e->getMessage()]);
    }
}
