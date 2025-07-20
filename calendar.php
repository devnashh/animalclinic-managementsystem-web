<?php
// Turn on error reporting at the top of the PHP file
function build_calendar($month, $year)
{
    $mysqli = new mysqli('localhost', 'root', '', 'archangels');
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Query to fetch the count of approved bookings per day
    $stmt = $mysqli->prepare("SELECT appointment_date, COUNT(*) as booking_count FROM bookings WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ? AND status = 'Approved' GROUP BY appointment_date");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[$row['appointment_date']] = $row['booking_count'];
            }
        }
        $stmt->close();
    }

    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $datetoday = date('Y-m-d');

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

        $bookingCount = isset($bookings[$date]) ? $bookings[$date] : 0; // Default to 0 if no bookings

        // Show booking count without the button
        $calendar .= "<td class='$today'><h4>$currentDay</h4><p>$bookingCount Bookings</p></td>";

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
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            width: 250px;
            background-color: #3B3B3B;
            padding-top: 20px;
            border-radius: 0 10px 10px 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            font-size: 16px;
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: gray;
            text-decoration: none;
        }

        .sidebar a.active {
            background-color: gray;
            font-weight: bold;
        }

        .main-content {
            margin-left: 270px;
            /* width of the sidebar + some margin */
            padding: 20px;
        }

        /* Calendar table styling */
        table {
            table-layout: fixed;
            width: 50%;
        }

        .table-bordered {
            border: 2px solid #ddd;
        }

        th,
        td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        td {
            vertical-align: top;
            height: 120px;
        }

        .today {
            background-color: gray;
            color: white;
            font-weight: bold;
        }

        .empty {
            background-color: #f9f9f9;
        }

        /* Calendar container */
        .container {
            padding: 20px;
            margin-right: 1%;
            width: 80%;
        }

        h4 {
            margin: 0;
            font-size: 18px;
        }

        p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>

    </style>
</head>

<body>
    <div class="sidebar">
        <img src="../images/qplogo.jpg" alt="Logo"
            style=" max-height: 150px;margin-left:10%;margin-bottom:8px;border-radius:120px">
        <a href="dashb.php">Dashboard <i class="fas fa-chart-bar"></i></a>
        <a href="app_list.php">Appointment List <i class="fas fa-calendar-alt"></i></a>
        <a href="users.php">Registered Users <i class="fas fa-users"></i></a>
        <a href="calendar.php" class="active">Calendar <i class="fas fa-calendar"></i></a>
        <a href="admin_manual.php">Manual <i class="fas fa-book"></i></a>
        <a href="settings.php">Settings <i class="fas fa-cog"></i></a>
        <form action="../logout.php" method="post" class="form-inline my-2 my-lg-0"
            style="padding-top:40px; padding-left:12px">
            <button class="btn btn-outline-danger my-2 my-sm-0" type="submit"
                style="background-color:red;color:white">Logout <i class="fas fa-sign-out-alt"></i></button>
        </form>
    </div>
    <div class="container alert alert-default mt-4" style="background:#fff;margin-bottom: 40px">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" style="background:#3B3B3B;border:none;color:white">
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome for icons -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>