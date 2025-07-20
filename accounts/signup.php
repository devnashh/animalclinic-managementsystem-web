<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .left-panel img {
            width: 200px;
            margin-bottom: 1rem;
        }

        .create-account-form {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            z-index: 3;
            transform: scale(0.9);
            /* Reduce form size for better fit */
            transform-origin: center;
        }

        h1,
        h3,
        p {
            margin: 0;
            text-align: center;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            margin-left: auto;
            height: 100%;
            position: relative;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .left-panel {
                display: none;
            }

            .login-container {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }

            .create-account-form {
                padding: 1rem;
                transform: scale(0.8);
                /* Further reduce size for smaller screens */
            }

            .form-row {
                grid-template-columns: 1fr;
                /* Single-column layout */
            }
        }
    </style>

</head>

<body>

    <div class="left-panel">
        <h1 class="mt-5 mb-5">WELCOME!</h1>
        <a href="signin.php">
            <img src="../media/qplogo.jpg" alt="Qualipaws Logo" class="rounded-circle mb-5">
        </a>
        <h3><span style="color:#1E98AE">QUALI</span>PAWS</h3>
        <p>ANIMAL HEALTH CLINIC</p>
    </div>
    <div class="login-container mt-5">
        <div class="create-account-form">
            <?php
            $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
            if (!empty($message)) {
                echo '<div class="alert alert-danger text-center" role="alert">' . $message . '</div>';
            }
            ?>

            <h3 class="text-center mb-4">Create an Account</h3>
            <form action="php/create_acc.php" method="POST">

                <!-- Personal Information -->
                <h5 class="mb-3 mt-4">Personal Information</h5>
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            placeholder="Enter full name">
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age" placeholder="Enter your age">
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number"
                            placeholder="Enter contact number">
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter email address">
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Enter address">
                    </div>
                </div>

                <!-- Account Information -->
                <h5 class="mb-3 mt-4">Account Information</h5>
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter username">
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter password" minlength="8" required>
                        <small id="passwordHelp" class="form-text text-muted">Password must be at least 8 characters
                            long.</small>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                            placeholder="Confirm password">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2 mt-4">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordHelp = document.getElementById('passwordHelp');

        passwordInput.addEventListener('input', () => {
            if (passwordInput.value.length < 8) {
                passwordHelp.style.color = 'red';
                passwordHelp.textContent = 'Password is too short.';
            } else {
                passwordHelp.style.color = 'green';
                passwordHelp.textContent = 'Password is valid.';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>