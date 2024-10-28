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

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        // Cek apakah password baru dan konfirmasi sama
        if ($new_password === $confirm_password) {
            // Query untuk mendapatkan data admin
            $stmt = $conn->prepare("SELECT * FROM ganteng WHERE username = ? AND password = MD5(?)");
            $stmt->bind_param("ss", $_SESSION['admin_username'], $current_password);
            $stmt->execute();
            $result = $stmt->get_result();

            // Cek apakah password saat ini benar
            if ($result->num_rows > 0) {
                // Hash password baru
                $hashed_new_password = md5($new_password);
                // Update password
                $stmt = $conn->prepare("UPDATE ganteng SET password = ? WHERE username = ?");
                $stmt->bind_param("ss", $hashed_new_password, $_SESSION['admin_username']);
                $stmt->execute();
                echo "<script>alert('Password berhasil diubah!');</script>";
            } else {
                echo "<script>alert('Password saat ini salah.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Password baru dan konfirmasi tidak cocok.');</script>";
        }
    } else {
        echo "<script>alert('Silakan isi semua field.');</script>";
    }
}

// Proses menambah pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $secret_code = $_POST['secret_code'];

    if (!empty($username) && !empty($password) && !empty($secret_code)) {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username sudah tersedia!');</script>";
        } else {
            // Cek apakah secret code sudah ada
            $stmt = $conn->prepare("SELECT * FROM users WHERE secret_code = ?");
            $stmt->bind_param("s", $secret_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Secret code sudah digunakan!');</script>";
            } else {
                // Hash password menggunakan bcrypt
                $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

                // Masukkan pengguna baru ke tabel users
                $stmt = $conn->prepare("INSERT INTO users (username, password, secret_code) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hashed_password, $secret_code);
                $stmt->execute();
                echo "<script>alert('Pengguna berhasil ditambahkan!');</script>";
                $stmt->close();
            }
        }
    }
}

// Hitung total pengguna yang terdaftar
$total_result = $conn->query("SELECT COUNT(*) AS total FROM users");
$total_users = $total_result->fetch_assoc()['total'];

// Hitung jumlah pengguna yang online (asumsi: pengguna online jika aktivitas terakhir dalam 5 menit terakhir)
$time_limit = date('Y-m-d H:i:s', strtotime('-24 hours'));
$online_result = $conn->query("SELECT COUNT(*) AS online_count FROM users WHERE last_activity > '$time_limit'");
$online_users = $online_result->fetch_assoc()['online_count'];

// Ambil daftar pengguna
$limit = 10; // Batasi jumlah user per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ambil daftar pengguna
$stmt = $conn->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Chat</title>
    <link rel="stylesheet" href="style.css"> <!-- Ganti dengan path stylesheet Anda -->
    <style>
        /* CSS Internal untuk Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .button {
            background-color: #5bc0de; /* Warna default */
            color: white;
            padding: 10px 15px; /* Sesuaikan padding dengan tombol lainnya */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Menghilangkan garis bawah */
            display: inline-block; /* Memastikan tombol dapat berperilaku seperti tombol */
            margin: 5px; /* Jarak antar tombol */
        }

        .button:hover {
            background-color: #31b0d5; /* Warna saat hover */
        }

        .logout-btn {
            background-color: #d9534f; /* Warna merah untuk logout */
            color: white;
        }

        .logout-btn:hover {
            background-color: #c9302c; /* Warna saat hover */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Dashboard Admin | Secret Chat</h2>

        <div class="dropdown">
            <button class="dropdown-button button">Admin Pass</button>
            <div class="dropdown-content">
                <form method="POST" action="">
                    <input type="password" name="current_password" placeholder="Password Saat Ini" required>
                    <input type="password" name="new_password" placeholder="Password Baru" required>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    <button type="submit" name="reset_password" class="button">Ubah Password</button>
                </form>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropdown-button button">Add User</button>
            <div class="dropdown-content">
                <form method="POST" action="">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="text" name="secret_code" placeholder="Secret Code" required>
                    <button type="submit" name="add_user" class="button">Add User</button>
                </form>
            </div>
        </div>

        <a href="logout.php" class="logout-btn button">Logout</a>

        <h3>Total Pengguna: <?php echo $total_users; ?></h3>
        <h3>Pengguna Online: <?php echo $online_users; ?></h3> <!-- Menampilkan jumlah pengguna online -->
        <h3>Daftar Pengguna</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Secret Code</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['secret_code']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="btn edit">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn delete" onclick="return confirm('Yakin ingin menghapus pengguna ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
<div class="pagination">
    <?php
    $total_pages = ceil($total_users / $limit);
    $max_links = 5; // Jumlah halaman yang ditampilkan sekaligus

    // Hitung batas awal dan akhir halaman yang akan ditampilkan
    $start = max(1, $page - floor($max_links / 2));
    $end = min($total_pages, $start + $max_links - 1);

    // Jika halaman saat ini tidak di awal, tampilkan tombol Previous
    if ($page > 1) {
        echo '<a href="?page=' . ($page - 1) . '">&laquo; Previous</a>';
    }

    // Tampilkan link halaman
    for ($i = $start; $i <= $end; $i++) {
        echo '<a href="?page=' . $i . '" class="' . ($i === $page ? 'active' : '') . '">' . $i . '</a>';
    }

    // Jika halaman saat ini tidak di akhir, tampilkan tombol Next
    if ($page < $total_pages) {
        echo '<a href="?page=' . ($page + 1) . '">Next &raquo;</a>';
    }
    ?>
</div>
        </div>
    </div>
</body>
</html>
