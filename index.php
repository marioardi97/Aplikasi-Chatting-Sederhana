<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: chat.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Secret Chat</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <h1>Secret Chat</h1>
        <p class="description">Aplikasi chat rahasia untuk komunikasi pribadi</p>
        <div class="buttons">
            <button class="toggle-button" onclick="toggleForm('login')">Login</button>
            <button class="toggle-button" onclick="toggleForm('register')">Register</button>
        </div>

        <!-- Tombol Cara Penggunaan -->
        <div class="usage-button">
            <button class="toggle-button" onclick="toggleUsage()">Cara Penggunaan</button>
        </div>
        
        <!-- Form Login -->
        <div id="login-form" class="form-container hidden">
            <h2>Login</h2>
            <form>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        </div>

        <!-- Form Register -->
        <div id="register-form" class="form-container hidden">
            <h2>Register</h2>
            <form>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="secret_code" placeholder="Secret Code" required>
                <button type="submit">Register</button>
            </form>
        </div>

        <!-- Informasi Cara Penggunaan -->
        <div id="usage-info" class="usage-container hidden">
            <p>Daftar terlebih dahulu, lalu login. Untuk dapat terhubung dengan lawan bicara, kalian perlu saling memasukkan secret code — yaitu kode yang kalian masukkan saat registrasi dan juga terlihat di dashboard Secret Chat. Tenang, chat akan otomatis terhapus setiap 30 menit.</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Mario Ardi.</p>
        <p>
            <a href="https://github.com/marioardi97/Aplikasi-Chatting-Sederhana" target="_blank">Secret Chat GitHub Repository</a> | 
            <a href="https://facebook.com/badas.net" target="_blank">Facebook</a> | 
            <a href="https://github.com/marioardi97" target="_blank">GitHub</a> | 
            <a href="https://www.linkedin.com/in/mario-ardi-09b9101ab/" target="_blank">LinkedIn</a> | 
            <a href="https://hostingan.id" target="_blank">Support by Hostingan</a>
        </p>
    </div>

    <script>
        function toggleForm(formId) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            
            if (formId === 'login') {
                loginForm.classList.toggle('hidden');
                registerForm.classList.add('hidden');
            } else if (formId === 'register') {
                registerForm.classList.toggle('hidden');
                loginForm.classList.add('hidden');
            }
        }

        function toggleUsage() {
            const usageInfo = document.getElementById('usage-info');
            usageInfo.classList.toggle('hidden');
        }
    </script>
</body>
</html>
