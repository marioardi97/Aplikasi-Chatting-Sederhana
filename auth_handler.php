<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['secret_code'] = $user['secret_code'];
            echo "success";
        } else {
            echo "Invalid username or password";
        }
    } elseif ($_POST['action'] === 'register') {
        $username = ucfirst(strtolower($_POST['username'])); // Ubah username menjadi huruf pertama kapital
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $secret_code = $_POST['secret_code'];

        // Periksa apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR secret_code = ?");
        $stmt->bind_param("ss", $username, $secret_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $existingUser = $result->fetch_assoc();
            if ($existingUser['username'] === $username) {
                echo "Username already exists. Please choose another one.";
            } elseif ($existingUser['secret_code'] === $secret_code) {
                echo "Secret code already used. Please choose another one.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, secret_code) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $secret_code);

            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Registration failed. Please try again.";
            }
        }
    }
}
?>
