<?php
session_start(); // Memulai sesi

// Menghapus semua variabel sesi
$_SESSION = [];

// Menghancurkan sesi
session_destroy();

// Mengalihkan pengguna ke halaman login atau homepage
header("Location: login.php"); // Ganti 'login.php' dengan halaman yang sesuai
exit; // Pastikan untuk keluar dari skrip setelah pengalihan
?>
