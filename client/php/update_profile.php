<?php
require '../../database/db_connection.php'; // Your database connection
require '../../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Update query
    $query = "UPDATE client SET full_name = ?, age = ?, contact_number = ?, email = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisssi", $full_name, $age, $contact_number, $email, $address, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";

        // Send notification email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your mail server
            $mail->SMTPAuth = true;
            $mail->Username = 'qualipawsanimalhealthclinic@gmail.com'; // Your Gmail address
            $mail->Password = 'zewlrtzjfdsumprl'; // Your Gmail password (use app-specific password if 2FA is enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('qualipawsanimalhealthclinic@gmail.com', 'Your System Name'); // Your sender email and name
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Profile Update Notification';
            $mail->Body = "
                <h1>Profile Updated Successfully</h1>
                <p>Dear $full_name,</p>
                <p>Your profile has been updated successfully. Below are the updated details:</p>
                <ul>
                    <li><strong>Full Name:</strong> $full_name</li>
                    <li><strong>Age:</strong> $age</li>
                    <li><strong>Contact Number:</strong> $contact_number</li>
                    <li><strong>Email:</strong> $email</li>
                    <li><strong>Address:</strong> $address</li>
                </ul>
                <p>If you did not make this update, please contact our support team immediately.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }

        // Redirect back to the profile page
        header("Location: ../client_profile.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update profile. Please try again.";
        header("Location: ../client_profile.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed without POST
    header("Location: ../client_profile.php");
    exit;
}
?>