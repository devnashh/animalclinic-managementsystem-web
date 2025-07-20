<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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

        .login-form {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            z-index: 3;
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
            /* Account for the left panel's width */
            margin-left: 10%;
            height: 100%;
            position: relative;
        }

        @media (max-width: 768px) {
            .left-panel {
                display: none;
            }

            .login-container {
                margin-left: 0;
                width: 100%;
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
    <div class="login-container">
        <div class="login-form">
            <h3 class="text-center mb-4">Login</h3>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); // Clear the error message after displaying
            }
            ?>
            <form action="php/login_acc.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" id="username"
                            placeholder="Enter username" required>
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Enter password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-lock"></i>
                        </button>
                    </div>

                    <div class="text-end">
                        <a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a>
                    </div>
                    <!-- Add reCAPTCHA -->
                    <!-- <div class="mt-3 mb-3">
                        <div class="g-recaptcha" data-sitekey="6Lc8deQqAAAAAGxfozVubi_pj-DmO58g_7G2CP6d"></div>
                    </div> -->
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
                <button type="button" class="btn btn-outline-primary w-100"
                    onclick="window.location.href='signup.php'">Create an Account</button>
            </form>

        </div>
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
    <script src="js/chatbot.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the icon
            this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
        });
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>