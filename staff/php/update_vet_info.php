<?php
include '../../database/db_connection.php';

// Start the session
session_start();

// Check if POST data exists
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    if ($username) {
        // Get the fields
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $address = $_POST['address'];

        // Update only the contact number, email, and address
        $update_query = "UPDATE client SET contact_number = ?, email = ?, address = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ssss", $contact_number, $email, $address, $username);

        if ($update_stmt->execute()) {
            header("Location: ../settings.php?success_update=1");
            exit;
        } else {
            error_log("Update error: " . $update_stmt->error);
            header("Location: ../settings.php?error_update=update_failed");
        }

        $update_stmt->close();
    } else {
        header("Location: ../settings.php");
        exit;
    }
}
?>