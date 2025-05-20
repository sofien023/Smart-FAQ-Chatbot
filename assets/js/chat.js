document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chatForm');
    const userInput = document.getElementById('userInput');
    const chatMessages = document.getElementById('chatMessages');

    // Function to add a message to the chat
    function addMessage(content, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        messageContent.textContent = content;
        
        const messageTime = document.createElement('div');
        messageTime.className = 'message-time';
        messageTime.textContent = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        messageDiv.appendChild(messageContent);
        messageDiv.appendChild(messageTime);
        chatMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Function to handle form submission
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const question = userInput.value.trim();
        if (!question) return;

        // Add user message to chat
        addMessage(question, true);
        userInput.value = '';

        try {
            // Send question to backend
            const response = await fetch('api/chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ question: question })
            });

            const data = await response.json();
            
            if (data.success) {
                addMessage(data.answer);
            } else {
                addMessage('Sorry, I encountered an error. Please try again later.');
            }
        } catch (error) {
            console.error('Error:', error);
            addMessage('Sorry, I encountered an error. Please try again later.');
        }
    });

    // Auto-focus input on load
    userInput.focus();
}); 