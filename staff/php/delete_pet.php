<?php
// Start session
session_start();

// Database connection
require_once '../../database/db_connection.php'; // Adjust path if needed

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $pet_id = intval($_GET['id']); // Sanitize the input

    // Prepare the DELETE query
    $sql = "DELETE FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $pet_id);
        if ($stmt->execute()) {
            // Successfully deleted
            $_SESSION['success_message'] = "Pet record deleted successfully.";
        } else {
            // Error during deletion
            $_SESSION['error_message'] = "Failed to delete the pet record.";
        }
        $stmt->close();
    } else {
        // Error preparing the statement
        $_SESSION['error_message'] = "Database error. Could not prepare the query.";
    }
} else {
    $_SESSION['error_message'] = "Invalid pet ID.";
}

// Redirect back to the pet records page
header("Location: ../pet_records.php");
exit;
