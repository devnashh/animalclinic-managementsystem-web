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
require_once '../database/db_connection.php'; // Adjust the path if needed

// Fetch pet records
$sql = "SELECT pets.*, client.full_name AS owner_name FROM pets 
        JOIN client ON pets.client_id = client.id";
$result = $conn->query($sql);

$searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Update SQL query to include search
$sql = "SELECT pets.*, client.full_name AS owner_name FROM pets 
        JOIN client ON pets.client_id = client.id 
        WHERE pets.name LIKE '%$searchTerm%' 
        OR client.full_name LIKE '%$searchTerm%'
        OR pets.breed LIKE '%$searchTerm%'
        OR pets.color LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
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

        .pet-card {
            background-color: #389850;
            color: #ffffff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .pet-card img {
            max-width: 100%;
            max-height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card-content {
            display: flex;
            flex-wrap: wrap;
        }

        .card-content>div {
            width: 50%;
        }
    </style>
</head>

<body class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column align-items-center p-3">
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
        <a href="#" class="text-dark active d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-book me-1"></i> Pet Records
        </a>
        <a href="settings.php" class="text-dark d-flex align-items-center py-2 px-3 rounded w-100">
            <i class="bi bi-gear me-1"></i> Settings
        </a>
       <a href="../database/logout.php" class="text-danger d-flex align-items-center py-2 px-3 rounded w-100 logout mt-5" onclick="return confirm('Are you sure you want to logout?');">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>
        <div class="admin-info text-dark mt-3 text-center">
            <i class="bi bi-person-circle me-1" style="font-size: 1.5em"></i>
            <div>
                <?php echo htmlspecialchars($vet); ?>
            </div>
            <small class="text-muted">
                <?php
                // Query to fetch the role of the logged-in user
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

    <!-- Main Content -->
    <div class="container flex-grow-1 p-4" style="font-family: 'Nunito', sans-serif;">
        <h1 class="mb-3">Pet Records</h1>
        <div class="d-flex mb-4">
            <input type="text" id="searchInput" class="form-control me-2" placeholder="Search pets or owners..."
                style="width: 300px; height: 40px;">
            <button class="btn btn-secondary" onclick="filterRecords()">Search</button>
        </div>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPetModal">
            Add Pet Record
        </button>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="pet-card">
                            <div class="row">
                                <div class="col-4">
                                    <img src="../media/<?php echo htmlspecialchars($row['profile_picture']) ?: 'default.png'; ?>"
                                        class="card-img-top img-fluid" alt="Pet Image">
                                </div>
                                <div class="col-8">
                                    <div class="card-content">
                                        <div><strong>Pet Name:</strong> <?php echo htmlspecialchars($row['name']); ?></div>
                                        <div><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></div>
                                        <div><strong>Age:</strong> <?php echo htmlspecialchars($row['age']); ?></div>
                                        <div><strong>Breed:</strong> <?php echo htmlspecialchars($row['breed']); ?></div>
                                        <div><strong>Sex:</strong> <?php echo htmlspecialchars($row['sex']); ?></div>
                                        <div><strong>Color:</strong> <?php echo htmlspecialchars($row['color']); ?></div>
                                    </div>
                                    <a href="#" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#editPetModal-<?php echo $row['id']; ?>">Edit</a>
                                    <a href="php/delete_pet.php?id=<?php echo $row['id']; ?>" class="btn btn-danger mt-2"
                                        onclick="return confirm('Are you sure you want to delete this pet?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Editing Pet Details -->
                    <div class="modal fade" id="editPetModal-<?php echo $row['id']; ?>" tabindex="-1"
                        aria-labelledby="editPetModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editPetModalLabel-<?php echo $row['id']; ?>">Edit Pet Details
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="php/edit_pet_process.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="pet_id" value="<?php echo $row['id']; ?>">
                                        <div class="mb-3">
                                            <label for="petName-<?php echo $row['id']; ?>" class="form-label">Pet Name</label>
                                            <input type="text" class="form-control" id="petName-<?php echo $row['id']; ?>"
                                                name="pet_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petAge-<?php echo $row['id']; ?>" class="form-label">Age</label>
                                            <input type="number" class="form-control" id="petAge-<?php echo $row['id']; ?>"
                                                name="pet_age" value="<?php echo htmlspecialchars($row['age']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petBreed-<?php echo $row['id']; ?>" class="form-label">Breed</label>
                                            <input type="text" class="form-control" id="petBreed-<?php echo $row['id']; ?>"
                                                name="pet_breed" value="<?php echo htmlspecialchars($row['breed']); ?>"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petSex-<?php echo $row['id']; ?>" class="form-label">Sex</label>
                                            <select class="form-select" id="petSex-<?php echo $row['id']; ?>" name="pet_sex"
                                                required>
                                                <option value="Male" <?php echo ($row['sex'] == 'Male') ? 'selected' : ''; ?>>Male
                                                </option>
                                                <option value="Female" <?php echo ($row['sex'] == 'Female') ? 'selected' : ''; ?>>
                                                    Female
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petColor-<?php echo $row['id']; ?>" class="form-label">Color</label>
                                            <input type="text" class="form-control" id="petColor-<?php echo $row['id']; ?>"
                                                name="pet_color" value="<?php echo htmlspecialchars($row['color']); ?>"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petImage-<?php echo $row['id']; ?>" class="form-label">Profile
                                                Picture</label>
                                            <input type="file" class="form-control" id="petImage-<?php echo $row['id']; ?>"
                                                name="pet_image">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pet records found.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Modal for Adding Pet Record -->
    <div class="modal fade" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="php/add_pet_process.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPetModalLabel">Add Pet Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ownerName" class="form-label">Owner Name</label>
                            <input type="text" class="form-control" id="ownerName" name="owner_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="ownerContact" class="form-label">Owner Contact</label>
                            <input type="text" class="form-control" id="ownerContact" name="owner_contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="ownerAddress" class="form-label">Owner Address</label>
                            <input type="text" class="form-control" id="ownerAddress" name="owner_address" required>
                        </div>
                        <div class="mb-3">
                            <label for="ownerEmail" class="form-label">Owner Email</label>
                            <input type="email" class="form-control" id="ownerEmail" name="owner_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="petName" class="form-label">Pet Name</label>
                            <input type="text" class="form-control" id="petName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="petAge" class="form-label">Pet Age</label>
                            <input type="number" class="form-control" id="petAge" name="age" required>
                        </div>
                        <div class="mb-3">
                            <label for="petBreed" class="form-label">Breed</label>
                            <input type="text" class="form-control" id="petBreed" name="breed" required>
                        </div>
                       <div class="mb-3">
                           <label for="type" class="form-label">Type</label>
                            <select class="form-control" id="type" name="type" required>
                             <option value="" disabled selected>Select Type</option>
                             <option value="Canine">Canine</option>
                             <option value="Feline">Feline</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="petSex" class="form-label">Sex</label>
                            <select class="form-select" id="petSex" name="sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="petColor" class="form-label">Color</label>
                            <input type="text" class="form-control" id="petColor" name="color" required>
                        </div>
                        <div class="mb-3">
                            <label for="petWeight" class="form-label">Weight</label>
                            <input type="number" class="form-control" id="petWeight" name="weight" step="0.1" required>
                        </div>
                        <div class="mb-3">
                            <label for="petProfilePicture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="petProfilePicture" name="profile_picture">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Pet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function filterRecords() {
            const searchInput = document.getElementById('searchInput').value;
            window.location.href = `pet_records.php?search=${encodeURIComponent(searchInput)}`;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>