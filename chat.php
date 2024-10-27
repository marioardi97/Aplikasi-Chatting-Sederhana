<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Chat Room</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS untuk chat */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f2f5;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }

        .chat-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        #chat-box {
            height: 400px;
            overflow-y: auto;
            padding: 15px;
            background: #f8f9fa;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
        }

        .sent {
            background: #007bff;
            color: white;
            margin-left: auto;
        }

        .received {
            background: #e9ecef;
            margin-right: auto;
        }

        #chat-form {
            padding: 15px;
            display: flex;
            gap: 10px;
            background: white;
            border-top: 1px solid #ddd;
        }

        input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 16px; /* Ukuran font untuk mencegah zoom */
        }

        button {
            padding: 12px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .info-box {
            background: white;
            padding: 15px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .info-box strong {
            color: #007bff;
            font-size: 1.2em;
        }

        .connect-form {
            padding: 15px;
            display: flex;
            gap: 10px;
            border-bottom: 1px solid #ddd;
        }

        .connect-form input {
            flex: 1;
        }

        #connection-status {
            margin-right: 15px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            background: #dc3545;
            color: white;
        }

        #connection-status.connected {
            background: #28a745;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0;
                padding: 10px;
            }

            .message {
                max-width: 85%;
            }

            .header h2 {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <div class="header">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <div style="display: flex; align-items: center;">
                    <span id="connection-status">Not Connected</span>
                    <a href="logout.php" class="logout-btn" style="margin-left: 15px;">Logout</a>
                </div>
            </div>
            
            <div class="connect-form">
                <input type="text" id="connect-code" placeholder="Enter secret code to connect">
                <button onclick="connectUser()">Connect</button>
            </div>

            <div id="chat-box"></div>

            <form id="chat-form" style="display: none;">
                <input type="text" id="message" placeholder="Type your message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>

        <div class="info-box">
            <p>Your Secret Code: <strong><?php 
                require_once 'config.php';
                $stmt = $conn->prepare("SELECT secret_code FROM users WHERE username = ?");
                $stmt->bind_param("s", $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()) {
                    echo htmlspecialchars($row['secret_code']);
                }
                $stmt->close();
            ?></strong></p>
            <p>Share this code with others to let them connect with you.</p>
        </div>
    </div>

    <script src="chat.js"></script>
</body>
</html>