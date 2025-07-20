<?php
// get_bookings.php
include '../../database/db_connection.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['client_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get the logged-in user's ID
$client_id = $_SESSION['client_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1:3306', 'u784320783_qualipaws', 'Qualipaws#12345678', 'u784320783_db_qualipaws');
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Fetch bookings for the logged-in client
$sql = "SELECT b.id, b.service_type, b.appointment_date, b.appointment_time, b.additional_notes, b.status,
               p.name AS pet_name, p.type AS pet_type, p.breed AS pet_breed
        FROM bookings b
        JOIN pets p ON b.pet_id = p.id
        WHERE b.client_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

header('Content-Type: application/json');
echo json_encode($bookings, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>