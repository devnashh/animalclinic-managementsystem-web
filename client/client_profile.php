<?php
session_start();

require_once '../database/db_connection.php'; // Adjust the path to your database connection file

if (!isset($_SESSION['client_id'])) {
    header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
    exit;
}

// Fetch user information
$client_id = $_SESSION['client_id'];
$sql = "SELECT username, full_name, age, contact_number, email, address FROM client WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>My profile</title>
    <style>
        .nav-item {
            margin-right: 30px;
            font-size: large;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 40px 20px;
            border-top: 1px solid #ddd;
        }

        .footer h6 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .footer p {
            margin: 0;
            color: #6c757d;
        }

        .footer .list-unstyled a {
            text-decoration: none;
            color: #6c757d;
        }

        .footer .list-unstyled a:hover {
            text-decoration: underline;
            color: #007bff;
        }

        .footer-bottom {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!--footer-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style=" font-family: 'Nunito Sans', 'sans-serif';">
        <div class="container">
            <!-- Logo and Text -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../media/qplogo.jpg" alt="Qualipaws Logo" style="height: 80px; width: 80px;"
                    class="rounded-circle">
                <div class="ms-2">
                    <div style="font-size: 18px; font-weight: bold;"><span style="color:#1E98AE">QUALI</span><span
                            style="color:#399751">PAWS</span></div>
                    <div style="font-size: 14px;color:#399751">Animal Health Clinic</div>
                </div>
            </a>
            <!-- Toggle Button for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="appointment.php">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="calendar.php">Calendar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pet_profile.php">Pet Profile</a>
                    </li>
                    <!-- Dropdown with User Icon -->
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person" style="font-size: 1.1rem;"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="client_profile.php">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../database/logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="container mt-5" style="font-family: 'Nunito Sans', sans-serif;">
        <?php
        if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert"
                style="margin: 20px auto; width: 80%;">
                <strong><?php echo $_SESSION['success']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); // Clear the success message after showing it ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert"
                style="margin: 20px auto; width: 80%;">
                <strong><?php echo $_SESSION['error']; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); // Clear the error message after showing it ?>
        <?php endif; ?>
        <!--content-->
        <h1 style="font-weight: 600;">My Profile</h1>
        <div class="card mt-2 p-4 shadow-sm">
            <div class="d-flex align-items-center">
                <!-- User Icon -->
                <div
                    style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; border: 2px solid #4CAF50; margin-right: 20px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5;">
                    <i class="fas fa-user" style="font-size: 40px; color: #4CAF50;"></i>
                </div>

                <!-- Name and Edit -->
                <div style="flex-grow: 1;">
                    <input type="text" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly
                        class="form-control-plaintext fs-5 fw-bold" style="border: none;">
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        <?php echo htmlspecialchars($user['username']); ?>
                    </p>
                </div>
                <a href="#" class="text-primary" style="text-decoration: none; font-size: 0.9rem;"
                    data-bs-toggle="modal" data-bs-target="#editModal">Edit <i class="fas fa-edit"></i></a>
            </div>
            <hr>
            <!-- User Details -->
            <div class="mt-4">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="email" class="form-label text-muted">Email Address:</label>
                        <input type="text" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly
                            class="form-control-plaintext">
                    </div>
                    <div class="col-sm-6">
                        <label for="address" class="form-label text-muted">Address:</label>
                        <input type="text" id="address" value="<?php echo htmlspecialchars($user['address']); ?>"
                            readonly class="form-control-plaintext">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-6">
                        <label for="contact_number" class="form-label text-muted">Contact Number:</label>
                        <input type="text" id="contact_number"
                            value="<?php echo htmlspecialchars($user['contact_number']); ?>" readonly
                            class="form-control-plaintext">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="php/update_profile.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden Input -->
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($client_id); ?>">

                        <!-- Form Input Fields -->
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" name="full_name" id="full_name" class="form-control"
                                value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" id="age" class="form-control"
                                value="<?php echo htmlspecialchars($user['age']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control"
                                value="<?php echo htmlspecialchars($user['contact_number']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" id="address" class="form-control"
                                value="<?php echo htmlspecialchars($user['address']); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Button to trigger Password Modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#passwordModal" style="margin-right:23%">
                            Update Password
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- New Password Update Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="php/update_password.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordModalLabel">Update Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden Input -->
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($client_id); ?>">

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Enter new password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                placeholder="Confirm new password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--footer-->
    <footer class="footer mt-3">
        <div class="container">
            <div class="row text-center text-md-start">
                <!-- About Us Section -->
                <div class="col-md-3 mb-3">
                    <h6>About us</h6>
                    <p>Your pet deserves quality care.</p>
                </div>
                <!-- Quick Link Section -->
                <div class="col-md-3 mb-3">
                    <h6>Quick Link</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                    </ul>
                </div>
                <!-- Contact Info Section -->
                <div class="col-md-3 mb-3">
                    <h6>Contact Info</h6>
                    <p>F. Halili National Road, Barangay Tungkong Mangga, <br> City of San Jose del Monte, Bulacan,
                        Philippines</p>
                    <p>0913 219 9347</p>
                    <p>qualipawsph@gmail.com</p>
                </div>
                <!-- Developers Section -->
                <div class="col-md-3 mb-3">
                    <h6>Developers</h6>
                    <p>Alberca, Jonas</p>
                    <p>Arizapa, John Alexander</p>
                    <p>Castillo, Charlie</p>
                    <p>Garcia, Jmswell</p>
                    <p>Wong, Shashie Mae</p>
                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                © 2024 QualiPaws. All rights reserved.
            </div>
        </div>
    </footer>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>