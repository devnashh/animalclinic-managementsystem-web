<?php
require_once '../database/db_connection.php';
session_start();

if (!isset($_SESSION['client_id'])) {
    die("You must be logged in to view your pets.");
}

$client_id = $_SESSION['client_id']; // Get the client ID from the session

// Fetch the pet data for the logged-in user
$sql = "SELECT * FROM pets WHERE client_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $client_id); // Bind the correct variable
    $stmt->execute();
    $result = $stmt->get_result();
    $pets = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Database error: " . $conn->error);
}

// Fetch pets data based on search query
$search_query = isset($_GET['search_query']) ? '%' . $_GET['search_query'] . '%' : '%';

$sql = "SELECT * FROM pets WHERE client_id = ? AND (name LIKE ? OR type LIKE ? OR breed LIKE ?)";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("isss", $client_id, $search_query, $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    $pets = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    die("Database error: " . $conn->error);
}

$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']); // Clear the message after displaying it
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
    <title>My pets</title>
    <style>
        .nav-item {
            margin-right: 30px;
            font-size: large;
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
                        <a class="nav-link active" href="#">Pet Profile</a>
                    </li>
                    <!-- Dropdown with User Icon -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
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

    <div class="container mt-4">
        <!-- Display message -->
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-fade" role="alert">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>
        <?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>
  
    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


    </div>
    

    <div class="container mt-4">
        <!-- Search Bar -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group" style="width: 350px; height: 40px;">
                <input type="text" name="search_query" class="form-control" placeholder="Search pets name..."
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button class="btn btn-secondary" type="submit">Search</button>
            </div>
        </form>
    </div>

    <!--content-->
    <div class="container mt-5" style=" font-family: 'Nunito Sans', 'sans-serif';">
        <h1 style="font-weight:600;">Pet Profile</h1>
        <button type="button" class="btn btn-success mb-2 bi bi-plus-lg" data-bs-toggle="modal"
            data-bs-target="#petInfoModal">
            Add Pet
        </button>


        <!-- Pet Cards -->
        <div class="row mt-4">
            <?php if (count($pets) > 0): ?>
                <?php foreach ($pets as $pet): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                           <img src="../media/pet_images/<?php echo $pet['profile_picture'] ?: 'dog5.jpg'; ?>?t=<?php echo time(); ?>"
                             class="card-img-top mx-5 mt-1" alt="Pet Image"
                              style="height: 200px;width:250px; object-fit: cover; border-radius:50%">
                            <div class="card-body">
                                <h2 class="card-title text-start"><b><?php echo $pet['name']; ?></b></h2>
                                <hr>
                                <div class="row">
                                    <!-- Column 1 -->
                                    <div class="col-6">
                                        <p class="card-text"><strong>Age:</strong> <?php echo $pet['age']; ?></p>
                                        <p class="card-text"><strong>Sex:</strong> <?php echo $pet['sex']; ?></p>
                                        <p class="card-text"><strong>Color:</strong> <?php echo $pet['color']; ?></p>
                                    </div>
                                    <!-- Column 2 -->
                                    <div class="col-6">
                                        <p class="card-text"><strong>Type:</strong> <?php echo $pet['type']; ?></p>
                                        <p class="card-text"><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
                                        <p class="card-text"><strong>Weight:</strong> <?php echo $pet['weight']; ?> kg</p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <!-- Edit Button -->
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editPetModal"
                                        onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($pet)); ?>)">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="php/delete_pet.php" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this pet?');">
                                        <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no pets yet. Add one now!</p>
            <?php endif; ?>
        </div>

 <!-- Edit Pet Modal -->
        <div class="modal fade" id="editPetModal" tabindex="-1" aria-labelledby="editPetModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="php/edit_pet.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPetModalLabel">Update Pet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="pet_id" id="edit-pet-id">
                            <div class="mb-3">
                                <label for="edit-pet-age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="edit-pet-age" name="age" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-pet-color" class="form-label">Color</label>
                                <input type="text" class="form-control" id="edit-pet-color" name="color" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-pet-weight" class="form-label">Weight (kg)</label>
                                <input type="text" class="form-control" id="edit-pet-weight" name="weight" required>
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
                    <form id="addPetForm" method="POST" action="php/add_pet.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Pet Image and Name -->
                                    <div class="col-md-3 text-center">
                                        <div class="position-relative">
                                            <img src="../../media" alt="Pet" class="rounded border border-success"
                                                style="width: 100px; height: 100px;">
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
                                            <label for="edit-pet-type" class="form-label">Type</label>
                                            <select class="form-select" id="edit-pet-type" name="type" required>
                                                <option value="" selected disabled>Select Type</option>
                                                <option value="Feline">Feline</option>
                                                <option value="Canine">Canine</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="petBreed" class="form-label">Breed:</label>
                                            <input type="text" class="form-control" id="petBreed" name="breed">
                                        </div>
                                        <div class="mb-3">
                                            <label for="petWeight" class="form-label">Weight (kg):</label>
                                            <input type="text" class="form-control" id="petWeight" name="weight">
                                        </div>
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

    <!--footer-->
    <footer class="footer">
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
        document.getElementById('edit-pet-image').src = "../media/pet_images/" + pet.profile_picture;
    </script>
    <script src="js/add_pet.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>