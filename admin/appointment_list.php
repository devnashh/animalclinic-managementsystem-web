<?php
// Fetch appointments from the database
include '../database/db_connection.php'; // Adjust path to your database connection
session_start();

// Ensure only authenticated users can access this page
if (!isset($_SESSION['client_id']) && !isset($_SESSION['username'])) {
    header("Location: ../accounts/signin.php");
    exit;
}

$admin = $_SESSION['username'] ?? null; // Get logged-in admin/vet username

// Initialize search filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
// SQL query to fetch both walk-in and online bookings
$query = "SELECT 
            b.id, 
            b.booking_type,  
            b.service_type, 
            b.appointment_date, 
            b.appointment_time, 
            b.status, 
            b.additional_notes, 
            COALESCE(c.full_name, b.walkin_customer_name) AS client_name,
            COALESCE(c.contact_number, b.walkin_customer_contact) AS contact_number,
            COALESCE(p.name, b.pet_name) AS pet_name, 
            COALESCE(p.age, 'N/A') AS pet_age, 
            COALESCE(p.sex, 'N/A') AS pet_sex, 
            COALESCE(p.breed, 'N/A') AS pet_breed,
            COALESCE(p.type, b.pet_type, 'N/A') AS pet_type  -- Fix for pet_type
          FROM bookings b
          LEFT JOIN client c ON b.client_id = c.id
          LEFT JOIN pets p ON b.pet_id = p.id";

// Apply search filter if present
if (!empty($search_query)) {
    $query .= " WHERE (c.full_name LIKE ? OR b.walkin_customer_name LIKE ?)";
}
if ($filter == 'walk-in') {
    $query .= " WHERE b.booking_type = 'walk-in' ORDER BY b.created_at DESC";
} elseif ($filter == 'online') {
    $query .= " WHERE b.booking_type = 'online' ORDER BY b.created_at DESC";
} elseif ($filter == 'latest') {
    $query .= " ORDER BY b.created_at DESC";
} elseif ($filter == 'oldest') {
    $query .= " ORDER BY b.created_at ASC";
} else {
    $query .= " ORDER BY b.created_at DESC"; // Default to latest
}
$result = mysqli_query($conn, $query);

// Prepare statement to prevent SQL injection
$stmt = mysqli_prepare($conn, $query);
if (!empty($search_query)) {
    $search_param = '%' . $search_query . '%';
    mysqli_stmt_bind_param($stmt, 'ss', $search_param, $search_param);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('../media/paw_bg.png');
            /* Adjust the path if needed */
            background-size: cover;
            background-repeat: repeat;
            background-size: 750px;
            /* Adjust size as desired */
        }

        .sidebar {
            background-color: #BBE5F5;
            min-height: 100vh;
        }

        .sidebar img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }

        .sidebar a {
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #389850;
            color: #ffffff !important;
        }

        .logout:hover {
            background-color: #f5c6cb !important;
            color: #721c24 !important;
        }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar -->
    <div class="d-fluid">
<button class="btn btn-primary d-md-none mx-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
    <i class="bi bi-list"></i>
</button>
</div>
<!-- Sidebar (Offcanvas for Mobile, Always Visible on Larger Screens) -->
<div class="offcanvas offcanvas-start d-md-none" id="sidebarMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column align-items-center">
        <img src="../media/qplogo.jpg" alt="Logo" width="150px" height="150px" class="mb-3">
        <a href="home.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>
        <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-calendar me-2"></i> Appointments
        </a>
        <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-person me-2"></i> Registered Users
        </a>
        <a href="add_pet.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-book me-2"></i> Pet Records
        </a>
        <a href="settings.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-gear me-2"></i> Settings
        </a>
        <a href="../database/logout.php" class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5" onclick="return confirm('Are you sure you want to logout?');">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
        <div class="admin-info text-dark mt-3 text-center">
            <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
            <div>
                <?php echo htmlspecialchars($admin); ?>
            </div>
            <small class="text-muted">
                <?php
                if ($admin) {
                    $query = "SELECT role FROM client WHERE username = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $admin);
                    $stmt->execute();
                    $stmt->bind_result($role);
                    $stmt->fetch();
                    echo htmlspecialchars($role);
                    $stmt->close();
                } else {
                    echo "Unknown Role";
                }
                ?>
            </small>
        </div>
    </div>
</div>

