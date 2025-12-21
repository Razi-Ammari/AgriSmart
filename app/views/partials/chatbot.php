<!-- Agro Assistant Chatbot -->
<div class="agro-chatbot">
    <!-- Floating Bubble -->
    <button class="chatbot-bubble" id="chatbotBubble" aria-label="Open Agro Assistant">
        <i class="bi bi-chat-dots"></i>
        <span class="bubble-pulse"></span>
    </button>

    <!-- Chat Panel -->
    <div class="chatbot-panel" id="chatbotPanel">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-avatar">
                <i class="bi bi-flower1"></i>
            </div>
            <div class="chatbot-title">
                <h4>Agro Assistant</h4>
                <span class="chatbot-status">Online</span>
            </div>
            <button class="chatbot-close" id="chatbotClose" aria-label="Close chat">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Chat Body -->
        <div class="chatbot-body" id="chatbotBody">
            <!-- Welcome Message -->
            <div class="chat-message bot-message">
                <div class="message-avatar">🌱</div>
                <div class="message-content">
                    <p>Hi! I'm your Agro Assistant. How can I help you today?</p>
                </div>
            </div>
        </div>

        <!-- Quick Replies Container -->
        <div class="chatbot-quick-replies" id="quickReplies">
            <!-- Quick reply buttons will be inserted here by JS -->
        </div>
    </div>
</div>

<style>
/* Chatbot Container */
.agro-chatbot {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Poppins', sans-serif;
}

