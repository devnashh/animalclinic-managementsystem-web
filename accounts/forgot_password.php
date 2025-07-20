<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('../media/paw_bg.png');
            background-repeat: repeat;
            background-size: 750px;
            margin: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .left-panel {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #BBE5F5;
            width: 25%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            color: #399751;
            height: 100%;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .left-panel img {
            width: 200px;
            margin-bottom: 1rem;
        }

        .reset-form-container {
            margin-left: 30%;
            margin-right: 30%;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .reset-form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="left-panel">
        <h1 class="mt-5 mb-5">WELCOME!</h1>
        <a href="../index.html">
            <img src="../media/qplogo.jpg" alt="Qualipaws Logo" class="rounded-circle mb-5">
        </a>
        <h3><span style="color:#1E98AE">QUALI</span>PAWS</h3>
        <p>ANIMAL HEALTH CLINIC</p>
    </div>

    <!-- Forgot Password Form -->
    <div class="reset-form-container">
        <h2>Forgot Password</h2>
        <!-- Display feedback messages -->
        <?php
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success" role="alert">' . htmlspecialchars($_GET['success']) . '</div>';
        }
        if (isset($_GET['error'])) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
        }
        ?>
        <form action="php/reset_password.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Enter your email</label>
                <input type="email" class="form-control" id="email" name="email" required
                    placeholder="Enter your registered email">
            </div>
            <button type="submit" class="btn btn-primary w-100">Confirm</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>