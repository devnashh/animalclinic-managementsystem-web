<?php
session_start();
require_once '../../database/db_connection.php'; // Adjust the path if needed

if (isset($_GET['id'])) {
    $pet_id = intval($_GET['id']);

    // Retrieve pet data
    $sql = "SELECT * FROM pets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pet = $result->fetch_assoc();

        // Insert into archived_pets
        $archive_sql = "INSERT INTO archived_pets (client_id, name, age, sex, color, type, breed, profile_picture, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($archive_sql);
        $stmt->bind_param(
            "isissssss",
            $pet['client_id'],
            $pet['name'],
            $pet['age'],
            $pet['sex'],
            $pet['color'],
            $pet['type'],
            $pet['breed'],
            $pet['profile_picture'],
            $pet['created_at']
        );

        if ($stmt->execute()) {
            // Delete the pet from the pets table
            $delete_sql = "DELETE FROM pets WHERE id = ?";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bind_param("i", $pet_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Pet archived successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to delete pet from original records.";
            }
        } else {
            $_SESSION['error_message'] = "Failed to archive the pet.";
        }
    } else {
        $_SESSION['error_message'] = "Pet not found.";
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

header("Location: ../pet_records.php");
exit;
