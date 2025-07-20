<?php
require '../../database/db_connection.php'; // Your database connection
require '../../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate that both fields match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match"; // Set error session message
        header("Location: ../client_profile.php"); // Redirect back to the profile page
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Fetch user's email
    $email_query = "SELECT email FROM client WHERE id = ?";
    $email_stmt = $conn->prepare($email_query);
    $email_stmt->bind_param("i", $id);
    $email_stmt->execute();
    $email_result = $email_stmt->get_result();
    $email_row = $email_result->fetch_assoc();
    $user_email = $email_row['email'];

    // Update query with password
    $query = "UPDATE client SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $hashed_password, $id);

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
            $mail->addAddress($user_email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Update Notification';
            $mail->Body = "
                <h1>Password Updated Successfully</h1>
                <p>Your password has been updated. If this was not you, please contact support immediately.</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }

        header("Location: ../client_profile.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to update password. Please try again."; // Set error session message
        header("Location: ../client_profile.php"); // Redirect back to the profile page
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../client_profile.php"); // Redirect if accessed without POST
    exit;
}
?>