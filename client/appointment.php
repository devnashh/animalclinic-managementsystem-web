<?php
session_start();
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
    <title>My Appoitntments</title>
    <style>
        .nav-item {
            margin-right: 30px;
            font-size: large;
        }

        .fade-out {
            animation: fadeOut 8s forwards;
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

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            /* Three equal columns */
            gap: 10px;
            /* Space between columns */
        }

        .grid-column {
            padding: 10px;
            /* Optional for spacing */
        }

        .button-group {
            margin-top: 15px;
            text-align: right;
            /* Align buttons to the right */
        }

        .button-group .btn {
            margin-left: 10px;
            /* Space between buttons */
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
                        <a class="nav-link active" aria-current="page" href="#">Appointments</a>
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
        <h1 style="font-weight:600;">My Appointment</h1>
        <div class="container mt-5" style=" font-family: 'Nunito Sans', 'sans-serif';">
            <div id="bookingsList">
                <p>Loading your bookings...</p>
            </div>
        </div>
        <button type="button" class="btn btn-success mt-4 mb-5" data-bs-toggle="modal"
            data-bs-target="#appointmentModal">
            + Set an Appointment
        </button>
    </div>

    <!-- Modal -->
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
                                <option value="">Select a pet</option>
                                <!-- Dynamically populate this list with the client's pets -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="additionalNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="additionalNotes" name="additional_notes"
                                rows="3"></textarea>
                            <i class="mt-3">(If you select <b style="color:blue">"Vaccination"</b> as the service type,
                                please include
                                your pet's last recorded weight.)</i>
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


    <!-- Edit Booking Modal (Dynamic) -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" aria-labelledby="editBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBookingForm">
                        <input type="hidden" id="editBookingId" name="bookingId">

                        <div class="mb-3">
                            <label for="editAppointmentDate" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="editAppointmentDate" name="appointment_date"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="editAppointmentTime" class="form-label">Appointment Time</label>
                            <input type="time" class="form-control" id="editAppointmentTime" name="appointment_time"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="editAdditionalNotes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="editAdditionalNotes" name="additional_notes"
                                rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('php/get_bookings.php') // API endpoint to fetch bookings
                .then(response => response.json())
                .then(data => {
                    const bookingsList = document.getElementById('bookingsList');
                    bookingsList.innerHTML = ''; // Clear previous content

                    if (data.error) {
                        bookingsList.innerHTML = `<p class="text-danger">${data.error}</p>`;
                        return;
                    }

                    if (data.length === 0) {
                        bookingsList.innerHTML = '<p>No bookings found.</p>';
                        return;
                    }

                    // Create booking cards and modals
                    data.forEach(booking => {
                        const bookingCard = document.createElement('div');
                        bookingCard.classList.add('card', 'mb-3');
                        bookingCard.innerHTML = `
                        <div class="card-body" style="box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);">
                            <h5 class="card-title" style="color: #007bff">${booking.service_type}</h5>
                            <div class="grid-layout">
                                <div class="grid-column">
                                    <strong>Date:</strong> ${booking.appointment_date}<br>
                                    <strong>Time:</strong> ${convertTo12HourFormat(booking.appointment_time)}
                                </div>
                                <div class="grid-column">
                                    <strong>Pet:</strong> ${booking.pet_name} (${booking.pet_type}, ${booking.pet_breed})<br>
                                    <strong>Notes:</strong> ${booking.additional_notes || 'N/A'}
                                </div>
                                <div class="grid-column">
                                    <strong>Status:</strong> ${booking.status}
                                </div>
                            </div>
                            <div class="button-group">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editBookingModal-${booking.id}">Edit</button>
                                <button class="btn btn-danger" 
                                        onclick="cancelBooking(${booking.id})" 
                                        ${booking.status === 'approved' ? 'disabled' : ''}>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    `;

                        // Create the corresponding modal for this booking
                        const editModal = document.createElement('div');
                        editModal.innerHTML = `
                        <div class="modal fade" id="editBookingModal-${booking.id}" tabindex="-1" aria-labelledby="editBookingModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editBookingForm-${booking.id}">
                                            <input type="hidden" id="editBookingId-${booking.id}" value="${booking.id}">
                                            <div class="mb-3">
                                                <label for="editAppointmentDate-${booking.id}" class="form-label">Date</label>
                                                <input type="date" class="form-control" id="editAppointmentDate-${booking.id}" value="${booking.appointment_date}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editAppointmentTime-${booking.id}" class="form-label">Time</label>
                                                <input type="time" class="form-control" id="editAppointmentTime-${booking.id}" value="${booking.appointment_time}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editAdditionalNotes-${booking.id}" class="form-label">Additional Notes</label>
                                                <textarea class="form-control" id="editAdditionalNotes-${booking.id}">${booking.additional_notes || ''}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                        // Append both booking card and modal to the page
                        bookingsList.appendChild(bookingCard);
                        document.body.appendChild(editModal); // Append modal to body to ensure Bootstrap can find it

                        // Add event listener for the edit form dynamically
                        document.getElementById(`editBookingForm-${booking.id}`).addEventListener('submit', function (event) {
                            event.preventDefault();

                            const bookingId = document.getElementById(`editBookingId-${booking.id}`).value;
                            const appointmentDate = document.getElementById(`editAppointmentDate-${booking.id}`).value;
                            const appointmentTime = document.getElementById(`editAppointmentTime-${booking.id}`).value;
                            const additionalNotes = document.getElementById(`editAdditionalNotes-${booking.id}`).value;

                            fetch('php/edit_booking.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ bookingId, appointmentDate, appointmentTime, additionalNotes })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Booking updated successfully.');
                                        location.reload(); // Refresh the page to reflect changes
                                    } else {
                                        alert(`Error: ${data.error}`);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error updating booking:', error);
                                    alert('An error occurred while updating the booking.');
                                });
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                    const bookingsList = document.getElementById('bookingsList');
                    bookingsList.innerHTML = '<p class="text-danger">An error occurred while fetching your bookings.</p>';
                });
        });

        function cancelBooking(bookingId) {
            if (!confirm('Are you sure you want to cancel this booking?')) return;

            fetch('php/cancel_booking.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ bookingId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Booking canceled successfully.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(`Error: ${data.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error canceling booking:', error);
                    alert('An error occurred while canceling the booking.');
                });
        }
        function convertTo12HourFormat(time) {
            const [hour, minute] = time.split(':');
            let period = 'AM';
            let hour12 = parseInt(hour, 10);

            if (hour12 >= 12) {
                period = 'PM';
                if (hour12 > 12) {
                    hour12 -= 12;
                }
            } else if (hour12 === 0) {
                hour12 = 12;
            }

            return `${hour12}:${minute} ${period}`;
        }

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




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>