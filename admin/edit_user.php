<?php
// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file config
require_once('../config.php');

// Mulai session
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_username'])) {
    header('Location: login.php'); // Alihkan ke halaman login jika belum login
    exit();
}

// Ambil user yang ingin diedit
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Proses update pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $secret_code = $_POST['secret_code'];

    if (!empty($username) && !empty($secret_code)) {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username sudah tersedia!');</script>";
        } else {
            // Cek apakah secret code sudah ada
            $stmt = $conn->prepare("SELECT * FROM users WHERE secret_code = ? AND id != ?");
            $stmt->bind_param("si", $secret_code, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Secret code sudah digunakan!');</script>";
            } else {
                // Update password jika diisi
                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
                    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, secret_code = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $username, $hashed_password, $secret_code, $user_id);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET username = ?, secret_code = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $username, $secret_code, $user_id);
                }
                $stmt->execute();
                $stmt->close();
                header('Location: dashboard.php'); // Alihkan kembali ke dashboard setelah update
                exit();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css"> <!-- Ganti dengan path stylesheet Anda -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Edit Pengguna</h2>
        <form method="POST" action="">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <input type="password" name="password" placeholder="Password (kosongkan jika tidak ingin mengubah)">
            <input type="text" name="secret_code" value="<?php echo htmlspecialchars($user['secret_code']); ?>" required>
            <div class="button-group"> <!-- Tambahkan div untuk grup tombol -->
                <button type="submit" name="update_user" class="button">Update Pengguna</button>
                <a href="dashboard.php" class="button">Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
