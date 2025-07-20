<?php
// Include database connection and PHPMailer
include '../../database/db_connection.php';
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the response content type to JSON
header('Content-Type: application/json');

// Start the session to ensure admin authentication
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

// Get the POST data from the request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
    exit;
}

$bookingId = intval($data['id']);
$newStatus = mysqli_real_escape_string($conn, $data['status']);
$rejectionReason = isset($data['reason']) && !empty($data['reason']) ? mysqli_real_escape_string($conn, $data['reason']) : 'No specific reason provided.';

// Ensure the status is valid
$validStatuses = ['Approved', 'Rejected', 'Pending', 'Done'];
if (!in_array($newStatus, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

// Fetch user email and booking details
$fetchQuery = "SELECT c.email, c.full_name, b.id, b.appointment_date, b.appointment_time, b.booking_type 
               FROM bookings b
               JOIN client c ON b.client_id = c.id 
               WHERE b.id = $bookingId";

$result = mysqli_query($conn, $fetchQuery);
if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found.']);
    exit;
}

$booking = mysqli_fetch_assoc($result);
$userEmail = $booking['email'];
$userName = $booking['full_name'];
$appointmentDate = $booking['appointment_date'];
$appointmentTime = $booking['appointment_time'];
$bookingType = $booking['booking_type'];

// If status is "Done", move to archived_bookings and delete from bookings
if ($newStatus == 'Done') {
    $archiveQuery = "INSERT INTO archived_bookings (client_id, pet_id, service_type, appointment_date, appointment_time, additional_notes, created_at, updated_at, status, booking_type, walkin_customer_name, walkin_customer_contact, walkin_customer_email)
                     SELECT client_id, pet_id, service_type, appointment_date, appointment_time, additional_notes, created_at, updated_at, status, booking_type, walkin_customer_name, walkin_customer_contact, walkin_customer_email
                     FROM bookings WHERE id = $bookingId";

    if (mysqli_query($conn, $archiveQuery)) {
        $deleteQuery = "DELETE FROM bookings WHERE id = $bookingId";
        mysqli_query($conn, $deleteQuery);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to archive booking.']);
        exit;
    }
}

// Update the booking status in the database
$updateQuery = "UPDATE bookings SET status = '$newStatus' WHERE id = $bookingId";
if (mysqli_query($conn, $updateQuery)) {
    // Prepare email message for each status
    $subject = '';
    $message = '';

    switch ($newStatus) {
        case 'Approved':
            $subject = 'Booking Approved';
            $message = "
                <h3>Hello $userName,</h3>
                <p>Your booking (ID: $bookingId) for the appointment on <b>$appointmentDate</b> at <b>$appointmentTime</b> has been <span style='color: green;'>Approved</span>.</p>
                <p>Thank you for choosing Qualipaws Animal Health Clinic!</p>
            ";
            break;

        case 'Rejected':
            $subject = 'Booking Rejected';
            $message = "
                    <h3>Hello $userName,</h3>
                    <p>We regret to inform you that your booking (ID: $bookingId) for the appointment on <b>$appointmentDate</b> at <b>$appointmentTime</b> has been <span style='color: red;'>Rejected</span>.</p>
                    <p><b>Reason:</b> $rejectionReason</p>
                    <p>Maybe the service is unavailable or the time slot/date you selected are not available.</p>
                    <p>Please feel free to contact us for more details or to reschedule.</p>
                    
                ";
            break;


        case 'Pending':
            $subject = 'Booking Pending';
            $message = "
                <h3>Hello $userName,</h3>
                <p>Your booking (ID: $bookingId) for the appointment on <b>$appointmentDate</b> at <b>$appointmentTime</b> is currently <span style='color: orange;'>Pending</span>.</p>
                <p>We will notify you once your booking is confirmed. Thank you for your patience!</p>
            ";
            break;
    }

    // Send email notification to the user
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qualipawsanimalhealthclinic@gmail.com';
        $mail->Password = 'zewlrtzjfdsumprl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('qualipawsanimalhealthclinic@gmail.com', 'Qualipaws Animal Health Clinic');
        $mail->addAddress($userEmail, $userName);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $message;

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Status updated and email notification sent.']);
    } catch (Exception $e) {
        echo json_encode(['success' => true, 'message' => 'Status updated but email notification failed: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
}

?>