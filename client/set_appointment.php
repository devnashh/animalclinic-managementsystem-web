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
    <title>Document</title>
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


    <!--content-->
    <div class="container mt-5" style=" font-family: 'Nunito Sans', 'sans-serif';">
        <h1 style="font-weight:600;">Set an Appointment</h1>
    </div>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Book an Appointment</h2>
        <form id="appointmentForm" action="php/save_booking.php" method="POST">
            <div class="mb-3">
                <label for="serviceType" class="form-label">Type of Service</label>
                <select class="form-select" id="serviceType" name="service_type" required>
                    <option value="">Select a service</option>
                    <option value="Vaccination">Vaccination</option>
                    <option value="Check-up">Check-up</option>
                    <option value="Grooming">Grooming</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="appointmentDate" class="form-label">Appointment Date</label>
                <input type="date" class="form-control" id="appointmentDate" name="appointment_date" required>
            </div>

            <div class="mb-3">
                <label for="appointmentTime" class="form-label">Appointment Time</label>
                <input type="time" class="form-control" id="appointmentTime" name="appointment_time" required>
            </div>

            <div class="mb-3">
                <label for="pet" class="form-label">Select Your Pet</label>
                <select class="form-select" id="pet" name="pet_id" required>
                    <!-- Dynamically populate this list with the client's pets -->
                    <option value="">Select a pet</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="additionalNotes" class="form-label">Additional Notes</label>
                <textarea class="form-control" id="additionalNotes" name="additional_notes" rows="3"></textarea>
                <i class="mt-3">(If you select
                    <b style="color:blue">"Vaccination"</b> as the service type, please include
                    your pet's last
                    recorded weight.)</i>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary mb-5">Book Appointment</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('php/get_pets.php') // Fetch pets dynamically based on the logged-in client
                .then(response => response.json())
                .then(data => {
                    const petSelect = document.getElementById('pet');
                    data.forEach(pet => {
                        const option = document.createElement('option');
                        option.value = pet.id;
                        option.textContent = `${pet.name} (${pet.type}, ${pet.breed})`;
                        petSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching pets:', error));
        });
    </script>
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
        document.addEventListener('DOMContentLoaded', function () {
            const termsModalEl = document.getElementById('termsModal');
            const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));

            termsModalEl.addEventListener('hidden.bs.modal', function () {
                appointmentModal.show();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>