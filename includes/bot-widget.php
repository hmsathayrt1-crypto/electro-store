<!-- Bot Widget -->
<div id="bot-widget" class="bot-widget">
    <button id="bot-toggle" class="bot-toggle" onclick="toggleBot()">💬 <span>المساعد</span></button>
    <div id="bot-panel" class="bot-panel" style="display:none;">
        <div class="bot-header">
            <span>🤖 المساعد الذكي</span>
            <button onclick="toggleBot()">✕</button>
        </div>
        <div id="bot-messages" class="bot-messages">
            <div class="bot-msg bot">مرحباً! كيف يمكنني مساعدتك؟ 😊</div>
        </div>
        <div class="bot-input-area">
            <input type="text" id="bot-input" placeholder="اكتب رسالتك..." onkeypress="if(event.key==='Enter')sendBotMessage()">
            <button onclick="sendBotMessage()">إرسال</button>
        </div>
    </div>
</div>