<!-- Sidebar for Large Screens (Always Visible) -->
<div class="sidebar d-none d-md-flex flex-column align-items-center p-3">
    <img src="../media/qplogo.jpg" alt="Logo" class="mb-3">
    <a href="home.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
    <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-calendar me-2"></i> Appointments
    </a>
    <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-person me-2"></i> Registered Users
    </a>
    <a href="add_pet.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-book me-2"></i> Pet Records
    </a>
    <a href="settings.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-gear me-2"></i> Settings
    </a>
   <a href="../database/logout.php" class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5" onclick="return confirm('Are you sure you want to logout?');">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    <div class="admin-info text-dark mt-3 text-center">
        <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
        <div>
            <?php echo htmlspecialchars($admin); ?>
        </div>
        <small class="text-muted">
            <?php
            if ($admin) {
                $query = "SELECT role FROM client WHERE username = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $admin);
                $stmt->execute();
                $stmt->bind_result($role);
                $stmt->fetch();
                echo htmlspecialchars($role);
                $stmt->close();
            } else {
                echo "Unknown Role";
            }
            ?>
        </small>
    </div>
</div>

</div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4" style="font-family: 'Nunito', sans-serif;">

        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }

        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']); // Remove message after displaying
        }

        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']); // Remove message after displaying
        }
        ?>
        <?php

        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>
        <h1 class=" mb-3">Appointment List</h1>

        <!-- Search Bar -->
        <div class="mb-4" style="width: 400px; height: 40px;">
            <div class="input-group">
                <!-- Icon inside the input group -->
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icon for search -->
                </span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search for appointments.."
                    onkeyup="searchUsers()">
            </div>
        </div>

        <!-- Button to Trigger Walk-In Booking Modal -->
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#walkinBookingModal">
            Book Walk-In Appointment
        </button>
        <form method="GET" action="" class="mt-3 mb-1">
            <h5> Filter Appointment</h5>
            <select name="filter" onchange="this.form.submit()">
                <option value="">All Appointments</option>
                <option value="online" <?php if ($filter == 'online')
                    echo 'selected'; ?>>Online</option>
                <option value="latest" <?php if ($filter == 'latest')
                    echo 'selected'; ?>>Latest</option>
                <option value="oldest" <?php if ($filter == 'oldest')
                    echo 'selected'; ?>>Oldest</option>
                <option value="walk-in" <?php if ($filter == 'walk-in')
                    echo 'selected'; ?>>Walk-in</option>
            </select>
        </form>
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Client</th>
                    <th>Pet</th>
                    <th>Service</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Booking Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pet_name'] ? $row['pet_name'] : 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['appointment_date'] . ' ' . $row['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_type']); ?></td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewModal<?php echo $row['id']; ?>">View</button>
                                <!-- Dropdown for updating status -->
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                        id="statusDropdown<?php echo $row['id']; ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown<?php echo $row['id']; ?>">
                                        <li><a class="dropdown-item update-status" href="#"
                                                data-id="<?php echo $row['id']; ?>" data-status="Approved">Approve</a></li>
                                        <li><a class="dropdown-item update-status" href="#"
                                                data-id="<?php echo $row['id']; ?>" data-status="Rejected">Reject</a></li>
                                        <li><a class="dropdown-item update-status" href="#"
                                                data-id="<?php echo $row['id']; ?>" data-status="Pending">Pending</a></li>
                                        <li>
                                            <form method="POST" action="php/archived_bookings_backend.php">
                                                <input type="hidden" name="booking_id"
                                                    value="<?= htmlspecialchars($row['id']); ?>">
                                                <button type="submit" class="btn btn-success btn-sm mx-3">
                                                    Done
                                                </button>
                                            </form>
                                        </li>
                                    </ul>

                                </div>
                                <form method="post" action="php/notify_user.php">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-secondary btn-sm">
                                        Notify
                                    </button>
                                </form>

                                <form method="POST" action="php/archived_bookings_backend.php" class="d-inline-block ms-3">
                                    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to archive this booking?');">
                                        Archive
                                    </button>
                                </form>
                        </td>
                    </tr>

                    <!-- Modal for Viewing Details -->
                    <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1"
                        aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel">Appointment Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Booking id no.:</strong> #QPC -
                                        <?php echo htmlspecialchars($row['id']); ?>
                                    </p>
                                    <p><strong>Client Name:</strong> <?php echo htmlspecialchars($row['client_name']); ?>
                                    </p>
                                    <p><strong>Contact Number:</strong>
                                        <?php echo htmlspecialchars($row['contact_number']); ?></p>
                                    <p><strong>Pet Name:</strong>
                                        <?php echo htmlspecialchars($row['pet_name'] ? $row['pet_name'] : 'N/A'); ?></p>
                                    <p><strong>Pet Type:</strong>
                                        <?php echo htmlspecialchars($row['pet_type'] ?? 'N/A'); ?>
                                    </p>

                                    <p><strong>Service:</strong> <?php echo htmlspecialchars($row['service_type']); ?></p>
                                    <p><strong>Appointment Date:</strong>
                                        <?php echo htmlspecialchars($row['appointment_date'] . ' ' . $row['appointment_time']); ?>
                                    </p>
                                    <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                                    <p><strong>Booking Type:</strong> <?php echo htmlspecialchars($row['booking_type']); ?>
                                    </p> <!-- Display Booking Type -->
                                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($row['additional_notes']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
        <!-- Walk-In Booking Modal -->
        <div class="modal fade" id="walkinBookingModal" tabindex="-1" aria-labelledby="walkinBookingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="walkinBookingModalLabel">Walk-In Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="walkinBookingForm" action="php/process_walkin_booking.php" method="POST">
                        <div class="modal-body">
                            <!-- Walk-In Customer Information -->
                            <div class="mb-3">
                                <label for="walkinCustomerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="walkinCustomerName"
                                    name="walkin_customer_name" placeholder="Enter full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="walkinCustomerContact" class="form-label">Customer Contact</label>
                                <input type="text" class="form-control" id="walkinCustomerContact"
                                    name="walkin_customer_contact" placeholder="Enter contact number" required>
                            </div>
                            <div class="mb-3">
                                <label for="walkinCustomerEmail" class="form-label">Customer Email</label>
                                <input type="text" class="form-control" id="walkinCustomerEmail"
                                    name="walkin_customer_email" placeholder="Enter email" required>
                            </div>
                            <!-- Pet Details -->
                            <div class="mb-3">
                                <label for="walkinPetName" class="form-label">Pet Name</label>
                                <input type="text" class="form-control" id="walkinPetName" name="pet_name"
                                    placeholder="Enter pet name" required>
                            </div>
                            <div class="mb-3">
                                <label for="walkinPetType" class="form-label">Pet Type</label>
                                <select class="form-select" id="walkinPetType" name="pet_type" required>
                                    <option value="" disabled selected>Select pet type</option>
                                    <option value="Dog">Dog</option>
                                    <option value="Cat">Cat</option>
                                </select>
                            </div>

                            <!-- Appointment Details -->
                            <div class="mb-3">
                                <label for="walkinServiceType" class="form-label">Service Type</label>
                                <select class="form-select" id="walkinServiceType" name="service_type" required>
                                    <option value="" disabled selected>Select service type</option>
                                    <option value="Others">Consultation</option>
                                    <option value="Others">Surgery</option>
                                    <option value="Grooming">Grooming</option>
                                    <option value="Grooming">Deworming</option>
                                    <option value="Vaccination">Vaccination</option>
                                    <option value="Others">Supplies</option>
                                    <option value="Others">Home Service</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="walkinAppointmentDate" class="form-label">Appointment Date</label>
                                <input type="date" class="form-control" id="walkinAppointmentDate"
                                    name="appointment_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="walkinAppointmentTime" class="form-label">Appointment Time</label>
                                <input type="time" class="form-control" id="walkinAppointmentTime"
                                    name="appointment_time" required>
                            </div>
                            <div class="mb-3">
                                <label for="walkinAdditionalNotes" class="form-label">Additional Notes</label>
                                <textarea class="form-control" id="walkinAdditionalNotes" name="additional_notes"
                                    rows="3" placeholder="Enter any additional notes (optional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Search Functionality
            function searchUsers() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById('searchInput');
                filter = input.value.toLowerCase();
                table = document.querySelector('table'); // Search in the main table (archived users table)
                tr = table.getElementsByTagName('tr');

                for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
                    td = tr[i].getElementsByTagName('td');
                    if (td) {
                        var found = false;
                        for (var j = 0; j < td.length; j++) { // Loop through each column in the row
                            txtValue = td[j].textContent || td[j].innerText;
                            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                found = true;
                                break;
                            }
                        }
                        tr[i].style.display = found ? '' : 'none'; // Show row if match is found
                    }
                }
            }
        </script>

        <script>
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', function () {
                    const bookingId = this.getAttribute('data-id');
                    const newStatus = this.getAttribute('data-status');

                    fetch('php/update_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: bookingId, status: newStatus })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Status updated successfully!');
                                location.reload();
                            } else {
                                alert('Failed to update status.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });

        </script>
        <script>
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function () {
                    const bookingId = this.querySelector("input[name='booking_id']").value;
                    console.log("Submitting Booking ID:", bookingId);

                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>