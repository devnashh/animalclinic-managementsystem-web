<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../database/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: ../signin.php");
        exit;
    }

    // Validate reCAPTCHA
    $recaptchaSecret = '6Lc8deQqAAAAAFBxZyO0_s1Vwc2JVRCxxTYdjORR';
    $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);

    //  if (!$responseKeys['success']) {
    //     $_SESSION['error'] = "Captcha validation failed. Please try again.";
    //      header("Location: ../signin.php");
    //      exit;
    //  }

    // Fetch user from the database
    $sql = "SELECT id, username, password, role, is_verified FROM client WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Check if the account is verified
            if ($user['is_verified'] == 0) {
                $_SESSION['error'] = "Your account is not verified. Please check your email and verify your account.";
                header("Location: ../signin.php");
                exit;
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['client_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'client':
                        header("Location: ../../client/home.php");
                        break;
                    case 'admin':
                        header("Location: ../../admin/home.php");
                        break;
                    case 'staff':
                        header("Location: ../../staff/home.php");
                        break;
                    case 'vet':
                        header("Location: ../../vet/home.php");
                        break;
                    default:
                        $_SESSION['error'] = "Invalid user role.";
                        header("Location: ../signin.php");
                }
                exit;
            } else {
                $_SESSION['error'] = "Invalid password.";
                header("Location: ../signin.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "User not found.";
            header("Location: ../signin.php");
            exit;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: ../signin.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../signin.php");
    exit;
}
?>
