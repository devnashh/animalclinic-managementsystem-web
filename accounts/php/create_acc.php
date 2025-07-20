<?php
require_once '../../database/db_connection.php';
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? null;
    $age = $_POST['age'] ?? null;
    $contact_number = $_POST['contact_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $address = $_POST['address'] ?? null;
    $username = $_POST['username'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (!$full_name || !$age || !$contact_number || !$email || !$address || !$username || !$password) {
        die("Please fill in all required fields.");
    }

    // Check for duplicate email or username
    $checkQuery = "SELECT * FROM client WHERE email = ? OR username = ?";
    if ($stmt = $conn->prepare($checkQuery)) {
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: ../signup.php?message=Email or Username has been taken. Please choose another.");
            $stmt->close();
            exit;
        }

        $stmt->close();
    } else {
        die("SQL preparation error: " . $conn->error);
    }

    // Generate a unique verification token
    $verification_token = bin2hex(random_bytes(16));

    // SQL to insert the data with verification_token
    $sql = "INSERT INTO client (full_name, age, contact_number, email, address, username, password, verification_token) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sissssss", $full_name, $age, $contact_number, $email, $address, $username, $password, $verification_token);

        if ($stmt->execute()) {
            // Send verification email
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'qualipawsanimalhealthclinic@gmail.com'; // Your Gmail address
                $mail->Password = 'zewlrtzjfdsumprl'; // Your Gmail app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email content
                $mail->setFrom('qualipawsanimalhealthclinic@gmail.com', 'Qualipaws Animal Health Clinic');
                $mail->addAddress($email, $full_name);
                $mail->Subject = 'Verify Your Email Address';
                $mail->isHTML(true);
                $mail->Body = "
                    <h3>Thank you for registering, $full_name!</h3>
                    <p>Please verify your email address by clicking the button below:</p>
                    <a href='https://lightyellow-hyena-715358.hostingersite.com/accounts/php/verify_account.php?token=$verification_token' 
                        style='padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none;'>
                        Verify My Account
                    </a>
                    <p>If you didn't create this account, you can safely ignore this email.</p>
                ";

                $mail->send();
                header("Location: ../signin.php?message=Verification email sent! Please check your inbox.");
                exit;
            } catch (Exception $e) {
                echo "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        die("SQL preparation error: " . $conn->error);
    }
}
?>