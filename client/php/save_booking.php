<?php
// Database connection
include '../../database/db_connection.php';
require '../../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Check if session contains client_id
if (!isset($_SESSION['client_id'])) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Client not logged in.'];
    header('Location: ../../accounts/signin.php');
    exit;
}

$client_id = $_SESSION['client_id'];
$pet_id = $_POST['pet_id'];
$service_type = $_POST['service_type'];
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$additional_notes = $_POST['additional_notes'];

$conn = new mysqli('127.0.0.1:3306', 'u784320783_qualipaws', 'Qualipaws#12345678', 'u784320783_db_qualipaws');
if ($conn->connect_error) {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Database connection failed.'];
    header('Location: ../appointment.php');
    exit;
}

// Fetch user email and pet details
$email_query = "SELECT c.email, p.name AS pet_name 
                FROM client c 
                INNER JOIN pets p ON c.id = p.client_id 
                WHERE c.id = ? AND p.id = ?";
$email_stmt = $conn->prepare($email_query);
$email_stmt->bind_param("ii", $client_id, $pet_id);
$email_stmt->execute();
$email_result = $email_stmt->get_result();
$email_data = $email_result->fetch_assoc();
$user_email = $email_data['email'];
$pet_name = $email_data['pet_name'];

$sql = "INSERT INTO bookings (client_id, pet_id, service_type, appointment_date, appointment_time, additional_notes)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $client_id, $pet_id, $service_type, $appointment_date, $appointment_time, $additional_notes);

if ($stmt->execute()) {
    $id = $conn->insert_id; // Get the last inserted ID here
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Booking successfully created!'];

    // Send notification email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qualipawsanimalhealthclinic@gmail.com';
        $mail->Password = 'zewlrtzjfdsumprl';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('qualipawsanimalhealthclinic@gmail.com', 'Qualipaws Animal Health Clinic');
        $mail->addAddress($user_email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Appointment Confirmation';
        $mail->Body = "
            <h1>Appointment Confirmation</h1>
            <p>Dear valued paw parent,</p>
            <p>Your Booking id no.: # $id</p>
            <p>Your appointment has been successfully booked. Here are the details:</p>
            <ul>
                <li><strong>Pet Name:</strong> $pet_name</li>
                <li><strong>Service Type:</strong> $service_type</li>
                <li><strong>Appointment Date:</strong> $appointment_date</li>
                <li><strong>Appointment Time:</strong> $appointment_time</li>
                <li><strong>Additional Notes:</strong> $additional_notes</li>
            </ul>
            <p>If you have any questions or need to make changes, please contact us at our clinic.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
} else {
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error: ' . $stmt->error];
}


$email_stmt->close();
$stmt->close();
$conn->close();

header('Location: ../appointment.php');
exit;
?>