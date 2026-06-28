<div id="chat-widget" class="fixed bottom-6 right-6 z-50 font-sans">
    <!-- Chat Button -->
    <button id="chat-toggle" class="bg-green-600 hover:bg-green-700 text-white rounded-full p-4 shadow-lg focus:outline-none transition-transform transform hover:scale-105 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden absolute bottom-16 right-0 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden transition-all duration-300 transform scale-95 opacity-0" style="height: 500px; max-height: 80vh;">
        <!-- Header -->
        <div class="bg-green-600 text-white p-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="font-bold text-lg">Market Assistant</h3>
            </div>
            <button id="chat-close" class="text-white hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col space-y-3">
            <!-- Default Welcome Message -->
            <div class="flex justify-start">
                <div class="bg-gray-200 text-gray-800 rounded-lg rounded-tl-none px-4 py-2 max-w-[85%] text-sm shadow-sm">
                    Jambo! I am your Smart Stall Booking Assistant. How can I help you today?
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex items-center space-x-2">
                <input type="text" id="chat-input" placeholder="Type a message..." class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent" autocomplete="off" required>
                <button type="submit" id="chat-submit" class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-full p-2 flex items-center justify-center transition-colors focus:outline-none disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chat-toggle');
    const chatWindow = document.getElementById('chat-window');
    const chatClose = document.getElementById('chat-close');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatSubmit = document.getElementById('chat-submit');

    let chatHistory = [];

    // Toggle Chat Window
    function toggleChat() {
        if (chatWindow.classList.contains('hidden')) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
            }, 10);
            chatInput.focus();
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
        }
    }

    chatToggle.addEventListener('click', toggleChat);
    chatClose.addEventListener('click', toggleChat);

    // Escape key closes chat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !chatWindow.classList.contains('hidden')) {
            toggleChat();
        }
    });

    // Handle Form Submit
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message to UI
        appendMessage(message, 'user');
        chatInput.value = '';
        
        // Show loading indicator
        const loadingId = showLoading();
        chatInput.disabled = true;
        chatSubmit.disabled = true;

        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    history: chatHistory
                })
            });

            const data = await response.json();
            
            // Remove loading indicator
            removeMessage(loadingId);
            
            if (response.ok) {
                // Add assistant reply to UI
                appendMessage(data.reply, 'assistant');
                
                // Update history
                chatHistory.push({ role: 'user', content: message });
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                appendMessage(data.reply || 'Sorry, there was an error processing your request.', 'assistant', true);
            }
        } catch (error) {
            removeMessage(loadingId);
            appendMessage('Connection error. Please try again later.', 'assistant', true);
        } finally {
            chatInput.disabled = false;
            chatSubmit.disabled = false;
            chatInput.focus();
        }
    });

    // Helper functions for UI
    function appendMessage(text, sender, isError = false) {
        const div = document.createElement('div');
        div.className = sender === 'user' ? 'flex justify-end' : 'flex justify-start';
        
        const bubble = document.createElement('div');
        bubble.className = sender === 'user' 
            ? 'bg-green-100 text-green-900 rounded-lg rounded-tr-none px-4 py-2 max-w-[85%] text-sm shadow-sm'
            : (isError 
                ? 'bg-red-100 text-red-800 rounded-lg rounded-tl-none px-4 py-2 max-w-[85%] text-sm shadow-sm' 
                : 'bg-gray-200 text-gray-800 rounded-lg rounded-tl-none px-4 py-2 max-w-[85%] text-sm shadow-sm'
              );
              
        // Format markdown-like text (bold, lists) simply
        let formattedText = text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');
            
        bubble.innerHTML = formattedText;
        div.appendChild(bubble);
        chatMessages.appendChild(div);
        
        scrollToBottom();
    }

    function showLoading() {
        const id = 'loading-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex justify-start';
        div.innerHTML = `
            <div class="bg-gray-100 text-gray-500 rounded-lg rounded-tl-none px-4 py-2 text-sm shadow-sm flex items-center space-x-1">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        `;
        chatMessages.appendChild(div);
        scrollToBottom();
        return id;
    }

    function removeMessage(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
</script>
