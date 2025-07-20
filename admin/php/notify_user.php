<?php
session_start();

require '../../database/db_connection.php'; // Ensure this file connects to your database

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php'; // Load PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Fetch booking details, including walk-in users
    $sql = "SELECT b.appointment_date, b.appointment_time, 
                   c.email, c.full_name, b.booking_type, 
                   b.walkin_customer_name, b.walkin_customer_email
            FROM bookings b
            LEFT JOIN client c ON b.client_id = c.id
            WHERE b.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $appointment_date = $row['appointment_date'];
        $appointment_time = $row['appointment_time'];
        $booking_type = strtolower(trim($row['booking_type'])); // Normalize case

        if ($booking_type === 'walk-in') {
            $recipient_name = $row['walkin_customer_name'];
            $recipient_email = trim($row['walkin_customer_email']);
        } else {
            $recipient_name = $row['full_name'];
            $recipient_email = trim($row['email']);
        }

        // Debugging: Check email output
        if (empty($recipient_email) || !filter_var($recipient_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "No valid email address found for $recipient_name.";
        } else {
            // Email content
            $subject = "Appointment Reminder - Your Upcoming Booking";
            $message = "
                <p>Dear $recipient_name,</p>
                <p>This is a reminder for your upcoming appointment.</p>
                <p><strong>Date:</strong> $appointment_date</p>
                <p><strong>Time:</strong> $appointment_time</p>
                <p>If you have any questions or need to reschedule, please contact us.</p>
                <p>Thank you!</p>";

            // Send email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'qualipawsanimalhealthclinic@gmail.com';
                $mail->Password = 'zewlrtzjfdsumprl';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('qualipawsanimalhealthclinic@gmail.com');
                $mail->addAddress($recipient_email, $recipient_name);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    $_SESSION['success'] = "Reminder sent successfully to $recipient_name!";
                } else {
                    $_SESSION['error'] = "Failed to send email.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $mail->ErrorInfo;
            }
        }
    } else {
        $_SESSION['error'] = "Booking not found.";
    }

    $stmt->close();
    $conn->close();
}

header("Location: ../appointment_list.php");
exit();
?>