<?php
session_start(); // Start the session
require_once '../../database/db_connection.php'; // Database connection

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Unauthorized action.";
    exit;
}

// Prepare the delete query
$sql = "DELETE FROM archived_bookings"; // Ensure this is the correct table name
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    echo "All archived users deleted successfully.";
} else {
    echo "Error deleting records: " . $conn->error;
}

// Close connection
$stmt->close();
$conn->close();
?>