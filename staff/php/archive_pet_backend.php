<?php
include '../../database/db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    $conn->begin_transaction();

    try {
        // Fetch the pet's details from the 'pets' table
        $query = "SELECT * FROM pets WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $pet_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pet = $result->fetch_assoc();
        $stmt->close();

        if ($pet) {
            // Archive related bookings
            $query = "DELETE FROM bookings WHERE pet_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $pet_id);
            $stmt->execute();
            $stmt->close();

            // Insert the pet details into the 'archive_pets' table
            $query = "INSERT INTO archive_pets (id, name, age, sex, color, type, breed, profile_picture, created_at, updated_at)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isssssssss", $pet['id'], $pet['name'], $pet['age'], $pet['sex'], $pet['color'], $pet['type'], $pet['breed'], $pet['profile_picture'], $pet['created_at'], $pet['updated_at']);
            $stmt->execute();
            $stmt->close();

            // Delete the pet record from the 'pets' table
            $query = "DELETE FROM pets WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $pet_id);
            $stmt->execute();
            $stmt->close();

            $conn->commit();

            // Redirect with a success message
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Pet archived successfully!'];
            header("Location: ../add_pet.php");
            exit;
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Pet not found!'];
            header("Location: ../add_pet.php");
            exit;
        }
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error archiving pet: ' . $e->getMessage()];
        header("Location: ../add_pet.php");
        exit;
    }
}

?>