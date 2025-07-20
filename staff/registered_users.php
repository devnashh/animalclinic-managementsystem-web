<?php

include '../database/db_connection.php';
// Start the session
session_start();

$staff = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if (!isset($_SESSION['client_id'])) {
    header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
    exit;
}
// Get the search query from the form
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query to fetch all users with search filter if search query exists
$query = "SELECT id, full_name, contact_number, address, username, email, role FROM client";
if ($search_query) {
    $query .= " WHERE full_name LIKE '%$search_query%' OR username LIKE '%$search_query%' OR email LIKE '%$search_query%'";
}
$result = mysqli_query($conn, $query);

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

        .alert {
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            z-index: 1000;

        }

        .alert.success {
            background-color: #4caf50;
            /* Green */
        }

        .alert.error {
            background-color: #f44336;
            /* Red */
        }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center p-3">
        <img src="../media/qplogo.jpg" alt="Logo" class="mb-3">
        <a href="home.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-house-door me-2"></i> Dashboard
        </a>
        <a href="appointment_list.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-calendar me-2"></i> Appointments
        </a>
        <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-person me-2"></i> Registered Users
        </a>
        <a href="add_pet.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-book me-2"></i> Pet Records
        </a>
        <a href="settings.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-gear me-2"></i> Settings
        </a>
        <a href="../database/logout.php"
            class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-auto">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
        <div class="admin-info text-dark mt-3 text-center">
            <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
            <div>
                <?php echo htmlspecialchars($staff); ?>
            </div>
            <small class="text-muted">
                <?php
                // Query to fetch the role of the logged-in user
                if ($staff) {
                    $query = "SELECT role FROM client WHERE username = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $staff);
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
    <!-- Main Content -->
    <div class="flex-grow-1 p-4" style="font-family: 'Nunito', sans-serif;">
        <h1 class="mb-3">Registered Users</h1>

        <!-- Search Bar -->
        <div class="mb-4" style="width: 400px; height: 40px;">
            <div class="input-group">
                <!-- Icon inside the input group -->
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icon for search -->
                </span>
                <input type="text" class="form-control" id="searchInput"
                    placeholder="Search by Name, Username, or Email" onkeyup="searchUsers()">
            </div>
        </div>

        <!--Add User -->
        <div class="mb-4">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add User
            </button>
        </div>


        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="php/add_user_backend.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Create New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="full_name"
                                    value="<?php echo isset($_SESSION['form_data']['full_name']) ? $_SESSION['form_data']['full_name'] : ''; ?>"
                                    required>
                            </div>

                            <!-- Username with error message -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>"
                                    required>
                                <?php if (isset($_SESSION['error_message']) && strpos($_SESSION['error_message'], 'Username') !== false): ?>
                                    <small class="text-danger"><?php echo $_SESSION['error_message'];
                                    unset($_SESSION['error_message']); ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Email with error message -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo isset($_SESSION['form_data']['email']) ? $_SESSION['form_data']['email'] : ''; ?>"
                                    required>
                                <?php if (isset($_SESSION['error_message']) && strpos($_SESSION['error_message'], 'Email') !== false): ?>
                                    <small class="text-danger"><?php echo $_SESSION['error_message'];
                                    unset($_SESSION['error_message']); ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Contact Number -->
                            <div class="mb-3">
                                <label for="contactNumber" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contactNumber" name="contact_number"
                                    value="<?php echo isset($_SESSION['form_data']['contact_number']) ? $_SESSION['form_data']['contact_number'] : ''; ?>"
                                    required>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?php echo isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : ''; ?>"
                                    required>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="client" <?php echo isset($_SESSION['form_data']['role']) && $_SESSION['form_data']['role'] == 'client' ? 'selected' : ''; ?>>Client</option>
                                </select>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="<?php echo isset($_SESSION['form_data']['password']) ? $_SESSION['form_data']['password'] : ''; ?>"
                                    minlength="8" required>
                                <small id="passwordHelp" class="form-text text-muted">Password must be at least 8
                                    characters long.</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                                    value="<?php echo isset($_SESSION['form_data']['confirm_password']) ? $_SESSION['form_data']['confirm_password'] : ''; ?>"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if (isset($_GET['message']) || isset($_GET['error'])) {
            $alertType = isset($_GET['message']) ? 'success' : 'error';
            $alertMessage = isset($_GET['message']) ? $_GET['message'] : $_GET['error'];
            echo "<div id='alert-box' class='alert mx-3 mb-4 $alertType'>$alertMessage</div>";
        }
        ?>
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Contact number</th>
                    <th>Address</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Skip rows where the role is 'admin' or 'vet'
                        if ($row['role'] === 'admin' || $row['role'] === 'vet') {
                            continue;
                        }

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                        echo "<td>
                <button type='button' class='btn btn-primary bi bi-pencil-square btn-sm' data-bs-toggle='modal' 
            data-bs-target='#editModal'
            data-id='" . htmlspecialchars($row['id']) . "' 
            data-username='" . htmlspecialchars($row['username']) . "' 
            data-email='" . htmlspecialchars($row['email']) . "' 
            data-role='" . htmlspecialchars($row['role']) . "'> Edit</button>

    <form method='POST' action='php/archive_users.php' class='d-inline'>
        <input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'>
        <button type='submit' class='btn btn-danger bi bi-archive btn-sm' onclick='return confirm(\"Are you sure you want to archive this user?\");'> Archive</button>
    </form>
            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>

        </table>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="php/edit_user_backend.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <option value="client">client</option>
                                <option value="admin">Admin</option>
                            </select>
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

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="php/archive_user_backend.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveModalLabel">Archive User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="archiveUserId">
                        <p>Are you sure you want to archive this user?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Archive</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Functionality
        function searchUsers() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toLowerCase();
            table = document.querySelector('#viewArchivedUsersModal .table');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header
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
        // Populate Edit Modal
        document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('editUserId').value = button.getAttribute('data-id');
            document.getElementById('editUsername').value = button.getAttribute('data-username');
            document.getElementById('editEmail').value = button.getAttribute('data-email');
            document.getElementById('editRole').value = button.getAttribute('data-role');
        });

        document.getElementById('searchInput').addEventListener('input', function () {
            const searchValue = this.value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'registered_users.php?search=' + encodeURIComponent(searchValue), true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const parser = new DOMParser();
                    const newDocument = parser.parseFromString(this.responseText, 'text/html');
                    const newTableBody = newDocument.querySelector('tbody');
                    if (newTableBody) {
                        document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                    }
                }
            };
            xhr.send();
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>