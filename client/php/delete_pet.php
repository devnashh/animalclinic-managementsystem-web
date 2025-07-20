<?php
// Include database connection
include '../../database/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];

    // Check if the pet has related bookings
    $checkQuery = "SELECT COUNT(*) as count FROM bookings WHERE pet_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('i', $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // Prevent deletion and return a message
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Cannot delete pet with existing bookings.'];
    } else {
        // Proceed to delete the pet
        $deleteQuery = "DELETE FROM pets WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $pet_id);

        if ($deleteStmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Pet deleted successfully.'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Failed to delete the pet.'];
        }
    }
    header("Location: ../pet_profile.php"); // Redirect back to pet profile page
    exit();
}
?>