<?php
session_start();
require_once 'config/database.php';

// Generate a unique session ID if not exists
if (!isset($_SESSION['chat_session_id'])) {
    $_SESSION['chat_session_id'] = uniqid('chat_', true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University FAQ Chatbot</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h1>University FAQ Chatbot</h1>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <div class="message-content">
                    Hello! I'm your university assistant. How can I help you today?
                </div>
                <div class="message-time"><?php echo date('H:i'); ?></div>
            </div>
        </div>
        <div class="chat-input">
            <form id="chatForm">
                <input type="text" id="userInput" placeholder="Type your question here..." required>
                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script src="assets/js/chat.js"></script>
</body>
</html> 