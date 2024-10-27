<?php
session_start();
if(isset($_SESSION['username'])) {
    header("Location: chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Login/Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        form {
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px; /* Ukuran font untuk mencegah zoom */
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #45a049;
        }
        .social-links {
            text-align: center;
            margin-top: 20px;
        }
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        .copyright {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form id="login-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <h2>Register</h2>
            <form id="register-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="secret_code" placeholder="Secret Code" required>
                <button type="submit">Register</button>
            </form>
        </div>

        <div class="social-links">
            <a href="https://marioardi.dev" target="_blank">Website</a>
            <a href="https://facebook.com/badas.net" target="_blank">Facebook</a>
            <a href="https://github.com/marioardi97" target="_blank">GitHub</a>
        </div>

        <div class="copyright">
            <p>&copy; <?php echo date("Y"); ?> Mario Ardi. All rights reserved.</p>
        </div>
    </div>

    <script src="auth.js"></script>
</body>
</html>
