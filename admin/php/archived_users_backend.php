<?php
include '../../database/db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $clientId = intval($_POST['id']);

    // Fetch the booking details
    $query = "SELECT * FROM client WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $client);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if ($booking) {
        // Insert the booking details into archive_bookings table
        $archive_query = "INSERT INTO archive_users 
                          (id, client_id, pet_id, service_type, appointment_date, appointment_time, additional_notes, created_at, updated_at, booking_type, walkin_customer_name, walkin_customer_contact) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($archive_query);
        $stmt->bind_param(
            "iiisssssssss",
            $booking['id'],
            $booking['client_id'],
            $booking['pet_id'],
            $booking['service_type'],
            $booking['appointment_date'],
            $booking['appointment_time'],
            $booking['additional_notes'],
            $booking['created_at'],
            $booking['updated_at'],
            $booking['booking_type'],
            $booking['walkin_customer_name'],
            $booking['walkin_customer_contact']
        );

        if ($stmt->execute()) {
            // Delete from bookings table after archiving
            $delete_query = "DELETE FROM bookings WHERE id = ?";
            $stmt_delete = $conn->prepare($delete_query);
            $stmt_delete->bind_param("i", $booking_id);
            $stmt_delete->execute();
            $stmt_delete->close();

            $_SESSION['success'] = "Booking successfully archived!";
        } else {
            $_SESSION['error'] = "Failed to archive booking.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Booking not found.";
    }

    $conn->close();
    header("Location: ../appointment_list.php"); // Redirect back to the appointments page
    exit();
}
?>