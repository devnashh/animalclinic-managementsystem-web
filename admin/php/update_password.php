<?php
include '../../database/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../accounts/signin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = $_SESSION['username']; // Get the logged-in vet's username
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Ensure both passwords match
    if ($new_password !== $confirm_password) {
        header("Location: ../settings.php?error=Passwords do not match");
        exit;
    }

    // Hash the new password before storing it
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in database
    $query = "UPDATE client SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $admin_username);

    if ($stmt->execute()) {
        header("Location: ../settings.php?success=Password updated successfully");
    } else {
        header("Location: ../settings.php?error=Failed to update password");
    }

    $stmt->close();
}
$conn->close();
?>