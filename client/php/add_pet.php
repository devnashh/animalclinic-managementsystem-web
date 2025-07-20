<?php
// add_pet.php

// Include database connection
include('../../database/db_connection.php'); // Adjust path to your actual DB connection file

// Start session to get the client_id from session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['client_id'])) {
    die("You must be logged in to add a pet.");
}

// Get client_id from session
$client_id = $_SESSION['client_id'];

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $color = $_POST['color'];
    $type = $_POST['type'];
    $breed = $_POST['breed'];
    $weight = $_POST['weight'];

    // Handle file upload for pet profile picture
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Upload file
        $upload_dir = '../../media/pet_images/';
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_new_name = uniqid() . '.' . $file_ext;

        // Move file to the upload directory
        if (move_uploaded_file($file_tmp, $upload_dir . '/' . $file_new_name)) {
            $profile_picture = $file_new_name; // Store only the file name or relative path in the database
        } else {
            die("Failed to upload the file. Please try again.");
        }

    }

    // Insert pet information into the pets table
    $query = "INSERT INTO pets (client_id, name, age, sex, color, type, breed, profile_picture, weight)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('issssssss', $client_id, $name, $age, $sex, $color, $type, $breed, $profile_picture, $weight);

        // Execute the query and check for success
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Pet added successfully!'];
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $stmt->error];
        }

        // Close statement and connection
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement: " . $conn->error;
    }

    // Close database connection
    $conn->close();
    header('Location: ../pet_profile.php');
    exit;
}
?>