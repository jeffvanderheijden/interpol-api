<?php 
// ============================
// Deletes a student
// ============================

function deleteStudent($conn, $data) {
    // Step 1: Sanitize and validate the student_id
    if (!isset($data['student_id']) || !is_numeric($data['student_id']) || (int)$data['student_id'] <= 0) {
        return json_encode(['error' => 'Invalid or missing student_id parameter.']);
    }

    $student_id = (int) $data['student_id']; // Sanitize the student_id as an integer

    // Step 3: Begin a database transaction
    $conn->begin_transaction();

    try {
        // Step 4: Delete associated records in a transaction

        // Delete from students
        $stmt = $conn->prepare("DELETE FROM students WHERE student_number = ?");
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $stmt->close();

        // Step 5: Commit the transaction
        $conn->commit();

        // Return success response
        return json_encode(['success' => 'Student successfully deleted.']);

    } catch (mysqli_sql_exception $e) {
        // Step 6: Rollback the transaction if something goes wrong
        $conn->rollback();

        // Log the error message securely for debugging purposes (avoid showing in response)
        error_log('Error deleting student: ' . $e->getMessage());

        return json_encode(['error' => 'Failed to delete student due to a system error. Please try again later.']);
    }
}