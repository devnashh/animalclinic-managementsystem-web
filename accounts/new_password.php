<?php
// Include database connection
include_once '../database/db_connection.php';

$message = ""; // Variable to store success message

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and is valid (not expired)
    $query = "SELECT id, email, reset_expiration FROM client WHERE verification_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token found, check if it's expired
        $user = $result->fetch_assoc();
        $current_time = date('Y-m-d H:i:s');

        if ($current_time > $user['reset_expiration']) {
            $message = "The reset token has expired. Please request a new password reset.";
        } else {
            // Token is valid, allow password reset
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $new_password = $_POST['password'];
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Hash the new password

                // Update the password in the database
                $updateQuery = "UPDATE client SET password = ?, verification_token = NULL, reset_expiration = NULL WHERE verification_token = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ss", $hashed_password, $token);
                $stmt->execute();

                $message = "Your password has been successfully updated! Redirecting to sign-in page...";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '../signin.php';
                        }, 3000);
                      </script>";
            }
        }
    } else {
        $message = "Invalid or expired token.";
    }
} else {
    $message = "Invalid token.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="card">
        <h3 class="text-center mb-4">Reset Your Password</h3>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form to enter new password -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="password" class="form-label">Enter New Password</label>
                <input type="password" class="form-control" id="password" name="password" required
                    placeholder="Enter your new password" minlength="8" pattern=".{8,}"
                    title="Password must be at least 8 characters long">
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
    <script>
        function validatePassword() {
            let password = document.getElementById("password").value;
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>