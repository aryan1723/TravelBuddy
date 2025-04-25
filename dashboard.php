<?php
// Start the session
session_start();

// Check if user is logged in - adjust this based on your authentication system
if (!isset($_SESSION['user_id'])) {
    // Store the current URL to redirect back after login
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    // Redirect to login page
    header('Location: login.php');
    exit();
}

// User is logged in, continue with dashboard content
require_once 'db_connect.php'; // Your database connection file

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
// Check if prepare() failed
if ($stmt === false) {
    // Handle error appropriately - log it, show a generic error message
    error_log("MySQLi prepare failed: " . $mysqli->error);
    // Maybe destroy session and redirect to login?
    session_destroy();
    header('Location: login.php?error=db_prepare');
    exit();
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User not found in database (session inconsistency)
    session_destroy();
    header('Location: login.php?error=user_not_found');
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();
// Make sure to close the connection when appropriate, e.g., $mysqli->close(); at the end of script or via register_shutdown_function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelBuddy - Your Travel Companion</title>
    <link rel="stylesheet" href="src/output.css"> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css"> <style>
        /* Simple scrollbar styling for chat */
        #chatboxMessages::-webkit-scrollbar {
            width: 5px;
        }
        #chatboxMessages::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        #chatboxMessages::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }
        #chatboxMessages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .message {
            max-width: 80%;
            word-wrap: break-word;
        }
        .user-message {
            background-color: #E0F2FE; /* Tailwind: bg-sky-100 */
            align-self: flex-end;
            margin-left: auto;
        }
        .bot-message {
            background-color: #F3F4F6; /* Tailwind: bg-gray-100 */
            align-self: flex-start;
            margin-right: auto;
        }
        /* Ensure the 'hidden' class works as expected */
        .hidden {
             display: none !important; /* Add !important just in case of override conflicts */
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-orange-500 to-orange-400 text-white shadow-md py-4 mb-10">
        <div class="container mx-auto flex justify-between items-center px-2 py-3">
            <div class="text-xl font-bold w-1/3 text-left">travelbuddy</div>
            <div id="greeting" class="text-lg font-semibold w-1/3 text-center">Good morning, Traveler!</div> <div class="w-1/3 text-right">
                <button id="menuBtn" class="text-2xl transform transition hover:scale-105">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <div id="sideMenu" class="fixed top-0 right-[-300px] w-72 h-full bg-white shadow-lg transition-transform duration-300 ease-in-out z-50 p-5 overflow-y-auto transform translate-x-0"> <div class="flex justify-between items-center mb-5 pb-3 border-b">
            <h2 class="text-orange-500 text-xl font-bold">TravelBuddy</h2>
            <button id="closeMenuBtn" class="text-gray-600 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="space-y-2">
             <a href="profile.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-user-circle text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>My Profile</span>
            </a>
            <a href="vtv.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-microphone-alt text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>Voice to Voice Translation</span>
            </a>
            <a href="voicet.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-microphone text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>Voice to Text Translation</span>
            </a>
            <a href="imaget.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-image text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>Image to Text Translation</span>
            </a>
             <a href="textv.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-text-height text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>Text to Speech</span>
            </a>
            <a href="pnrs.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-ticket-alt text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>PNR Status</span>
            </a>
             <a href="travelp.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                 <i class="fas fa-book text-orange-500 mr-3 text-lg w-5 text-center"></i>
                 <span>Travel Phrasebook</span>
            </a>
            <a href="logout.php" class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-orange-50 hover:translate-x-1 transition-all">
                <i class="fas fa-arrow-right-from-bracket text-orange-500 mr-3 text-lg w-5 text-center"></i>
                <span>Log out</span>
            </a>
        </div>
    </div>

    <main class="flex-grow container mx-auto p-4 py-8">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="vtv.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-microphone-alt"></i> </div>
                <h3 class="text-lg font-semibold mb-2">Voice to Voice Translation</h3>
                <p class="text-gray-600 text-sm">Speak and get real-time voice translation.</p> </a>
            <a href="voicet.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-microphone"></i> </div>
                <h3 class="text-lg font-semibold mb-2">Voice to Text Translation</h3>
                <p class="text-gray-600 text-sm">Convert your speech to text in multiple languages.</p>
            </a>
            <a href="imaget.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-image"></i> </div>
                <h3 class="text-lg font-semibold mb-2">Image to Text Translation</h3>
                <p class="text-gray-600 text-sm">Extract and translate text from images.</p> </a>
            <a href="textv.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-text-height"></i> </div>
                <h3 class="text-lg font-semibold mb-2">Text to Speech</h3>
                <p class="text-gray-600 text-sm">Convert written text into natural sounding speech.</p>
            </a>
            <a href="pnrs.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-ticket-alt"></i> </div>
                <h3 class="text-lg font-semibold mb-2">PNR Status</h3>
                <p class="text-gray-600 text-sm">Check your train ticket booking status.</p> </a>
            <a href="travelp.php" class="block bg-white rounded-xl shadow-md p-6 cursor-pointer hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="text-orange-500 text-4xl mb-4"> <i class="fas fa-book"></i> </div>
                <h3 class="text-lg font-semibold mb-2">Travel Phrasebook</h3>
                <p class="text-gray-600 text-sm">Essential phrases for travelers.</p> </a>
        </div>
    </main>

    <footer class="relative bg-[#FF5E00] text-white overflow-hidden mt-30">
         <div class="container mx-auto px-6 pt-24 pb-12 relative z-10"> <div class="flex flex-col md:flex-row justify-between items-start">
                <div class="mb-8 md:mb-0 md:w-1/3">
                    <div class="text-3xl font-bold mb-4">
                        travel<span class="text-white">buddy</span>
                    </div>
                    <p class="text-white/80 text-sm max-w-xs">
                        Your smart travel companion for every journey. Making railway travel seamless with real-time assistance.
                    </p>
                </div>

                <div class="w-full md:w-2/3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 footer-grid">
                    <div>
                        <h3 class="font-bold text-lg mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-sm"> <li><a href="index.php" class="text-white/80 hover:text-white transition">Home</a></li>
                            <li><a href="index.php#features" class="text-white/80 hover:text-white transition">Features</a></li>
                            <li><a href="index.php#about" class="text-white/80 hover:text-white transition">About Us</a></li>
                            <li><a href="index.php#contact" class="text-white/80 hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">Support</h3>
                        <ul class="space-y-2 text-sm"> 
                            <li><a href="support.html" class="text-white/80 hover:text-white transition">FAQ</a></li>
                            <li><a href="support.html" class="text-white/80 hover:text-white transition">Help Center</a></li>
                            <li><a href="support.html" class="text-white/80 hover:text-white transition">Privacy Policy</a></li>
                            <li><a href="support.html" class="text-white/80 hover:text-white transition">Terms of Service</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-4">Connect</h3>
                        <div class="flex space-x-4">
                            <a href="#" aria-label="Facebook" class="text-white/80 hover:text-white transition transform hover:scale-125">
                                <ion-icon name="logo-facebook" class="text-2xl"></ion-icon>
                            </a>
                            <a href="#" aria-label="Twitter" class="text-white/80 hover:text-white transition transform hover:scale-125">
                                <ion-icon name="logo-twitter" class="text-2xl"></ion-icon>
                            </a>
                            <a href="#" aria-label="Instagram" class="text-white/80 hover:text-white transition transform hover:scale-125">
                                <ion-icon name="logo-instagram" class="text-2xl"></ion-icon>
                            </a>
                            <a href="#" aria-label="LinkedIn" class="text-white/80 hover:text-white transition transform hover:scale-125">
                                <ion-icon name="logo-linkedin" class="text-2xl"></ion-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/20 mt-12 pt-6 text-center text-sm text-white/60"> &copy; <?php echo date("Y"); ?> Travelbuddy. All rights reserved.
            </div>
        </div>
    </footer>

    <button id="chatbot-toggle" class="fixed bottom-5 right-5 bg-orange-500 text-white w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-2xl z-[60] hover:bg-orange-600 transition-colors" aria-label="Toggle Chatbot">
        <i class="fas fa-comment-dots"></i>
    </button>

    <div id="chatbot-window" class="hidden fixed bottom-20 right-5 w-80 sm:w-96 h-[500px] bg-white rounded-lg shadow-xl border border-gray-200 flex flex-col z-50 transition-opacity duration-300 ease-in-out">
        <div class="bg-orange-500 text-white p-3 flex justify-between items-center rounded-t-lg">
            <h3 class="font-semibold text-lg">TravelBuddy Assistant</h3>
            <button id="chatbot-close" class="text-xl hover:text-gray-200" aria-label="Close Chatbot">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="chatboxMessages" class="flex-grow p-4 overflow-y-auto flex flex-col space-y-3">
            <div class="message bot-message p-2 rounded-lg text-sm">
                Hello! How can I help you with your travel plans today? </div>
            </div>

        <div class="p-3 border-t border-gray-200 flex items-center bg-gray-50 rounded-b-lg"> <input type="text" id="chatbot-input" placeholder="Ask me anything..." class="flex-grow border border-gray-300 rounded-l-md p-2 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500 text-sm" aria-label="Chatbot Input">
            <button id="chatbot-send" class="bg-orange-500 text-white px-4 h-full py-2 rounded-r-md hover:bg-orange-600 transition-colors text-sm flex items-center justify-center" aria-label="Send Message"> <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script src="js/dashboard.js" defer></script> <script>
        // --- Gemini API Configuration ---
        // WARNING: Storing API keys in client-side code is insecure! Use a backend proxy for production.
        const GEMINI_API_KEY = "AIzaSyBk5G9lWQVncYumqRgxDjWAFRxz8fBAXQ0"; // Your API key
        const GEMINI_API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=${GEMINI_API_KEY}`;

        // --- Wait for DOM to Load ---
        document.addEventListener('DOMContentLoaded', function() {

            // --- Get Chatbot DOM Elements ---
            const chatbotToggle = document.getElementById('chatbot-toggle');
            const chatbotWindow = document.getElementById('chatbot-window');
            const chatbotClose = document.getElementById('chatbot-close');
            const chatboxMessages = document.getElementById('chatboxMessages');
            const chatbotInput = document.getElementById('chatbot-input');
            const chatbotSend = document.getElementById('chatbot-send');

            // --- Error Check: Ensure elements exist ---
            if (!chatbotToggle || !chatbotWindow || !chatbotClose || !chatboxMessages || !chatbotInput || !chatbotSend) {
                console.error("Chatbot Error: Could not find one or more required HTML elements. Check IDs.");
                // Optionally disable the toggle button if setup fails
                if(chatbotToggle) chatbotToggle.style.display = 'none';
                return; // Stop execution of chatbot script if elements are missing
            }

            // --- Chatbot Event Listeners ---

            // Toggle Chat Window
            chatbotToggle.addEventListener('click', () => {
                const isHidden = chatbotWindow.classList.contains('hidden');
                // Use toggle which adds/removes based on presence
                chatbotWindow.classList.toggle('hidden');

                if (isHidden) { // If it *was* hidden, it's now visible
                    chatbotToggle.innerHTML = '<i class="fas fa-times"></i>';
                    chatbotToggle.setAttribute('aria-label', 'Close Chatbot');
                    chatbotInput.focus(); // Focus input when opening
                } else { // If it *was* visible, it's now hidden
                    chatbotToggle.innerHTML = '<i class="fas fa-comment-dots"></i>';
                     chatbotToggle.setAttribute('aria-label', 'Open Chatbot');
                }
            });

            // Close Chat Window
            chatbotClose.addEventListener('click', () => {
                // Explicitly add 'hidden' class
                chatbotWindow.classList.add('hidden');
                // Reset toggle button icon and label
                chatbotToggle.innerHTML = '<i class="fas fa-comment-dots"></i>';
                chatbotToggle.setAttribute('aria-label', 'Open Chatbot');
            });

            // Send Message on Button Click
            chatbotSend.addEventListener('click', handleSendMessage);

            // Send Message on Enter Key Press
            chatbotInput.addEventListener('keypress', (event) => {
                if (event.key === 'Enter' && !event.shiftKey) { // Allow shift+enter for newline if needed later
                     event.preventDefault(); // Prevent default form submission/newline
                    handleSendMessage();
                }
            });

            // --- Chatbot Functions (Defined inside DOMContentLoaded) ---

            /**
             * Displays a message in the chatbox.
             * @param {string} text - The message content.
             * @param {'user' | 'bot' | 'error' | 'typing'} sender - The sender type.
             * @returns {HTMLElement | null} The created message element or null if container not found.
             */
            function displayMessage(text, sender = 'bot') {
                 if (!chatboxMessages) return null; // Guard clause

                const messageElement = document.createElement('div');
                messageElement.classList.add('message', 'p-2', 'rounded-lg', 'text-sm');

                // Add specific styling based on sender type
                switch (sender) {
                    case 'user':
                        messageElement.classList.add('user-message');
                        messageElement.textContent = text; // Use textContent for user input (safer)
                        break;
                    case 'bot':
                        messageElement.classList.add('bot-message');
                        // Basic Markdown handling (bold/italics) - Can be expanded
                        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        messageElement.innerHTML = text; // Use innerHTML for bot to render basic HTML
                        break;
                     case 'error':
                         messageElement.classList.add('bot-message', 'text-red-600', 'italic'); // Style errors differently
                         messageElement.textContent = text;
                         break;
                    case 'typing':
                         messageElement.classList.add('bot-message', 'text-gray-500', 'italic');
                         messageElement.id = 'typing-indicator'; // ID to easily remove it
                         messageElement.textContent = text;
                         break;
                    default: // Default to bot style if sender is unknown
                         messageElement.classList.add('bot-message');
                         messageElement.textContent = text;
                }

                chatboxMessages.appendChild(messageElement);

                // Scroll to the bottom
                chatboxMessages.scrollTop = chatboxMessages.scrollHeight;
                return messageElement; // Return the element if needed (e.g., for removing typing indicator)
            }

             /**
              * Handles sending the message from input to the Gemini API.
              */
            async function handleSendMessage() {
                const messageText = chatbotInput.value.trim();
                if (!messageText || !chatbotInput || !chatbotSend) return; // Don't send empty messages or if elements are missing

                displayMessage(messageText, 'user');
                chatbotInput.value = ''; // Clear input field
                chatbotInput.disabled = true; // Disable input while waiting
                chatbotSend.disabled = true;
                chatbotSend.classList.add('opacity-50', 'cursor-not-allowed'); // Visual feedback

                // Add a "typing" indicator
                displayMessage('TravelBuddy is thinking...', 'typing');

                try {
                    const response = await fetch(GEMINI_API_URL, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            "contents": [{"parts": [{"text": messageText}]}],
                            // Optional: Add generationConfig or safetySettings if needed
                            // "generationConfig": { "temperature": 0.7, "maxOutputTokens": 1000 },
                            // "safetySettings": [ ... ]
                        }),
                    });

                    // Remove typing indicator regardless of success/failure below
                    const typingIndicator = document.getElementById('typing-indicator');
                    if (typingIndicator) typingIndicator.remove();


                    if (!response.ok) {
                        let errorMsg = `Sorry, API error: ${response.status}`;
                        try {
                            const errorData = await response.json();
                            console.error("API Error Response:", errorData);
                            // Try to get a more specific error message
                            errorMsg = `Sorry, I encountered an error: ${errorData?.error?.message || response.statusText}`;
                         } catch (e) {
                            // Ignore if response body is not JSON or empty
                            console.error("Could not parse API error response:", e);
                         }
                        displayMessage(errorMsg, 'error');
                        return; // Stop processing on API error
                    }

                    const data = await response.json();

                    // Extract the response text - **Crucially check the actual response structure**
                    let botResponseText = "Sorry, I couldn't understand the response format."; // Default
                    if (data.candidates && data.candidates[0]?.content?.parts?.[0]?.text) {
                        botResponseText = data.candidates[0].content.parts[0].text;
                    } else {
                        console.error("Unexpected API response structure:", JSON.stringify(data, null, 2)); // Log unexpected structure
                    }

                    displayMessage(botResponseText, 'bot');

                } catch (error) {
                     // Remove typing indicator if fetch itself failed
                    const typingIndicator = document.getElementById('typing-indicator');
                    if (typingIndicator) typingIndicator.remove();

                    console.error('Error sending message to Gemini:', error);
                    displayMessage('Sorry, there was a network issue or configuration problem. Please try again.', 'error');
                } finally {
                    // Re-enable input and button
                    chatbotInput.disabled = false;
                    chatbotSend.disabled = false;
                     chatbotSend.classList.remove('opacity-50', 'cursor-not-allowed');
                    chatbotInput.focus(); // Set focus back to input
                }
            } // End handleSendMessage

        }); // --- End of DOMContentLoaded ---
    </script>

</body>
</html>