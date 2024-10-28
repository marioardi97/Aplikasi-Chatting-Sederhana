<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();
?>
