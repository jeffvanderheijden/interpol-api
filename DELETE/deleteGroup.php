<?php 
// ============================
// Creates all challenges for a student group with generated keys
// ============================
// Function to delete a group and associated records
function deleteGroup($conn, $data) {
    if (!isset($data['group_id'])) {
        return json_encode(['error' => 'Missing group_id parameter.' + $data]);
    }

    $group_id = (int) $data['group_id']; // Sanitize group_id input

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Delete from group_challenges
        $stmt = $conn->prepare("DELETE FROM group_challenges WHERE group_id = :group_id");
        $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();

        // Delete from students
        $stmt = $conn->prepare("DELETE FROM students WHERE group_id = :group_id");
        $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();

        // Delete from groups
        $stmt = $conn->prepare("DELETE FROM groups WHERE id = :group_id");
        $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        return json_encode(['success' => 'Group and associated records successfully deleted.']);
    } catch (PDOException $e) {
        // Rollback transaction if something goes wrong
        $conn->rollBack();
        return json_encode(['error' => 'Failed to delete group: ' . $e->getMessage()]);
    }
}