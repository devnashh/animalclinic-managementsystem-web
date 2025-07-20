<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';
include_once '../../database/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $query = "SELECT id, full_name, email FROM client WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));

        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $updateQuery = "UPDATE client SET verification_token = ?, reset_expiration = ? WHERE email = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sss", $token, $expiry_time, $email);
        $stmt->execute();

        $resetLink = "https://lightyellow-hyena-715358.hostingersite.com/accounts/new_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Hello " . $user['full_name'] . ",\n\nTo reset your password, click on the following link:\n$resetLink\n\nIf you did not request this, please ignore this email.";

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
            $mail->addAddress($email, $user['full_name']);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            header('Location: ../forgot_password.php?success=' . urlencode("A password reset link has been sent to your email."));
        } catch (Exception $e) {
            header('Location: ../forgot_password.php?error=' . urlencode("Error sending the email. Mailer Error: {$mail->ErrorInfo}"));
        }
    } else {
        header('Location: ../forgot_password.php?error=' . urlencode("Email not found."));
    }
    exit();
}
?>