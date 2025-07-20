<?php session_start();
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
unset($_SESSION['message']);
// Clear the message after displaying it ?>
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

    <title>Welcome to Qualipaws</title>
    <style>
        .nav-item {
            margin-right: 30px;
            font-size: large;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
            height: 600px;
            color: red;
            /* Or another suitable height */
        }

        .fade-out {
            animation: fadeOut 5s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
        }

        .chatbot-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999;
            background-color: #399751;
            color: white;
            border-radius: 50px;
            width: 60px;
            height: 60px;
            border: none;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chatbot-window {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            max-height: 400px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-header {
            background-color: #399751;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .chatbot-body {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .chatbot-footer {
            padding: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 5px;
        }

        .chatbot-footer input {
            flex: 1;
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 5px 10px;
        }

        .chatbot-footer button {
            background-color: #399751;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 5px 10px;
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
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
    <div class="container mt-2">
        <?php if ($message): ?>
            <div class="alert alert-<?= htmlspecialchars($message['type']) ?> fade-out">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>
    </div>
    <!--content-->
    <div class="container mt-5" style=" font-family: 'Nunito Sans', 'sans-serif';">
        <h1 style="font-weight:600;">Welcome to Qualipaws,</h1>
        <h5 style="font-weight:600;">Provide a High Quality care for your furry friend.</h5>
        <h6 class="mt-4">Set an Appointment easily for your pet with Qualipaws.</h6>
        <!-- Trigger Button -->
        <button type="button" class="btn btn-primary mt-4 mb-5" data-bs-toggle="modal"
            data-bs-target="#appointmentModal">
            Book an Appointment
        </button>
    </div>

    <!-- Chatbot Button -->
    <button class="chatbot-button" id="chatbotToggle">
        <i class="bi bi-chat-dots"></i>
    </button>
    <!-- Chatbot Window -->
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">Qualipaws Chatbot</div>
        <div class="chatbot-body" id="chatbotBody">
            <div class="text-muted">
                <h5>Hello paw parents! <i class="fa fa-paw"></i> I am Qualipaws chatbot, Ask a Question?</h5>
                <!-- Dog and Cat icons -->
                <div style="display: flex; justify-content: center; align-items: center; margin-top: 10px;">
                    <i class="fa fa-dog" style="font-size: 24px; margin-right: 10px;"></i>
                    <i class="fa fa-cat" style="font-size: 24px;"></i>
                </div>
            </div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatbotInput" placeholder="Type a question...">
            <button id="chatbotSend">Send</button>
        </div>
    </div>

    <!--Booking Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Book an Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="appointmentForm" action="php/save_booking.php" method="POST">
                        <div class="mb-3">
                            <label for="serviceType" class="form-label">Type of Service</label>
                            <select class="form-select" id="serviceType" name="service_type" required>
                                <option value="">Select a service</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Check-up">Check-up</option>
                                <option value="Grooming">Grooming</option>
                                <option value="surgery">Surgery</option>
                                <option value="deworming">Deworming</option>
                                <option value="consultation">Consultation</option>
                                <option value="homeService">Home Service</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="appointmentDate" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="appointmentDate" name="appointment_date"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="appointmentTime" class="form-label">Appointment Time</label>
                            <input type="time" class="form-control" id="appointmentTime" name="appointment_time"
                                required>
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
                            <textarea class="form-control" id="additionalNotes" name="additional_notes"
                                rows="3"></textarea>
                            <i>(If you select
                                <b style="color:blue">"Vaccination"</b> as the service type, please include
                                your pet's last
                                recorded weight.)</i>
                        </div>

                        <!-- Checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="termsCheckbox" required>
                            <label class="form-check-label" for="termsCheckbox">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and
                                    Conditions</a>
                            </label>
                            <div class="invalid-feedback">
                                You must agree before submitting.
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Book Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Acceptance of Terms</h6>
                    <p>By proceeding with the booking, you acknowledge that you have read, understood, and agree to be
                        bound by these Terms and Conditions. If you do not agree with any of the terms, please do not
                        proceed with the booking process.</p>

                    <h6>2. Appointment Booking</h6>
                    <p>All appointments are subject to availability. We reserve the right to decline or reschedule
                        appointments at our discretion. You are responsible for providing accurate and complete
                        information during the booking process. Any incorrect details may result in the cancellation of
                        your appointment.</p>

                    <h6>3. Confirmation of Appointment</h6>
                    <p>Once your booking is successfully made, you will receive a confirmation via email or SMS. It is
                        your responsibility to check the accuracy of the details provided. If you do not receive a
                        confirmation, please contact us immediately to verify your booking status.</p>

                    <h6>4. Cancellations and Rescheduling</h6>
                    <p>To cancel or reschedule an appointment, please notify us at least 24 hours before the scheduled
                        time. Cancellations made less than 24 hours prior to the appointment may incur a cancellation
                        fee. We reserve the right to reschedule or cancel appointments due to unforeseen circumstances,
                        and you will be notified as soon as possible in such cases.</p>

                    <h6>5. Late Arrivals</h6>
                    <p>If you are running late, please inform us as soon as possible. Late arrivals may result in a
                        shortened appointment duration or rescheduling, depending on availability. If you arrive more
                        than 15 minutes late without prior notice, your appointment may be canceled, and a cancellation
                        fee may apply.</p>

                    <h6>6. No-Show Policy</h6>
                    <p>If you fail to attend your scheduled appointment without notifying us in advance, it will be
                        considered a "No-Show." We reserve the right to charge a No-Show fee or require prepayment for
                        future bookings.</p>

                    <h6>7. Payment Terms</h6>
                    <p>Payment is due at the time of the appointment. We accept [list accepted payment methods]. Failure
                        to provide payment may result in the denial of services. All prices are subject to change
                        without prior notice.</p>

                    <h6>8. Privacy and Data Protection</h6>
                    <p>Your personal information collected during the booking process will be handled in accordance with
                        our Privacy Policy. We are committed to protecting your personal data and will not share it with
                        third parties without your consent, except as required by law.</p>

                    <h6>9. Limitation of Liability</h6>
                    <p>We are not liable for any direct, indirect, incidental, or consequential damages resulting from
                        your use of our booking system or services. We are not responsible for any injury, loss, or
                        damage that may occur during your visit or as a result of the services provided.</p>

                    <h6>10. Changes to Terms and Conditions</h6>
                    <p>We reserve the right to modify or update these Terms and Conditions at any time without prior
                        notice. Continued use of our booking system after changes have been made constitutes acceptance
                        of the revised terms.</p>

                    <h6>11. Governing Law</h6>
                    <p>These Terms and Conditions are governed by and construed in accordance with the laws of [Your
                        Country/Region]. Any disputes arising from these terms will be subject to the jurisdiction of
                        the courts of [Your Country/Region].</p>

                    <h6>12. Contact Information</h6>
                    <p>If you have any questions or concerns regarding these Terms and Conditions, please contact us at
                        [qualipawsanimalhealthclinic@gmail.com].</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const termsModalEl = document.getElementById('termsModal');
            const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));

            termsModalEl.addEventListener('hidden.bs.modal', function () {
                appointmentModal.show();
            });
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

    <script src="js/appointment.js"></script>
    <script src="js/chatbot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>