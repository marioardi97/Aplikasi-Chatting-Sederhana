<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set karakter encoding
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Jakarta');
?>