<?php
include '../database/db_connection.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data from archived_users
    $query = "SELECT * FROM archived_users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Insert the user back into the `client` table
        $insertQuery = "INSERT INTO client (full_name, username, contact_number, email, address, role)
                        VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param(
            "ssssss",
            $user['full_name'],
            $user['username'],
            $user['contact_number'],
            $user['email'],
            $user['address'],
            $user['role']
        );

        if ($insertStmt->execute()) {
            // Delete from archived_users after successful restoration
            $deleteQuery = "DELETE FROM archived_users WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $id);
            $deleteStmt->execute();

            header("Location: ../settings.php?success=1");
            exit;
        } else {
            header("Location: ../settings.php?error=1");
            exit;
        }
    } else {
        header("Location: ../settings.php?error=2"); // User not found in archive
        exit;
    }
} else {
    header("Location: ../settings.php?error=3"); // No ID provided
    exit;
}
?>