/* Floating Bubble */
.chatbot-bubble {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    border: none;
    color: white;
    font-size: 26px;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(46, 125, 50, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.chatbot-bubble:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(46, 125, 50, 0.5);
}

.chatbot-bubble::before {
    content: 'Need help?';
    position: absolute;
    bottom: 100%;
    right: 0;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 12px;
    white-space: nowrap;
    margin-bottom: 8px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}

.chatbot-bubble:hover::before {
    opacity: 1;
}

/* Pulse Animation */
.bubble-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(46, 125, 50, 0.4);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Chat Panel */
.chatbot-panel {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 360px;
    height: 500px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.chatbot-panel.active {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: auto;
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    padding: 16px;
    border-radius: 16px 16px 0 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.chatbot-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chatbot-title {
    flex: 1;
}

.chatbot-title h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.chatbot-status {
    font-size: 11px;
    opacity: 0.9;
}

.chatbot-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    padding: 4px;
    opacity: 0.9;
    transition: opacity 0.3s;
}

.chatbot-close:hover {
    opacity: 1;
}

/* Chat Body */
.chatbot-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.chatbot-body::-webkit-scrollbar {
    width: 6px;
}

.chatbot-body::-webkit-scrollbar-track {
    background: transparent;
}

.chatbot-body::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

/* Chat Messages */
.chat-message {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    font-size: 24px;
    flex-shrink: 0;
}

.bot-message .message-content {
    background: white;
    border-radius: 12px 12px 12px 4px;
    padding: 12px 14px;
    max-width: 75%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.user-message {
    flex-direction: row-reverse;
}

.user-message .message-content {
    background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
    color: white;
    border-radius: 12px 12px 4px 12px;
    padding: 10px 14px;
    max-width: 75%;
    box-shadow: 0 2px 4px rgba(46, 125, 50, 0.2);
}

.message-content p {
    margin: 0;
    font-size: 14px;
    line-height: 1.5;
}

.message-content ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
    font-size: 13px;
}

.message-content li {
    margin: 4px 0;
}

/* Quick Replies */
.chatbot-quick-replies {
    padding: 12px 16px 16px;
    background: white;
    border-radius: 0 0 16px 16px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.quick-reply-btn {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
}

.quick-reply-btn:hover {
    background: #2e7d32;
    color: white;
    border-color: #2e7d32;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
}

.quick-reply-btn:active {
    transform: translateY(0);
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 14px;
}

.typing-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #2e7d32;
    animation: typing 1.4s infinite;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
        opacity: 0.5;
    }
    30% {
        transform: translateY(-10px);
        opacity: 1;
    }
}

/* Mobile Responsive */
@media (max-width: 480px) {
    .chatbot-panel {
        width: calc(100vw - 48px);
        max-width: 360px;
    }
}

@media (max-width: 360px) {
    .agro-chatbot {
        bottom: 16px;
        right: 16px;
    }
    
    .chatbot-bubble {
        width: 54px;
        height: 54px;
        font-size: 22px;
    }
    
    .chatbot-panel {
        width: calc(100vw - 32px);
        height: 450px;
        bottom: 70px;
    }
}
</style>

<script>
// Chatbot Knowledge Base
const chatbotData = {
    // Initial questions
    initial: [
        { id: 'recommendations', text: '🌱 Product recommendations', emoji: '🌱' },
        { id: 'growing', text: '☀️ Growing conditions', emoji: '☀️' },
        { id: 'watering', text: '💧 Watering & soil tips', emoji: '💧' },
        { id: 'buying', text: '🛒 Shopping help', emoji: '🛒' },
        { id: 'account', text: '🔐 Account & login', emoji: '🔐' }
    ],
    
    // Responses and follow-up questions
    responses: {
        // Product Recommendations
        recommendations: {
            answer: "Our AI Recommendations system helps you find the perfect plants! Just provide your climate conditions (temperature, humidity, season) and we'll suggest the best matches. Visit the 'AI Recommendations' page to get started! 🌱",
            followUp: [
                { id: 'how_ai_works', text: 'How does the AI work?' },
                { id: 'climate_info', text: 'What climate info do I need?' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        how_ai_works: {
            answer: "Our AI uses a smart matching algorithm that considers:\n• Temperature range (35% weight)\n• Humidity levels (30% weight)\n• Growing season (35% weight)\n\nIt scores each product and shows you the best matches! 🤖",
            followUp: [
                { id: 'try_ai', text: 'Try AI recommendations' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        climate_info: {
            answer: "You'll need to provide:\n☀️ Temperature range (e.g., 15-25°C)\n💧 Humidity level (low/medium/high)\n📅 Current season (spring/summer/fall/winter)\n\nThat's it! We'll do the rest. 🎯",
            followUp: [
                { id: 'try_ai', text: 'Get recommendations now' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        // Growing Conditions
        growing: {
            answer: "Each product shows growing conditions like:\n☀️ Sunlight needs (full sun, partial shade, shade)\n💧 Water requirements\n🌡️ Temperature range\n📅 Best planting season\n\nCheck the product details page for complete info! 🌿",
            followUp: [
                { id: 'sunlight', text: 'What does full-sun mean?' },
                { id: 'browse_products', text: 'Browse products' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        sunlight: {
            answer: "Sunlight requirements:\n☀️ Full Sun: 6+ hours direct sunlight\n⛅ Partial Shade: 3-6 hours sunlight\n🌥️ Shade: Less than 3 hours\n\nMatch your garden's conditions! 🌞",
            followUp: [
                { id: 'browse_products', text: 'View products' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        // Watering & Soil
        watering: {
            answer: "Watering tips:\n💧 Water deeply but less frequently\n🌱 Check soil moisture first\n🌞 Water early morning or evening\n🌾 Different plants need different amounts\n\nEach product lists its specific needs! 💚",
            followUp: [
                { id: 'soil_tips', text: 'Soil preparation tips' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        soil_tips: {
            answer: "Healthy soil basics:\n🌱 Use well-draining soil\n🍂 Add organic compost\n🌾 Test pH levels (most plants prefer 6-7)\n💚 Mulch to retain moisture\n\nHealthy soil = healthy plants! 🌿",
            followUp: [
                { id: 'browse_products', text: 'Shop for plants' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        // Buying & Cart
        buying: {
            answer: "Shopping is easy:\n1️⃣ Browse products or use AI recommendations\n2️⃣ Click on a product to view details\n3️⃣ Select quantity and click 'Add to Cart'\n4️⃣ View cart and proceed to checkout\n\nSimple as that! 🛒",
            followUp: [
                { id: 'add_to_cart', text: 'How to add to cart?' },
                { id: 'checkout_info', text: 'Checkout process' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        add_to_cart: {
            answer: "To add products:\n1️⃣ Go to product details page\n2️⃣ Choose your quantity\n3️⃣ Click 'Buy Now'\n4️⃣ Choose 'Go to Cart' or 'Continue Shopping'\n\nNote: Sellers can't buy their own products! 🛍️",
            followUp: [
                { id: 'view_cart', text: 'View my cart' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        checkout_info: {
            answer: "Checkout steps:\n1️⃣ Review items in cart\n2️⃣ Enter shipping address\n3️⃣ Choose payment method\n4️⃣ Confirm order\n\nTrack your order in 'My Orders' section! 📦",
            followUp: [
                { id: 'view_orders', text: 'View my orders' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        // Account & Login
        account: {
            answer: "Create an account to:\n✅ Buy and sell products\n✅ Save your cart\n✅ Track orders\n✅ Get personalized recommendations\n✅ Manage your profile\n\nIt's free and takes 1 minute! 🎉",
            followUp: [
                { id: 'how_register', text: 'How to register?' },
                { id: 'seller_account', text: 'Become a seller' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        how_register: {
            answer: "Registration is easy:\n1️⃣ Click 'Register' in the navbar\n2️⃣ Fill in your details\n3️⃣ Choose 'Buyer' role to sell products\n4️⃣ Verify your email\n\nStart shopping or selling today! 🚀",
            followUp: [
                { id: 'register_now', text: 'Register now' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        seller_account: {
            answer: "To sell products:\n1️⃣ Register with 'Buyer' role (sellers can also buy)\n2️⃣ Go to 'Sell Product' in navbar\n3️⃣ Fill product details & upload images\n4️⃣ Your product goes live instantly!\n\nStart earning today! 💰",
            followUp: [
                { id: 'sell_product', text: 'List a product' },
                { id: 'back', text: '← Back to main menu' }
            ]
        },
        
        // Navigation Actions
        try_ai: {
            answer: "Redirecting you to AI Recommendations... 🚀",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/products/recommendations',
            followUp: []
        },
        
        browse_products: {
            answer: "Taking you to our products... 🌱",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/products',
            followUp: []
        },
        
        view_cart: {
            answer: "Opening your cart... 🛒",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/cart',
            followUp: []
        },
        
        view_orders: {
            answer: "Showing your orders... 📦",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/dashboard/orders',
            followUp: []
        },
        
        register_now: {
            answer: "Let's get you registered! ✨",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/auth/register',
            followUp: []
        },
        
        sell_product: {
            answer: "Opening product listing form... 📝",
            action: 'navigate',
            url: '<?php echo BASE_URL; ?>/products/create',
            followUp: []
        },
        
        back: {
            answer: "What else can I help you with? 😊",
            followUp: 'initial'
        }
    }
};

// Chatbot Controller
class AgroChatbot {
    constructor() {
        this.bubble = document.getElementById('chatbotBubble');
        this.panel = document.getElementById('chatbotPanel');
        this.closeBtn = document.getElementById('chatbotClose');
        this.body = document.getElementById('chatbotBody');
        this.quickReplies = document.getElementById('quickReplies');
        
        this.init();
    }
    
    init() {
        // Event listeners
        this.bubble.addEventListener('click', () => this.toggle());
        this.closeBtn.addEventListener('click', () => this.close());
        
        // Show initial quick replies
        this.showQuickReplies(chatbotData.initial);
    }
    
    toggle() {
        this.panel.classList.toggle('active');
        if (this.panel.classList.contains('active')) {
            this.body.scrollTop = this.body.scrollHeight;
        }
    }
    
    close() {
        this.panel.classList.remove('active');
    }
    
    showQuickReplies(replies) {
        this.quickReplies.innerHTML = '';
        
        const replyList = Array.isArray(replies) ? replies : chatbotData[replies];
        
        replyList.forEach(reply => {
            const btn = document.createElement('button');
            btn.className = 'quick-reply-btn';
            btn.textContent = reply.text;
            btn.onclick = () => this.handleReply(reply.id, reply.text);
            this.quickReplies.appendChild(btn);
        });
    }
    
    handleReply(id, text) {
        // Show user's question
        this.addMessage(text, 'user');
        
        // Clear quick replies temporarily
        this.quickReplies.innerHTML = '';
        
        // Show typing indicator
        this.showTyping();
        
        // Simulate thinking delay
        setTimeout(() => {
            this.hideTyping();
            
            const response = chatbotData.responses[id];
            if (response) {
                this.addMessage(response.answer, 'bot');
                
                // Handle navigation action
                if (response.action === 'navigate') {
                    setTimeout(() => {
                        window.location.href = response.url;
                    }, 1000);
                } else {
                    // Show follow-up questions
                    const followUp = response.followUp === 'initial' 
                        ? chatbotData.initial 
                        : response.followUp;
                    this.showQuickReplies(followUp);
                }
            }
        }, 800);
    }
    
    addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${type}-message`;
        
        if (type === 'bot') {
            messageDiv.innerHTML = `
                <div class="message-avatar">🌱</div>
                <div class="message-content">
                    <p>${this.formatMessage(text)}</p>
                </div>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="message-content">
                    <p>${text}</p>
                </div>
            `;
        }
        
        this.body.appendChild(messageDiv);
        this.body.scrollTop = this.body.scrollHeight;
    }
    
    formatMessage(text) {
        // Convert line breaks to <br> and bullet points
        return text
            .replace(/\n•/g, '<br>•')
            .replace(/\n/g, '<br>')
            .replace(/\n\n/g, '<br><br>');
    }
    
    showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-message bot-message';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="message-avatar">🌱</div>
            <div class="message-content">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;
        this.body.appendChild(typingDiv);
        this.body.scrollTop = this.body.scrollHeight;
    }
    
    hideTyping() {
        const typing = document.getElementById('typingIndicator');
        if (typing) typing.remove();
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new AgroChatbot();
});
</script>
