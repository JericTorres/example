function initializeChatbot() {
    const chatbotIcon = document.getElementById("chatbot-icon");
    const chatbotWindow = document.getElementById("chatbot-window");
    const closeChatBtn = document.getElementById("close-chat");
    const sendBtn = document.getElementById("send-btn");
    const userInput = document.getElementById("user-input");
    const chatContent = document.getElementById("chat-content");

    // Open chatbot
    chatbotIcon.addEventListener("click", () => {
        chatbotWindow.style.display = "block";
    });

    // Close chatbot
    closeChatBtn.addEventListener("click", () => {
        chatbotWindow.style.display = "none";
    });

    // Send on button click
    sendBtn.addEventListener("click", () => {
        handleUserMessage();
    });

    // Send on Enter key
    userInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            handleUserMessage();
        }
    });

    // Handle sending user message
    async function handleUserMessage() {
        const userMessage = userInput.value.trim();
        if (!userMessage) return;

        displayMessage(userMessage, "user-message");
        userInput.value = "";

        const aiReply = await getAIResponse(userMessage);
        displayMessage(aiReply, "ai-message");

        // Scroll to bottom
        chatContent.scrollTop = chatContent.scrollHeight;
    }

    // Append message to chat
    function displayMessage(message, className) {
        const messageDiv = document.createElement("div");
        messageDiv.className = className + " mb-2 p-2 rounded";
        messageDiv.textContent = message;
        chatContent.appendChild(messageDiv);
    }

    // Fetch AI-like response from PHP
    async function getAIResponse(userMessage) {
        try {
            const response = await fetch("../PHP/chatbot.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ prompt: userMessage })
            });

            const data = await response.json();
            return data.output_text || "Sorry, I didn’t understand that.";
        } catch (err) {
            console.error("Fetch error:", err);
            return "Oops! Something went wrong.";
        }
    }
}

// Get the header and listen for scroll events
window.addEventListener('scroll', function () {
   const header = document.querySelector('header');
   if (window.scrollY > 50) {  // You can adjust this threshold
      header.classList.add('scrolled');
   } else {
      header.classList.remove('scrolled');
   }
});


// Start when page is ready
document.addEventListener("DOMContentLoaded", initializeChatbot);
