<?php
$filter = ''; // Default value for $filter

// If you're expecting $filter from a form submission or URL parameter, do this:
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
}
// Start the session
session_start();

// Assuming admin's name is stored in session when logged in
$admin = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if (!isset($_SESSION['client_id'])) {
    header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
    exit;
}
// Database connection
$conn = new mysqli('', '', '', '');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
// Bookings statistics for the graph
$bookings_query = "SELECT DATE(appointment_date) AS date, COUNT(*) AS total FROM bookings WHERE 1=1";

if ($filter == 'day') {
    $bookings_query .= " AND DATE(appointment_date) = CURDATE()";
} elseif ($filter == 'week') {
    $bookings_query .= " AND YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'month') {
    $bookings_query .= " AND MONTH(appointment_date) = MONTH(CURDATE()) AND YEAR(appointment_date) = YEAR(CURDATE())";
} elseif ($filter == 'year') {
    $bookings_query .= " AND YEAR(appointment_date) = YEAR(CURDATE())";
}

$bookings_query .= " GROUP BY DATE(appointment_date)";
$bookings_result = $conn->query($bookings_query);

$bookings_dates = [];
$bookings_totals = [];
while ($row = $bookings_result->fetch_assoc()) {
    $bookings_dates[] = $row['date'];
    $bookings_totals[] = $row['total'];
}

// Users statistics for the graph
$users_query = "SELECT DATE(created_at) AS date, COUNT(*) AS total FROM client WHERE 1=1";

if ($filter == 'day') {
    $users_query .= " AND DATE(created_at) = CURDATE()";
} elseif ($filter == 'week') {
    $users_query .= " AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter == 'month') {
    $users_query .= " AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
} elseif ($filter == 'year') {
    $users_query .= " AND YEAR(created_at) = YEAR(CURDATE())";
}

$users_query .= " GROUP BY DATE(created_at)";
$users_result = $conn->query($users_query);

$users_dates = [];
$users_totals = [];
while ($row = $users_result->fetch_assoc()) {
    $users_dates[] = $row['date'];
    $users_totals[] = $row['total'];
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
            height: 155px;
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
    <!-- Toggle Button (Visible Only on Small Screens) -->
    <!-- Toggle Button (Visible Only on Mobile) -->
    <div class="d-fluid">
        <button class="btn btn-primary d-md-none mx-1" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu">
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
            <a href="../database/logout.php"
                class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5"
                onclick="return confirm('Are you sure you want to logout?');">
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
        <a href="../database/logout.php"
            class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5"
            onclick="return confirm('Are you sure you want to logout?');">
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
    <div class="container flex-grow-1 p-4" style="font-family: 'Nunito', sans-serif;">
        <h3>Welcome to the Admin Panel</h5>
            <?php
            if (isset($_SESSION['success'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            } ?>
            <!-- Cards Section -->
            <div class="row g-3">
                <a href="registered_users.php" class="dashLink" style="text-decoration:none">
                    <div class="col-md-4">
                        <div class="card text-center p-4">
                            <i class="bi bi-people icon text-primary"></i>
                            <h5>Total Users</h5>
                            <p class="fs-3"><?php echo $total_users; ?></p>
                        </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="#" class="dashLink" style="text-decoration:none">
                    <div class="card text-center p-4">
                        <i class="bi bi-calendar-event icon text-success"></i>
                        <h5>Total Bookings</h5>
                        <p class="fs-3"><?php echo $total_bookings; ?></p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="settings.php" class="dashLink" style="text-decoration:none">
                    <div class="card text-center p-4">
                        <i class="bi bi-archive icon text-secondary"></i>
                        <h5>Archived Bookings</h5>
                        <p class="fs-3"><?php echo $total_archived; ?></p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="appointment_list.php" class="dashLink" style="text-decoration:none">
                    <div class="card text-center p-4">
                        <i class="bi bi-hourglass-split icon text-warning"></i>
                        <h5>Pending Bookings</h5>
                        <p class="fs-3"><?php echo $total_pending; ?></p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="appointment_list.php" class="dashLink" style="text-decoration:none">
                    <div class="card text-center p-4">
                        <i class="bi bi-check-circle icon text-success"></i>
                        <h5>Approved Bookings</h5>
                        <p class="fs-3"><?php echo $total_approved; ?></p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="appointment_list.php" class="dashLink" style="text-decoration:none">
                    <div class="card text-center p-4">
                        <i class="bi bi-x-circle icon text-danger"></i>
                        <h5>Rejected Bookings</h5>
                        <p class="fs-3"><?php echo $total_rejected; ?></p>
                    </div>
                </a>
            </div>
    </div>
    <div class="mt-4">
        <div class="filter-buttons mb-3">
            <a href="?filter=day" class="btn btn-primary">Day</a>
            <a href="?filter=week" class="btn btn-primary">Week</a>
            <a href="?filter=month" class="btn btn-primary">Month</a>
            <a href="?filter=year" class="btn btn-primary">Year</a>
            <a href="?" class="btn btn-secondary">All Time</a>
        </div>

    </div>
    <!-- Info Button -->
    <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#infoModal">
        <strong class="bi bi-question-circle">
        </strong>
    </button>

    <!-- Statistics Graphs Section -->
    <div class="row">

        <div class="col-md-6">
            <canvas id="bookingsChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="usersChart"></canvas>
        </div>
    </div>
    <div class="mt-4">
        <a href="php/generate_report.php" class="btn btn-success">
            <i class="bi bi-download me-1"></i> Generate Report
        </a>
    </div>

    </div>
    <!-- Info Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">How the Graph and Filter Work?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Graph:</h6>
                    <ul>
                        <li>The graph provides a visual representation of appointment statistics, allowing you to easily
                            track and analyze trends over different time periods.</li>
                        <li>Hover over the graph elements to view detailed data points, such as the number of
                            appointments on a specific date.</li>
                        <li>The graph updates dynamically based on the selected filter option, giving you a more
                            customized view of the data.</li>
                        <li>This helps in identifying peak hours, popular services, and other valuable insights to
                            improve clinic operations.</li>
                    </ul>
                    <h6>Filter:</h6>
                    <ul>
                        <li>The filter enables you to narrow down the data displayed on the graph, making it easier to
                            focus on specific information.</li>
                        <li>You can filter by:
                            <ul>
                                <li><strong>Time Period:</strong> View data by day, week, month, or year to observe
                                    trends over different time frames.</li>
                                <li><strong>Service Type:</strong> Analyze the popularity of different services offered
                                    at the clinic.</li>
                                <li><strong>Status:</strong> Check the status of appointments, such as confirmed,
                                    canceled, or completed.</li>
                            </ul>
                        </li>
                        <li>When you select a filter option, the graph reloads with the relevant data while maintaining
                            your position on the page, ensuring a seamless user experience.</li>
                        <li>This feature is useful for making data-driven decisions, such as staffing schedules or
                            promotional campaigns for specific services.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        const usersCtx = document.getElementById('usersChart').getContext('2d');

        // Bookings Chart
        new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($bookings_dates); ?>,
                datasets: [{
                    label: 'Total Bookings',
                    data: <?php echo json_encode($bookings_totals); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Total Bookings' } }
                }
            }
        });

        // Users Chart
        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($users_dates); ?>,
                datasets: [{
                    label: 'New Users',
                    data: <?php echo json_encode($users_totals); ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: { title: { display: true, text: 'Date' } },
                    y: { title: { display: true, text: 'Total Users' } }
                }
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>