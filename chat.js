let connectedUser = null;
let messagePollingInterval;

// Update status koneksi
function updateConnectionStatus(status, username = null) {
    const statusElement = document.getElementById('connection-status');
    if (status === 'connected') {
        statusElement.textContent = `Connected to ${username}`; // Tambahkan backticks
        statusElement.classList.add('connected');
    } else {
        statusElement.textContent = 'Not Connected';
        statusElement.classList.remove('connected');
    }
}

// Fungsi untuk memformat waktu
function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Fungsi untuk scroll otomatis ke pesan terbaru
function scrollToBottom() {
    const chatBox = document.getElementById('chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Fungsi untuk menampilkan loading indicator
function showLoading(show = true) {
    const loadingElement = document.getElementById('loading-indicator');
    if (loadingElement) {
        loadingElement.style.display = show ? 'block' : 'none';
    }
}

function connectUser() {
    const secretCode = document.getElementById('connect-code').value;
    if (!secretCode.trim()) {
        alert('Please enter a secret code');
        return;
    }
    
    showLoading(true);
    fetch('chat_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=connect&secret_code=${encodeURIComponent(secretCode)}` // Tambahkan backticks
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            connectedUser = data.username;
            updateConnectionStatus('connected', data.username);
            document.querySelector('.connect-form').style.display = 'none';
            document.getElementById('chat-form').style.display = 'flex';
            document.getElementById('connect-code').value = '';
            startMessagePolling();
            loadMessages();
            
            // Tambahkan notifikasi
            showNotification(`Connected to ${data.username}`); // Tambahkan backticks
        } else {
            alert('Invalid secret code');
            updateConnectionStatus('disconnected');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Connection failed');
        updateConnectionStatus('disconnected');
    })
    .finally(() => {
        showLoading(false);
    });
}

function startMessagePolling() {
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
    }
    messagePollingInterval = setInterval(loadMessages, 2000);
}

function loadMessages() {
    if (!connectedUser) return;
    
    fetch(`chat_handler.php?action=get_messages&user=${encodeURIComponent(connectedUser)}`) // Tambahkan backticks
    .then(response => response.json())
    .then(messages => {
        const chatBox = document.getElementById('chat-box');
        const currentScroll = chatBox.scrollTop;
        const isScrolledToBottom = (chatBox.scrollHeight - chatBox.clientHeight) <= (currentScroll + 1);

        chatBox.innerHTML = messages.map(msg => `
            <div class="message ${msg.sender === connectedUser ? 'received' : 'sent'}">
                <span class="sender">${msg.sender}</span>
                <div class="message-content">
                    <span class="text">${msg.message}</span>
                    <small class="time">${formatDateTime(msg.created_at)}</small>
                </div>
                ${msg.sender === connectedUser ? 
                    '<span class="status">✓</span>' : ''}
            </div>
        `).join('');
        
        // Hanya scroll ke bawah jika user sudah di bawah
        if (isScrolledToBottom) {
            scrollToBottom();
        }
    })
    .catch(error => console.error('Error loading messages:', error));
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message) {
    if (!("Notification" in window)) {
        return;
    }

    if (Notification.permission === "granted") {
        new Notification("Chat App", { body: message });
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then(permission => {
            if (permission === "granted") {
                new Notification("Chat App", { body: message });
            }
        });
    }
}

// Handler untuk form chat
document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!connectedUser) {
        alert('Please connect to a user first');
        return;
    }
    
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Disable form sementara
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    
    fetch('chat_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=send&message=${encodeURIComponent(message)}&receiver=${encodeURIComponent(connectedUser)}` // Tambahkan backticks
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageInput.value = '';
            loadMessages();
            // Play send sound
            const audio = new Audio('send.mp3');
            audio.play();
        } else {
            alert('Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message');
    })
    .finally(() => {
        submitButton.disabled = false;
        messageInput.focus();
    });
});

// Event listener untuk input pesan
document.getElementById('message').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
});

// Handler untuk disconnect
function disconnect() {
    if (connectedUser) {
        connectedUser = null;
        updateConnectionStatus('disconnected');
        document.querySelector('.connect-form').style.display = 'flex';
        document.getElementById('chat-form').style.display = 'none';
        document.getElementById('chat-box').innerHTML = '';
        if (messagePollingInterval) {
            clearInterval(messagePollingInterval);
        }
    }
}

// Inisialisasi
document.getElementById('chat-form').style.display = 'none';
updateConnectionStatus('disconnected');

// Clean up
window.addEventListener('unload', function() {
    if (messagePollingInterval) {
        clearInterval(messagePollingInterval);
    }
});

// Request notification permission
if ("Notification" in window) {
    Notification.requestPermission();
}
