<?php
include '../../database/db_connection.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['client_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get the JSON payload
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['bookingId'])) {
    echo json_encode(['error' => 'Booking ID is required']);
    exit;
}

$bookingId = $data['bookingId'];
$clientId = $_SESSION['client_id'];

// Connect to the database
$conn = new mysqli('127.0.0.1:3306', 'u784320783_qualipaws', 'Qualipaws#12345678', 'u784320783_db_qualipaws');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Verify that the booking belongs to the logged-in client and is cancellable
$sql = "SELECT status FROM bookings WHERE id = ? AND client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $clientId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Booking not found or not cancellable']);
    $stmt->close();
    $conn->close();
    exit;
}

$booking = $result->fetch_assoc();
if ($booking['status'] === 'Approved') {
    echo json_encode(['error' => 'Approved bookings cannot be canceled']);
    $stmt->close();
    $conn->close();
    exit;
}

// Delete the booking
$sql = "DELETE FROM bookings WHERE id = ? AND client_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $clientId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to cancel booking']);
}

$stmt->close();
$conn->close();
?>