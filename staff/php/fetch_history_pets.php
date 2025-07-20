<?php
// Include database connection
include '../../database/db_connection.php';

// Check if pet_id is set
if (isset($_GET['pet_id'])) {
    $pet_id = $_GET['pet_id'];

    // Query to get online bookings
    $online_query = $conn->prepare("
        SELECT * FROM archived_bookings 
        WHERE pet_id = ? AND booking_type = 'online'
        ORDER BY appointment_date DESC, appointment_time DESC
    ");
    $online_query->bind_param("i", $pet_id);
    $online_query->execute();
    $online_result = $online_query->get_result();

    // Query to get walk-in bookings
    $walkin_query = $conn->prepare("
        SELECT * FROM archived_bookings 
        WHERE pet_id = ? AND booking_type = 'walkin'
        ORDER BY appointment_date DESC, appointment_time DESC
    ");
    $walkin_query->bind_param("i", $pet_id);
    $walkin_query->execute();
    $walkin_result = $walkin_query->get_result();

    echo "<h4>Online Bookings</h4>";
    if ($online_result->num_rows > 0) {
        echo "<table class='table table-bordered table-striped'>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Additional Notes</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $online_result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['service_type'] . "</td>
                    <td>" . $row['appointment_date'] . "</td>
                    <td>" . date("h:i A", strtotime($row['appointment_time'])) . "</td>
                    <td>" . $row['additional_notes'] . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "";
    }
} else {
    echo "<p class='text-danger'>No pet ID provided.</p>";
}
?>