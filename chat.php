<?php
require_once 'config.php';
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$current_user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>WhatsApp Clone - Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #d1d7db;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 30%;
            background: #ffffff;
            border-right: 1px solid #d1d7db;
        }
        .chat-area {
            width: 70%;
            background: #efeae2;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: #f0f2f5;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .profile {
            display: flex;
            align-items: center;
        }
        .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .search-box {
            padding: 8px 15px;
            background: #f0f2f5;
        }
        .search-box input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 8px;
            background: white;
        }
        .chat-list {
            overflow-y: auto;
            height: calc(100% - 108px);
        }
        .chat-item {
            padding: 8px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f0f2f5;
            cursor: pointer;
        }
        .chat-item:hover {
            background: #f0f2f5;
        }
        .chat-item img {
            width: 49px;
            height: 49px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .chat-info {
            flex: 1;
        }
        .chat-info h4 {
            margin-bottom: 3px;
        }
        .chat-info p {
            color: #667781;
            font-size: 14px;
        }
        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .message {
            max-width: 65%;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 7.5px;
            position: relative;
        }
        .received {
            background: white;
            margin-right: auto;
        }
        .sent {
            background: #d9fdd3;
            margin-left: auto;
        }
        .message-input {
            background: #f0f2f5;
            padding: 10px;
            display: flex;
            align-items: center;
        }
        .message-input input {
            flex: 1;
            padding: 9px 12px;
            border: none;
            border-radius: 8px;
            margin: 0 10px;
        }
        .message-input button {
            background: none;
            border: none;
            color: #54656f;
            cursor: pointer;
            font-size: 20px;
        }
        .message-input button:hover {
            color: #00a884;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="header">
                <div class="profile">
                    <img src="<?php echo htmlspecialchars($current_user['profile_pic']); ?>" alt="Profile">
                    <span><?php echo htmlspecialchars($current_user['name']); ?></span>
                </div>
                <div class="actions">
                    <a href="logout.php" style="color: #54656f;"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search or start new chat" id="search-input">
            </div>
            <div class="chat-list" id="chat-list">
                <!-- Chat list will be loaded here -->
            </div>
        </div>
        <div class="chat-area" id="chat-area">
            <div class="header" id="chat-header" style="display: none;">
                <div class="profile">
                    <img src="default.png" alt="Contact" id="contact-img">
                    <span id="contact-name"></span>
                </div>
            </div>
            <div class="messages" id="messages">
                <!-- Messages will be loaded here -->
            </div>
            <div class="message-input" id="message-form" style="display: none;">
                <button type="button"><i class="far fa-smile"></i></button>
                <input type="text" placeholder="Type a message" id="message-input">
                <button type="button" id="send-button"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <script>
        let currentChat = null;
        
        // Load chat list
        function loadChatList() {
            fetch('get_users.php')
                .then(response => response.json())
                .then(users => {
                    const chatList = document.getElementById('chat-list');
                    chatList.innerHTML = '';
                    users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'chat-item';
                        div.onclick = () => startChat(user);
                        div.innerHTML = `
                            <img src="${user.profile_pic}" alt="Contact">
                            <div class="chat-info">
                                <h4>${user.name}</h4>
                                <p>${user.phone_number}</p>
                            </div>
                        `;
                        chatList.appendChild(div);
                    });
                });
        }

        // Start chat with user
        function startChat(user) {
            currentChat = user;
            document.getElementById('chat-header').style.display = 'flex';
            document.getElementById('message-form').style.display = 'flex';
            document.getElementById('contact-img').src = user.profile_pic;
            document.getElementById('contact-name').textContent = user.name;
            loadMessages();
        }

        // Load messages
        function loadMessages() {
            if (!currentChat) return;
            fetch(`get_messages.php?user_id=${currentChat.id}`)
                .then(response => response.json())
                .then(messages => {
                    const messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = '';
                    messages.forEach(message => {
                        const div = document.createElement('div');
                        div.className = `message ${message.sender_id == <?php echo $user_id; ?> ? 'sent' : 'received'}`;
                        div.textContent = message.message;
                        messagesDiv.appendChild(div);
                    });
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        }

        // Send message
        document.getElementById('send-button').onclick = () => {
            if (!currentChat) return;
            const input = document.getElementById('message-input');
            const message = input.value.trim();
            if (!message) return;

            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    receiver_id: currentChat.id,
                    message: message
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    input.value = '';
                    loadMessages();
                }
            });
        };

        // Search functionality
        document.getElementById('search-input').oninput = (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.chat-item').forEach(item => {
                const name = item.querySelector('h4').textContent.toLowerCase();
                const phone = item.querySelector('p').textContent.toLowerCase();
                if (name.includes(searchTerm) || phone.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        };

        // Initial load
        loadChatList();
        // Refresh messages every 5 seconds
        setInterval(loadMessages, 5000);
    </script>
</body>
</html>
