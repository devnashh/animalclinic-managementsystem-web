<?php
include '../../database/db_connection.php'; // Ensure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];

    if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES['pet_image']['name'];
        $img_tmp = $_FILES['pet_image']['tmp_name'];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed_ext)) {
            $new_img_name = uniqid('pet_', true) . '.' . $img_ext;
            $upload_path = '../../media/pet_images/' . $new_img_name;

            if (move_uploaded_file($img_tmp, $upload_path)) {
                // Update profile picture in the database
                $sql = "UPDATE pets SET profile_picture = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $new_img_name, $pet_id);

                if ($stmt->execute()) {
                    header("Location: ../pet_profile?success=1"); // Redirect to refresh the image
                    exit();
                } else {
                    echo "Database update failed";
                }
                $stmt->close();
            } else {
                echo "File upload failed";
            }
        } else {
            echo "Invalid file type";
        }
    } else {
        echo "No file uploaded";
    }
}
?>
