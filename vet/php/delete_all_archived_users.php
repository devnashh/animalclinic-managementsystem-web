<?php
// delete_all_archived_users.php

require_once '../../database/db_connection.php'; // Include your database connection

// Only allow deletion if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Unauthorized action.";
    exit;
}

$sql = "DELETE FROM archived_users"; // Assuming 'archived_users' is your table name
if ($conn->query($sql) === TRUE) {
    echo "All archived users deleted successfully.";
} else {
    echo "Error deleting records: " . $conn->error;
}

$conn->close();
?>