<?php
require_once '../../database/db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists
    $query = "SELECT * FROM client WHERE verification_token = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Token is valid, activate the account
            $updateQuery = "UPDATE client SET is_verified = 1, verification_token = NULL WHERE verification_token = ?";
            if ($updateStmt = $conn->prepare($updateQuery)) {
                $updateStmt->bind_param("s", $token);
                $updateStmt->execute();

                if ($updateStmt->affected_rows == 1) {
                    echo "Account successfully verified!";
                } else {
                    echo "Error: Unable to verify account.";
                }
            }
        } else {
            echo "Invalid or expired token.";
        }

        $stmt->close();
    }
} else {
    echo "No token provided.";
}
?>