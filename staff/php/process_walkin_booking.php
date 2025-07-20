<?php
session_start();
include '../../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $walkin_customer_name = $_POST['walkin_customer_name'];
    $walkin_customer_contact = $_POST['walkin_customer_contact'];
    $walkin_customer_email = $_POST['walkin_customer_email'];
    $service_type = $_POST['service_type'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $additional_notes = $_POST['additional_notes'];
    $pet_name = $_POST['pet_name'];
    $pet_type = $_POST['pet_type'];

    // Insert into bookings table
    $sql = "INSERT INTO bookings (client_id, pet_id, booking_type, walkin_customer_name, walkin_customer_contact, walkin_customer_email, service_type, appointment_date, appointment_time, additional_notes, pet_type, pet_name, status, created_at, updated_at) 
            VALUES (NULL, NULL, 'Walk-in', ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW(), NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssss', $walkin_customer_name, $walkin_customer_contact, $walkin_customer_email, $service_type, $appointment_date, $appointment_time, $additional_notes, $pet_type, $pet_name);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking successfully created!";
    } else {
        $_SESSION['error'] = "Booking Failed!";
    }

    header("Location: ../appointment_list.php");
    exit;
}
?>