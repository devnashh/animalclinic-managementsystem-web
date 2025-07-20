<?php
// Include database connection
include '../../database/db_connection.php'; // Adjust the path as necessary

// Include PHPMailer for email notifications
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php'; // Adjust path based on your setup

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

// Ensure the status is valid
$validStatuses = ['Approved', 'Rejected', 'Pending'];
if (!in_array($newStatus, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

// Fetch the client's email based on the booking ID
$clientQuery = "SELECT c.email, c.full_name 
                FROM bookings b 
                JOIN client c ON b.client_id = c.id 
                WHERE b.id = $bookingId";
$clientResult = mysqli_query($conn, $clientQuery);

if (!$clientResult || mysqli_num_rows($clientResult) === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking or client not found.']);
    exit;
}

$client = mysqli_fetch_assoc($clientResult);
$clientEmail = $client['email'];
$clientName = $client['full_name'];

// Update the booking status in the database
$query = "UPDATE bookings SET status = '$newStatus' WHERE id = $bookingId";
if (mysqli_query($conn, $query)) {
    // Send email notification
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qualipawsanimalhealthclinic@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'zewlrtzjfdsumprl'; // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('qualipawsanimalhealthclinic@gmail.com', 'QualiPaws Animal Health Clinic'); // Replace with your info
        $mail->addAddress($clientEmail, $clientName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Status Update';
        $mail->Body = "
            <p>Dear $clientName,</p>
            <p>Your booking status has been updated to: <strong>$newStatus</strong>.</p>
            <p>Thank you for choosing QualiPaws Animal Health Clinic.</p>
        ";
        $mail->AltBody = "Dear $clientName, your booking status has been updated to: $newStatus. Thank you for choosing QualiPaws Animal Health Clinic.";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Status updated successfully and notification sent.']);
    } catch (Exception $e) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully, but notification could not be sent.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
}
?>