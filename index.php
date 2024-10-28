<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Secret Chat Login/Register</title>
    <style>
        * {
            box-sizing: border-box;
        }

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
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        .description {
            font-size: 1.1em;
            color: #333;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .usage-button {
            margin: 20px 0;
        }

        .toggle-button {
            padding: 12px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .toggle-button:hover {
            background: #0056b3;
        }

        .form-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .form-container.hidden {
            max-height: 0;
            opacity: 0;
        }

        .form-container:not(.hidden) {
            max-height: 300px;
            opacity: 1;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
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

        .usage-container {
            background: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
            font-size: 1em;
            color: #333;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, opacity 0.5s ease;
            opacity: 0;
        }

        .usage-container.hidden {
            max-height: 0;
            opacity: 0;
        }

        .usage-container:not(.hidden) {
            max-height: 200px;
            opacity: 1;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
                margin: 20px auto;
            }

            .toggle-button {
                font-size: 14px;
                padding: 10px;
            }

            input {
                font-size: 14px;
            }

            button {
                font-size: 14px;
            }
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #666;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secret Chat</h1>
        <p class="description">Aplikasi chat rahasia untuk komunikasi pribadi</p>

        <div class="buttons">
            <button class="toggle-button" onclick="toggleForm('login')">Login</button>
            <button class="toggle-button" onclick="toggleForm('register')">Register</button>
        </div>

        <div id="login-form-container" class="form-container hidden">
            <h2>Login</h2>
            <form id="login-form">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>

        <div id="register-form-container" class="form-container hidden">
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

        <div class="footer">
            <p>&copy; <?php echo date("Y"); ?> <a href="https://hostingan.id" target="_blank" style="text-decoration: none; color: inherit;">Mario Ardi. Powered By Hostingan.</a></p>
        </div>
    </div>

    <script src="auth.js"></script>
    <script>
        function toggleForm(form) {
            document.getElementById('login-form-container').classList.add('hidden');
            document.getElementById('register-form-container').classList.add('hidden');

            if (form === 'login') {
                document.getElementById('login-form-container').classList.remove('hidden');
            } else if (form === 'register') {
                document.getElementById('register-form-container').classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
