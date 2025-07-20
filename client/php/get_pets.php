<?php
// Database connection
include '../../database/db_connection.php';

session_start();

// Check if session is set
if (!isset($_SESSION['client_id'])) {
    echo json_encode(['error' => 'Client not logged in']);
    exit;
}

// Get the logged-in client's ID
$client_id = $_SESSION['client_id'];

// Fetch pets belonging to the client
$conn = new mysqli('127.0.0.1:3306', 'u784320783_qualipaws', 'Qualipaws#12345678', 'u784320783_db_qualipaws');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}


$sql = "SELECT id, name, type, breed, weight FROM pets WHERE client_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    $pets[] = $row;
}

header('Content-Type: application/json');
echo json_encode($pets, JSON_PRETTY_PRINT);

$stmt->close();
$conn->close();
?>