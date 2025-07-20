<?php
// Include the database connection
include '../../database/db_connection.php';

// Check if user_id is set via POST
if (isset($_POST['user_id'])) {
    // Get the user_id from the POST data
    $user_id = intval($_POST['user_id']);  // Ensure the ID is an integer to prevent SQL injection

    // Prepare SQL query to delete the user from the archived_users table
    $sql = "DELETE FROM archived_users WHERE id = ?";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user_id to the SQL statement
        $stmt->bind_param('i', $user_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the same page to refresh the user list
            header("Location: ../settings.php?success=deleted");
            exit();

        } else {
            // If the deletion fails
            echo "Error deleting user: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // If there is an issue preparing the SQL statement
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "No user ID provided.";
}

// Close the database connection
$conn->close();
?>