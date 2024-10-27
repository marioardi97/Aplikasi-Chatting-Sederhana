<?php
// cleanup.php

// Import konfigurasi database
require_once 'config.php';

try {
    // Buat koneksi menggunakan variabel dari config.php
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Hapus chat yang lebih lama dari 30 menit
    $sql = "DELETE FROM chat WHERE created_at < NOW() - INTERVAL 30 MINUTE";
    $result = $conn->query($sql);

    if ($result) {
        $deleted = $conn->affected_rows;
        echo "Success: Deleted " . $deleted . " messages";
    } else {
        throw new Exception("Error executing query: " . $conn->error);
    }

    $conn->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>