<?php
session_start();
include '../../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];

    $targetDir = "../../media/pet_images/";
    $profilePicture = null;

    // Check if a file is uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Allow specific file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'avif');
        if (in_array($fileType, $allowedTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                // Save web-accessible path to DB
                $profilePicture = '../../media/pet_images/' . $fileName;
            } else {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error uploading image.'];
                header("Location: ../add_pet.php");
                exit;
            }
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Only JPG, JPEG, PNG & GIF files are allowed.'];
            header("Location: ../add_pet.php");
            exit;
        }
    }

    // Update query with or without image
    if ($profilePicture) {
        $query = "UPDATE pets SET name=?, age=?, weight=?, profile_picture=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $age, $weight, $profilePicture, $id);
    } else {
        $query = "UPDATE pets SET name=?, age=?, weight=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $age, $weight, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Pet information updated successfully.'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Failed to update pet information.'];
    }
    $stmt->close();
    $conn->close();

    header("Location: ../add_pet.php");
    exit;
}
?>