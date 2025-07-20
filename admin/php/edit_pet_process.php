<?php
// edit_pet_process.php

// Database connection
require_once '../../database/db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $pet_name = $_POST['pet_name'];
    $pet_age = $_POST['pet_age'];
    $pet_breed = $_POST['pet_breed'];
    $pet_sex = $_POST['pet_sex'];
    $pet_color = $_POST['pet_color'];
    $owner_name = $_POST['owner_name'];
    $owner_conatct = $_POST['owner_contact'];
    $owner_address = $_POST['owner_address'];
    $owner_email = $_POST['owner_email'];
    // Handle image upload
    $pet_image = $_FILES['pet_image']['name'];
    if ($pet_image) {
        $target_dir = "../../media/pet_images/";
        $target_file = $target_dir . basename($pet_image);
        move_uploaded_file($_FILES['pet_image']['tmp_name'], $target_file);
    } else {
        // If no new image, keep the old one
        $pet_image = $_POST['existing_image'];
    }

    // Update pet record in the database
    $sql = "UPDATE pets SET name = ?, age = ?, breed = ?, sex = ?, color = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $pet_name, $pet_age, $pet_breed, $pet_sex, $pet_color, $pet_image, $pet_id);

    $sql = "UPDATE pets SET name = ?, age = ?, breed = ?, sex = ?, color = ?, profile_picture = ?, owner_name = ?, owner_contact = ?, owner_address =?, owner_email WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissssi", $pet_name, $pet_age, $pet_breed, $pet_sex, $pet_color, $pet_image, $pet_id, $owner_name, $owner_contact, $owner_address, $owner_email);
    if ($stmt->execute()) {
        // Redirect back to pet records page
        header('Location: ../pet_records.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>