<?php
include('../../database/db_connection.php');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch archived bookings
try {
    $stmt = $pdo->prepare("SELECT * FROM archived_bookings ORDER BY appointment_date DESC");
    $stmt->execute();

    $archivedBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($archivedBookings);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>