<?php
include_once '../../database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['pet_id'];
    $age = $_POST['age'];
    $color = $_POST['color'];
    $weight = $_POST['weight'];

    // Fetch current pet image
    $query = $conn->prepare("SELECT profile_picture FROM pets WHERE id = ?");
    $query->bind_param("i", $pet_id);
    $query->execute();
    $result = $query->get_result();
    $pet = $result->fetch_assoc();
    $current_image = $pet['profile_picture'];

    // Check if a new image was uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../../media/pet_images/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        // Validate file type
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $new_image = "../../media/pet_images/" . basename($_FILES["profile_picture"]["name"]);
            } else {
                echo json_encode(["error" => "Error uploading image."]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Only JPG, JPEG, PNG & GIF files are allowed."]);
            exit;
        }
    } else {
        // No new image uploaded, keep current image
        $new_image = $current_image;
    }

    // Update pet details
    $stmt = $conn->prepare("UPDATE pets SET age = ?, color = ?, weight = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("isssi", $age, $color, $weight, $new_image, $pet_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "new_image" => $new_image]);
    } else {
        echo json_encode(["error" => "Failed to update pet."]);
    }
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
