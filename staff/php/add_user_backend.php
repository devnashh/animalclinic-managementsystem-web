<?php
include '../../database/db_connection.php';
session_start();

// Store form data in session if validation fails
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Save the form data to the session
    $_SESSION['form_data'] = $_POST;

    // Check if username already exists
    $check_username_query = "SELECT id FROM client WHERE username = '$username'";
    $check_username_result = mysqli_query($conn, $check_username_query);

    // Check if email already exists
    $check_email_query = "SELECT id FROM client WHERE email = '$email'";
    $check_email_result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_username_result) > 0) {
        // Username exists, redirect with error message
        $_SESSION['error_message'] = 'Username has already been taken.';
        header('Location: ../registered_users.php?message=User added successfully');
        exit;
    }

    if (mysqli_num_rows($check_email_result) > 0) {
        // Email exists, redirect with error message
        $_SESSION['error_message'] = 'Email has already been taken.';
        header('Location: ../registered_users.php?error=Failed to add user');
        exit;
    }

    // Insert new user into the database if no duplicates
    $query = "INSERT INTO client (full_name, username, email, contact_number, address, role, password) 
              VALUES ('$full_name', '$username', '$email', '$contact_number', '$address', '$role', '$hashed_password')";

    if (mysqli_query($conn, $query)) {
        // Success, clear session data
        unset($_SESSION['form_data']);
        $_SESSION['success_message'] = 'User added successfully';
        header('Location: ../registered_users.php?message=User added successfully');
    } else {
        // Failed to insert, redirect with error message
        $_SESSION['error_message'] = 'Failed to add user.';
        header('Location: ../registered_users.php?error=Failed to add user');
    }
    exit;
}
