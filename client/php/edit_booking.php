<?php
include_once '../../database/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['bookingId'])) {
    $id = $data['bookingId'];
    $date = $data['appointmentDate'];
    $time = $data['appointmentTime'];
    $notes = $data['additionalNotes'];

    // Check if booking is approved
    $status_check = $conn->prepare("SELECT status FROM bookings WHERE id = ?");
    $status_check->bind_param("i", $id);
    $status_check->execute();
    $result = $status_check->get_result();
    $booking = $result->fetch_assoc();

    if ($booking['status'] === 'Approved') {
        echo json_encode(["error" => "You cannot edit an approved booking."]);
        exit;
    }

    // Proceed with the update if not approved
    $stmt = $conn->prepare("UPDATE bookings SET appointment_date = ?, appointment_time = ?, additional_notes = ? WHERE id = ?");
    $stmt->bind_param("sssi", $date, $time, $notes, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to update booking."]);
    }
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>