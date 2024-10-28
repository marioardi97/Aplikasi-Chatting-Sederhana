<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file config
require_once('../config.php');

// Mulai session
session_start();

// Cek jika pengguna sudah login
if (isset($_SESSION['admin_username'])) {
    header('Location: dashboard.php'); // Alihkan ke halaman dashboard jika sudah login
    exit();
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (!empty($username) && !empty($password)) {
        // Query untuk mendapatkan data admin
        $stmt = $conn->prepare("SELECT * FROM ganteng WHERE username = ? AND password = MD5(?)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah ada admin yang cocok
        if ($result->num_rows > 0) {
            // Simpan informasi admin dalam session
            $_SESSION['admin_username'] = $username;
            header('Location: dashboard.php'); // Alihkan ke halaman dashboard
            exit();
        } else {
            $error_message = "Username atau password salah.";
        }

        $stmt->close();
    } else {
        $error_message = "Silakan masukkan username dan password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Mengatur tinggi body agar penuh */
        }

        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%; /* Lebar maksimal 90% */
            max-width: 400px; /* Lebar maksimal 400px */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #4cae4c;
        }

        /* Responsive Styles */
        @media (max-width: 500px) {
            .login-container {
                width: 95%; /* Mengatur lebar menjadi 95% pada layar kecil */
            }

            h2 {
                font-size: 24px; /* Ukuran font judul lebih kecil */
            }

            input[type="text"],
            input[type="password"],
            button {
                font-size: 14px; /* Ukuran font untuk input dan tombol lebih kecil */
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Admin</h2>
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
