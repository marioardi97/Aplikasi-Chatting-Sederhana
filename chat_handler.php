<?php
session_start();
require_once 'config.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    die(json_encode(['error' => 'Not authenticated']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Handle koneksi dengan user lain
    if ($action === 'connect') {
        $secret_code = $_POST['secret_code'];
        
        $stmt = $conn->prepare("SELECT username FROM users WHERE secret_code = ?");
        $stmt->bind_param("s", $secret_code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                'success' => true,
                'username' => $row['username']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
        $stmt->close();
    }
    
    // Handle pengiriman pesan
    else if ($action === 'send') {
        $sender = $_SESSION['username'];
        $receiver = $_POST['receiver'];
        $message = $_POST['message'];
        
        $stmt = $conn->prepare("INSERT INTO chat (sender, receiver, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $sender, $receiver, $message);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Message sent'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send message'
            ]);
        }
        $stmt->close();
    }
}

// Handle GET request untuk mengambil pesan
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    if ($action === 'get_messages') {
        $currentUser = $_SESSION['username'];
        $otherUser = $_GET['user'];
        
        // Ambil pesan antara kedua user
        $stmt = $conn->prepare("
            SELECT * FROM chat 
            WHERE (sender = ? AND receiver = ?) 
            OR (sender = ? AND receiver = ?) 
            ORDER BY created_at ASC
        ");
        $stmt->bind_param("ssss", $currentUser, $otherUser, $otherUser, $currentUser);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'sender' => $row['sender'],
                'message' => $row['message'],
                'created_at' => $row['created_at']
            ];
        }
        
        echo json_encode($messages);
        $stmt->close();
    }
}
?>