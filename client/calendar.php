<?php
// Turn on error reporting at the top of the PHP file
function build_calendar($month, $year)
{
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-xs btn-success' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= " <a class='btn btn-xs btn-danger' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br>";

    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th class='header'>$day</th>";
    }
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    $currentDay = 1;
    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $today = $date == date('Y-m-d') ? "today" : "";

        $calendar .= "<td class='$today'><h4>$currentDay</h4></td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $calendar .= "<td class='empty'></td>", $l++)
            ;
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
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
    <title>Calendar</title>
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
    <!-- Footer -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="font-family: 'Nunito Sans', 'sans-serif';">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="../media/qplogo.jpg" alt="Qualipaws Logo" style="height: 80px; width: 80px;"
                    class="rounded-circle">
                <div class="ms-2">
                    <div style="font-size: 18px; font-weight: bold;"><span style="color:#1E98AE">QUALI</span><span
                            style="color:#399751">PAWS</span></div>
                    <div style="font-size: 14px;color:#399751">Animal Health Clinic</div>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="appointment.php">Appointments</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Calendar</a></li>
                    <li class="nav-item"><a class="nav-link" href="pet_profile.php">Pet Profile</a></li>
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

    <div class="container alert alert-default mt-4" style="background:#fff;margin-bottom: 40px">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" style="background:#007bff;border:none;color:white">
                    <h1>Calendar <i class="fas fa-paw"></i></h1>
                </div>
                <?php
                $dateComponents = getdate();
                if (isset($_GET['month']) && isset($_GET['year'])) {
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                } else {
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }
                build_calendar($month, $year);
                ?>
            </div>
        </div>
    </div>
    <script>
        function loadBookingForm(date) {
            // Use AJAX to load booking.php with the selected date parameter
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("bookingFormContainer").innerHTML = this.responseText;
                }
            };
            xhttp.open("GET", "booking.php?date=" + encodeURIComponent(date), true);
            xhttp.send();
        }
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.history.pushState(null, "", window.location.href);
        };
    </script>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-md-3 mb-3">
                    <h6>About us</h6>
                    <p>Your pet deserves quality care.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Quick Link</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">About us</a></li>
                        <li><a href="#">Services</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Contact Info</h6>
                    <p>F. Halili National Road, Barangay Tungkong Mangga, <br> City of San Jose del Monte, Bulacan,
                        Philippines</p>
                    <p>0913 219 9347</p>
                    <p>qualipawsph@gmail.com</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Developers</h6>
                    <p>Alberca, Jonas</p>
                    <p>Arizapa, John Alexander</p>
                    <p>Castillo, Charlie</p>
                    <p>Garcia, Jmswell</p>
                    <p>Wong, Shashie Mae</p>
                </div>
            </div>
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