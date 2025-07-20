<?php
// Database connection
$host = ''; // Your database host
$dbname = ''; // Your database name
$username = ''; // Your database username
$password = ''; // Your database password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>