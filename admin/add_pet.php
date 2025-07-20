<?php
    include '../database/db_connection.php';
    // Start the session
    session_start();

    $admin = isset($_SESSION['username']) ? $_SESSION['username'] : null;

    if (! isset($_SESSION['client_id'])) {
        header("Location: ../accounts/signin.php"); // Redirect to login if not logged in
        exit;
    }

    if ($admin) {
        $query = "SELECT username, age, contact_number, email, address, role FROM client WHERE username = ?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("s", $admin);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $admin_details = $result->fetch_assoc();
        }
        $stmt->close();
    }

    $searchQuery = ""; // Initialize the variable to avoid the warning

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
        $searchQuery = trim($_GET['search']);
    }
    $sql    = "SELECT * FROM pets";
    $result = $conn->query($sql);
    $sql    = "SELECT * FROM pets";

    // Add a condition to filter by the search query if it's provided
    if (! empty($searchQuery)) {
        $sql .= " WHERE name LIKE ? OR owner_name LIKE ?";
        $stmt       = $conn->prepare($sql);
        $searchTerm = "%$searchQuery%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }

    $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
    unset($_SESSION['message']); // Clear the message after displaying it
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            background-image: url('../media/paw_bg.png');
            /* Adjust the path if needed */
            background-size: cover;
            background-repeat: repeat;
            background-size: 750px;
            /* Adjust size as desired */
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        form.d-inline {
            margin: 0;
        }

        .alert-fade {
            animation: fadeOut 8s forwards;
            /* Fades out in 5 seconds */
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            99% {
                opacity: 0;
            }

            100% {
                opacity: 0;
                visibility: hidden;
                display: none;
            }
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

        table {
            width: 80%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 3px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
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
        <a href="appointment_list.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-calendar me-2"></i> Appointments
        </a>
        <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-person me-2"></i> Registered Users
        </a>
        <a href="#" class="text-dark d-flex active align-items-center py-2 px-3 rounded w-100">
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
                        $stmt  = $conn->prepare($query);
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
    <a href="#" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-house-door me-2"></i> Dashboard
    </a>
    <a href="appointment_list.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-calendar me-2"></i> Appointments
    </a>
    <a href="registered_users.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
        <i class="bi bi-person me-2"></i> Registered Users
    </a>
    <a href="add_pet.php" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
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
                    $stmt  = $conn->prepare($query);
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

    <div class="container mt-4">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($message['type'])?> alert-fade" role="alert">
                <?php echo htmlspecialchars($message['text'])?>
            </div>
        <?php endif; ?>
<?php
    if (isset($_SESSION['message'])):
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
	            <div class="alert alert-<?php echo $message['type']; ?>">
	                <?php echo $message['text']; ?>
	            </div>
	        <?php endif; ?>

        <h1 style="font-weight:600;" style=" font-family: 'Nunito Sans', 'sans-serif';">Pet Profile</h1>
        <!-- Search Bar -->
        <div class="mb-4" style="width: 400px; height: 40px;">
            <div class="input-group">
                <!-- Icon inside the input group -->
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icon for search -->
                </span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search pets.."
                    onkeyup="searchUsers()">
            </div>
        </div>

        <button type="button" class="btn btn-success mb-2 bi bi-plus-lg" data-bs-toggle="modal"
            data-bs-target="#petInfoModal">
            Add Pet
        </button>

        <div class="p-4 bg-light rounded-sm">
            <table id="user-table" class="table table-striped responsive hover p-2" width="100%">
                <thead class="thead-dark fs-6">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Color</th>
                    <th>Type</th>
                    <th>Breed</th>
                    <th>Weight (kg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['age'] . "</td>";
                            echo "<td>" . $row['sex'] . "</td>";
                            echo "<td>" . $row['color'] . "</td>";
                            echo "<td>" . $row['type'] . "</td>";
                            echo "<td>" . $row['breed'] . "</td>";
                            echo "<td>" . $row['weight'] . " kg</td>";
                            echo "<td>
                      <div class='btn d-flex' role='group' aria-label='Action Buttons'>
                        <button class='btn btn-primary btn-md me-2 info-button' data-bs-toggle='modal' data-bs-target='#infoModal'
                            data-id='" . $row['id'] . "'
                            data-created='" . $row['created_at'] . "'
                            data-updated='" . $row['updated_at'] . "'
                            data-profile-picture='../media/pet_images/" . $row['profile_picture'] . "'
                            data-owner-name='" . $row['owner_name'] . "'
                            data-owner-contact='" . $row['owner_contact'] . "'
                            data-owner-address='" . $row['owner_address'] . "'
                            data-owner-email='" . $row['owner_email'] . "'>
                           <i class='bi bi-info-circle'></i>
                        </button>
                        <button class='btn btn-primary btn-md me-2' data-bs-toggle='modal' data-bs-target='#editPetModal'
                           data-id='" . $row['id'] . "'>
                           <i class='bi bi-pencil-square'></i>
                        </button>
                        <a href='#' class='btn btn-secondary btn-md me-4 view-history-booking'
                           data-pet-id='" . $row['id'] . "'
                           data-bs-toggle='modal'
                           data-bs-target='#historyModal'>
                            <i class='bi bi-clock-history'></i>
                        </a>
                        <form method='POST' action='php/archive_pet_backend.php' class='d-inline'>
                            <input type='hidden' name='pet_id' value='" . $row['id'] . "'>
                            <button type='submit' class='btn btn-danger btn-md'>
                                <i class='bi bi-archive'></i>
                            </button>
                        </form>
                    </div>
                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        </div>

        <!-- View Other Info Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalLabel">Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID:</strong> <span id="modalId"></span></p>
                        <p><strong>Created At:</strong> <span id="modalCreatedAt"></span></p>
                        <p><strong>Updated At:</strong> <span id="modalUpdatedAt"></span></p>
                        <p><strong>Profile Picture:</strong></p>
                        <img id="modalProfilePicture" src="" alt="Profile Picture" class="img-fluid rounded"
                            style="max-width: 200px">
                        <hr>
                        <p><strong>Owner Name:</strong> <span id="modalOwnerName"></span></p>
                        <p><strong>Owner Contact:</strong> <span id="modalOwnerContact"></span></p>
                        <p><strong>Owner Address:</strong> <span id="modalOwnerAddress"></span></p>
                        <p><strong>Owner Email:</strong> <span id="modalOwnerEmail"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- History Modal -->
        <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="historyModalLabel">Booking History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="historyContent">
                            <!-- Booking history details will be loaded here -->
                            <p>Loading...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- pet edit -->
        <div class="modal fade" id="editPetModal" tabindex="-1" aria-labelledby="editPetModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editPetForm" method="POST" action="php/edit_pet_backend.php"
                        enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPetModalLabel">Edit Pet Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="editPetId" name="id">
                            <div class="mb-3">
                                <label for="editPetName" class="form-label">Pet Name:</label>
                                <input type="text" class="form-control" id="editPetName" name="name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="editPetAge" class="form-label">Age:</label>
                                    <input type="number" class="form-control" id="editPetAge" name="age">
                                </div>
                                <div class="col-md-6">
                                    <label for="editPetWeight" class="form-label">Weight:</label>
                                    <input type="number" class="form-control" id="editPetWeight" name="weight">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editPetPicture" class="form-label">Upload Picture:</label>
                                <input type="file" class="form-control" id="editPetPicture" name="profile_picture"
                                    accept="image/*">
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


        <!-- Modal -->
        <div class="modal fade" id="petInfoModal" tabindex="-1" aria-labelledby="petInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="addPetForm" method="POST" action="php/add_pet_backend.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Pet Image and Name -->
                                    <div class="col-md-3 text-center">
                                        <div class="position-relative">
                                            <img src="../media/pet_images/" alt="Pet"
                                                class="rounded-circle border border-success"
                                                style="width: 100px; height: 100px;">
                                            <h5 class="mt-2">Add Picture</h5>
                                            <input type="file" class="form-control mt-2" name="profile_picture">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="petName" class="form-label">Pet Name:</label>
                                            <input type="text" class="form-control" id="petName" name="name" required>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Pet Details -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="petAge" class="form-label">Age:</label>
                                            <input type="number" class="form-control" id="petAge" name="age">
                                        </div>
                                        <div class="mb-3">
                                            <label for="petSex" class="form-label">Sex:</label>
                                            <select class="form-control" id="petSex" name="sex">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petColor" class="form-label">Color:</label>
                                            <input type="text" class="form-control" id="petColor" name="color">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="petType" class="form-label">Type:</label>
                                            <input type="text" class="form-control" id="petType" name="type">
                                        </div>
                                        <div class="mb-3">
                                            <label for="petBreed" class="form-label">Breed:</label>
                                            <input type="text" class="form-control" id="petBreed" name="breed">
                                        </div>
                                        <div class="mb-3">
                                            <label for="petWeight" class="form-label">Weight:</label>
                                            <input type="text" class="form-control" id="petWeight" name="weight">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ownerName" class="form-label">Owner name:</label>
                                        <input type="text" class="form-control" id="ownerName" name="owner_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ownerContact" class="form-label">Owner contact:</label>
                                        <input type="text" class="form-control" id="ownerContact" name="owner_contact">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ownerAddress" class="form-label">Owner address:</label>
                                        <input type="text" class="form-control" id="ownerAddress" name="owner_address">
                                    </div>
                                    <div class="mb-3">
                                        <label for="ownerEmail" class="form-label">Owner email:</label>
                                        <input type="text" class="form-control" id="ownerEmail" name="owner_email">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="js/table.js"></script>

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
        const infoModal = document.getElementById('infoModal');

        // Event listener for "View Other Details" modal
        infoModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget; // Button that triggered the modal
            const id = button.getAttribute('data-id');
            const createdAt = button.getAttribute('data-created');
            const updatedAt = button.getAttribute('data-updated');
            const profilePicture = button.getAttribute('data-profile-picture'); // Profile Picture URL

            // Fetch owner details
            const ownerName = button.getAttribute('data-owner-name');
            const ownerContact = button.getAttribute('data-owner-contact');
            const ownerAddress = button.getAttribute('data-owner-address');
            const ownerEmail = button.getAttribute('data-owner-email');

            // Populate modal fields
            document.getElementById('modalId').textContent = id;
            document.getElementById('modalCreatedAt').textContent = createdAt;
            document.getElementById('modalUpdatedAt').textContent = updatedAt;
            document.getElementById('modalProfilePicture').src = profilePicture || '../media/pet_images/dog1.jpg'; // Default image if null

            // Populate owner details
            document.getElementById('modalOwnerName').textContent = ownerName;
            document.getElementById('modalOwnerContact').textContent = ownerContact;
            document.getElementById('modalOwnerAddress').textContent = ownerAddress;
            document.getElementById('modalOwnerEmail').textContent = ownerEmail;
        });

        // Event listener for "Edit Pet" modal
        document.addEventListener('DOMContentLoaded', () => {
            const editPetModal = document.getElementById('editPetModal');

            editPetModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const petId = button.getAttribute('data-id');
                const petName = button.closest('tr').children[1].textContent.trim();
                const petAge = button.closest('tr').children[2].textContent.trim();
                const petSex = button.closest('tr').children[3].textContent.trim();

                // Populate edit modal fields
                this.querySelector('#editPetId').value = petId;
                this.querySelector('#editPetName').value = petName;
                this.querySelector('#editPetAge').value = petAge;
                this.querySelector('#editPetSex').value = petSex;
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '.view-history-booking', function () {
            var petId = $(this).data('pet-id');

            // Fetch booking history via AJAX
            $.ajax({
                url: 'php/fetch_history_pets.php',
                type: 'GET',
                data: { pet_id: petId },
                success: function (data) {
                    $('#historyContent').html(data);
                },
                error: function () {
                    $('#historyContent').html('<p class="text-danger">Failed to load booking history.</p>');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>