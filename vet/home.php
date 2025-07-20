<?php
// Start the session
session_start();

// Assuming admin's name is stored in session when logged in
$vet = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if (!isset($_SESSION['client_id'])) {
    header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
    exit;
}
// Database connection
$conn = new mysqli('127.0.0.1:3306', 'u784320783_qualipaws', 'Qualipaws#12345678', 'u784320783_db_qualipaws');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch data counts
$total_users = $conn->query("SELECT COUNT(*) AS count FROM client")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) AS count FROM bookings")->fetch_assoc()['count'];
$total_archived = $conn->query("SELECT COUNT(*) AS count FROM archived_bookings ")->fetch_assoc()['count'];
$total_pending = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];
$total_approved = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status = 'approved'")->fetch_assoc()['count'];
$total_rejected = $conn->query("SELECT COUNT(*) AS count FROM bookings WHERE status = 'rejected'")->fetch_assoc()['count'];

// Fetch total users
$result_users = $conn->query("SELECT COUNT(*) AS total_users FROM client");
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch approved bookings
$result_approved = $conn->query("SELECT COUNT(*) AS total_approved FROM bookings WHERE status = 'approved'");
$total_approved = $result_approved->fetch_assoc()['total_approved'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background-image: url('../media/paw_bg.png');
            background-size: cover;
            background-repeat: repeat;
            background-size: 750px;
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

        .card {
            border: none;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            width: 300px;
            height: 160px;
        }

        .card h5 {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .icon {
            font-size: 2.5rem;
        }
    </style>
</head>

<body class="d-flex">
    
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
        <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>
        <a href="appointment_list.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
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


        <div class="vet-info text-dark mt-3 text-center">
            <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
            <div>
                <?php echo htmlspecialchars($vet); ?>
            </div>
            <small class="text-muted">
                <?php
                if ($vet) {
                    $query = "SELECT role FROM client WHERE username = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $vet);
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
    <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-house-door me-1"></i> Dashboard
    </a>
    <a href="appointment_list.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-calendar me-1"></i> Appointments
    </a>
    <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-person me-1"></i> Registered Users
    </a>
    <a href="add_pet.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-book me-1"></i> Pet Records
    </a>
    <a href="settings.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-gear me-1"></i> Settings
    </a>
    <a href="../database/logout.php" class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5" onclick="return confirm('Are you sure you want to logout?');">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>

    <div class="vet-info text-dark mt-3 text-center">
        <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
        <div>
            <?php echo htmlspecialchars($vet); ?>
        </div>
        <small class="text-muted">
            <?php
            if ($vet) {
                $query = "SELECT role FROM client WHERE username = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $vet);
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
    <div class="container flex-grow-1 p-4" style="font-family: 'Nunito', sans-serif;">
        <h1>Welcome to the Veterinarian Panel</h1>
        <h3>Dashboard</h3>

        <!-- Cards Section -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-people icon text-primary"></i>
                    <h5>Total Users</h5>
                    <p class="fs-3"><?php echo $total_users; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-calendar-event icon text-success"></i>
                    <h5>Total Bookings</h5>
                    <p class="fs-3"><?php echo $total_bookings; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-archive icon text-secondary"></i>
                    <h5>Archived Bookings</h5>
                    <p class="fs-3"><?php echo $total_archived; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-hourglass-split icon text-warning"></i>
                    <h5>Pending Bookings</h5>
                    <p class="fs-3"><?php echo $total_pending; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-check-circle icon text-success"></i>
                    <h5>Approved Bookings</h5>
                    <p class="fs-3"><?php echo $total_approved; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <i class="bi bi-x-circle icon text-danger"></i>
                    <h5>Rejected Bookings</h5>
                    <p class="fs-3"><?php echo $total_rejected; ?></p>
                </div>
            </div>
        </div>

        <script>
            // Data from PHP
            const totalUsers = <?php echo $total_users; ?>;
            const totalApprovedBookings = <?php echo $total_approved; ?>;

            // Booking Statistics Chart
            const ctxBookings = document.getElementById('bookingsChart').getContext('2d');
            new Chart(ctxBookings, {
                type: 'bar', // Use doughnut, bar, or pie chart
                data: {
                    labels: ['Approved Bookings', 'Other Bookings'],
                    datasets: [{
                        data: [totalApprovedBookings, 100 - totalApprovedBookings], // Example values
                        backgroundColor: ['#4caf50', '#f1f1f1'], // Colors
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Bookings Statistics' }
                    }
                }
            });

            // User Statistics Chart
            const ctxUsers = document.getElementById('usersChart').getContext('2d');
            new Chart(ctxUsers, {
                type: 'bar',
                data: {
                    labels: ['Total Users'],
                    datasets: [{
                        label: 'Number of Users',
                        data: [totalUsers],
                        backgroundColor: ['#007bff'], // Blue color for bars
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'User Statistics' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>