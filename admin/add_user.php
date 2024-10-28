<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $secret_code = $_POST['secret_code'];

    $stmt = $conn->prepare("INSERT INTO users (username, secret_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $secret_code);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Add User</h1>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="secret_code" placeholder="Secret Code" required>
        <button type="submit">Add User</button>
    </form>
    <a href="index.php">Back to User List</a>
</body>
</html>
