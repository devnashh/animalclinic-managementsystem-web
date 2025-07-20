<?php
// Database connection
include '../../database/db_connection.php';
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch data
$query = "SELECT * FROM bookings";
$result = $conn->query($query);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="report.csv"');

$output = fopen('php://output', 'w');

// Write column headers
fputcsv($output, ['ID', 'Client ID', 'Pet ID', 'Service Type', 'Appointment Date', 'Appointment Time', 'Status', 'Created At', 'Updated At']);

// Write rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>