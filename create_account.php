<?php
// Include database connection
include('database/db_connection.php');

// Initialize variables for form fields
$full_name = $age = $contact_number = $email = $address = $username = $password = $security_question = $security_answer = $role = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form input
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Password hashing
    $security_question = $_POST['security_question'];
    $security_answer = $_POST['security_answer'];
    $role = $_POST['role'];

    // Validation (basic example)
    if (empty($full_name) || empty($email) || empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    // Check if the username or email already exists in the database
    $check_query = "SELECT * FROM client WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username or Email is already taken.";
    }

    // If no errors, insert into the database
    if (empty($errors)) {
        $insert_query = "INSERT INTO client (full_name, age, contact_number, email, address, username, password, security_question, security_answer, role, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sissssssss", $full_name, $age, $contact_number, $email, $address, $username, $password, $security_question, $security_answer, $role);

        if ($stmt->execute()) {
            echo "Account created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>

<body>

    <h2>Create Account</h2>

    <?php
    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
    ?>

    <form method="POST" action="create_account.php">
        <label for="full_name">Full Name:</label><br>
        <input type="text" name="full_name" id="full_name" value="<?php echo $full_name; ?>" required><br><br>

        <label for="age">Age:</label><br>
        <input type="number" name="age" id="age" value="<?php echo $age; ?>" required><br><br>

        <label for="contact_number">Contact Number:</label><br>
        <input type="text" name="contact_number" id="contact_number" value="<?php echo $contact_number; ?>"><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" id="email" value="<?php echo $email; ?>" required><br><br>

        <label for="address">Address:</label><br>
        <textarea name="address" id="address"><?php echo $address; ?></textarea><br><br>

        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label for="security_question">Security Question:</label><br>
        <input type="text" name="security_question" id="security_question"
            value="<?php echo $security_question; ?>"><br><br>

        <label for="security_answer">Security Answer:</label><br>
        <input type="text" name="security_answer" id="security_answer" value="<?php echo $security_answer; ?>"><br><br>

        <label for="role">Role:</label><br>
        <select name="role" id="role">
            <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="vet" <?php echo ($role == 'vet') ? 'selected' : ''; ?>>Vet</option>
        </select><br><br>


        <button type="submit">Create Account</button>
    </form>

</body>

</html>