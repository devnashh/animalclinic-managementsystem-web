<?php
include '../database/db_connection.php';
// Start the session
session_start();

// Assuming admin's name is stored in session when logged in
// Example: $_SESSION['admin_name'] contains the logged-in admin's name
$vet = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if (!isset($_SESSION['client_id'])) {
    header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
    exit;
}

if ($vet) {
    $query = "SELECT full_name, username, age, contact_number, email, address, role FROM client WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $vet);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $vet_details = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch archived users
$query = "SELECT id, full_name, username, contact_number, email, address FROM archived_users";
$result = $conn->query($query);
$archived_users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $archived_users[] = $row;
    }
}
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

        .profile-card {
            max-width: 400px;
            margin: 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-card .bi-person-circle {
            margin-bottom: 0;
        }

        .profile-card h5 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .profile-card p {
            color: #555;
            font-size: 14px;
            margin: 0;
        }

        .profile-card hr {
            margin: 15px 0;
        }

        .profile-card .text-muted {
            font-size: 0.9rem;
        }

        button .bi {
            font-size: 1.2rem;
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
        <a href="home.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>
        <a href="appointment_list.php" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-calendar me-2"></i> Appointments
        </a>
        <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-person me-2"></i> Registered Users
        </a>
        <a href="add_pet.php" class="text-dark d-flex active align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-book me-2"></i> Pet Records
        </a>
        <a href="#" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
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
    <a href="home.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
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
    <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
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
    <div class="container mt-4" style="font-family: 'Nunito', sans-serif;">
        <?php if (isset($_GET['success_update']) && $_GET['success_update'] == 1): ?>
            <div class="alert alert-success">Information Updated Successfully.</div>
        <?php elseif (isset($_GET['error_update'])): ?>
            <div class="alert alert-danger">Failed to update information.</div>
        <?php endif; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                User restored successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                switch ($_GET['error']) {
                    case 1:
                        echo "Failed to restore user.";
                        break;
                    case 2:
                        echo "User not found in archive.";
                        break;
                    case 3:
                        echo "Invalid request. No user ID provided.";
                        break;
                    default:
                        echo "An unknown error occurred.";
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <h1 class="mb-3">Veterinarian Settings</h2>
            <div class="profile-card mb-5">
                <!-- Admin Profile Icon and Name -->
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-person-badge text-primary me-3" style="font-size: 3.5rem;"></i>
                    <div>
                        <h5 class="mb-0"><?php echo htmlspecialchars($vet_details['username']); ?></h5>
                        <p class="text-muted mb-0"><em><?php echo htmlspecialchars($vet_details['role']); ?></em></p>
                    </div>
                </div>

                <!-- Divider -->
                <hr>

                <!-- Admin Contact Details -->
                <?php if (!empty($vet_details)): ?>
                    <div class="text-start">
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($vet_details['contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($vet_details['email']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($vet_details['address']); ?></p>
                        <p><strong>Role:</strong> <?php echo htmlspecialchars($vet_details['role']); ?></p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mt-3" data-bs-toggle="modal"
                        data-bs-target="#editModal">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                <?php else: ?>
                    <p class="text-danger">No profile information found.</p>
                <?php endif; ?>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Admin Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="php/update_vet_info.php" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        value="<?php echo htmlspecialchars($vet_details['contact_number']); ?>"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo htmlspecialchars($vet_details['email']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"
                                        required><?php echo htmlspecialchars($vet_details['address']); ?></textarea>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <h2 class="mb-3">Options</h2>
            <!-- Change Password Users -->
            <div class="mb-3">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#passwordModal"
                    style="margin-right:23%">
                    <i class="bi bi-key me-2"></i>Update Password
                </button>
            </div>
            <!-- New Password Update Modal -->
            <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="php/update_password.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="passwordModalLabel">Update Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password"
                                        minlength="8" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- View Archived Bookings -->
            <div class="mb-3">
                <button class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#viewArchivedBookingsModal">
                    <i class="bi bi-archive-fill me-2"></i> View Archived Bookings
                </button>
            </div>
            <!-- View Archived Users -->
            <div class="mb-3">
                <button class="btn btn-secondary d-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#viewArchivedUsersModal">
                    <i class="bi bi-people-fill me-2"></i> View Archived Users
                </button>
            </div>


            <!-- View Archived Bookings Modal -->
            <div class="modal fade" id="viewArchivedBookingsModal" tabindex="-1" aria-labelledby="archivedBookingsLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="archivedBookingsLabel">Archived Bookings</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client ID</th>
                                        <th>Pet ID</th>
                                        <th>Service Type</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="archivedBookingsTable">
                                    <!-- Archived bookings will be dynamically loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Archived Users Modal -->
            <div class="modal fade" id="viewArchivedUsersModal" tabindex="-1"
                aria-labelledby="viewArchivedUsersModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width:1000px">
                    <div class="modal-content" style="width:1000px">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewArchivedUsersModalLabel">Archived Users</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($archived_users)): ?>
                                        <?php foreach ($archived_users as $user): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['contact_number']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['address']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No archived users found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                const passwordInput = document.getElementById('password');
                const passwordHelp = document.getElementById('passwordHelp');

                passwordInput.addEventListener('input', () => {
                    if (passwordInput.value.length < 8) {
                        passwordHelp.style.color = 'red';
                        passwordHelp.textContent = 'Password is too short.';
                    } else {
                        passwordHelp.style.color = 'green';
                        passwordHelp.textContent = 'Password is valid.';
                    }
                });
            </script>
            <script>
                function deleteAllStatistics() {
                    if (confirm("Are you sure you want to delete all data statistics for bookings and users? This action cannot be undone.")) {
                        // Call your backend endpoint to delete the statistics
                        alert("Data statistics deleted successfully.");
                    }
                }
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    setTimeout(function () {
                        let alert = document.querySelector(".alert");
                        if (alert) {
                            alert.style.transition = "opacity 0.5s";
                            alert.style.opacity = "0";
                            setTimeout(() => alert.remove(), 500);
                        }
                    }, 3000);
                });
            </script>

            <script src="js/archived_users.js"></script>
            <script src="js/archived_bookings.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>