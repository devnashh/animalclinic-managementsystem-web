<?php
include '../../database/db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    // Fetch user data from the client table
    $query = "SELECT * FROM client WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Insert user data into archived_users table
        $insert_query = "INSERT INTO archived_users (id, full_name, contact_number, address, username, email, role) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("issssss", $row['id'], $row['full_name'], $row['contact_number'], $row['address'], $row['username'], $row['email'], $row['role']);

        if ($insert_stmt->execute()) {
            // Delete user from client table
            $delete_query = "DELETE FROM client WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();

            // Redirect back with success message
            header("Location: ../registered_users.php?message=User archived successfully.");
            exit();
        } else {
            header("Location: ../registered_users.php?error=Failed to archive user.");
            exit();
        }
    } else {
        header("Location: ../registered_users.php?error=User not found.");
        exit();
    }
} else {
    header("Location: registered_users.php");
    exit();
}
?>