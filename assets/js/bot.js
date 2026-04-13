// Bot Widget
function toggleBot() {
    const panel = document.getElementById('bot-panel');
    panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
}

async function sendBotMessage() {
    const input = document.getElementById('bot-input');
    const msg = input.value.trim();
    if (!msg) return;

    addBotMessage(msg, 'user');
    input.value = '';

    try {
        const res = await fetch(window.location.origin + '/api/bot/chat.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({message: msg})
        });
        const data = await res.json();
        addBotMessage(data.response || 'عذراً، حدث خطأ', 'bot');
    } catch(e) {
        addBotMessage('عذراً، حدث خطأ في الاتصال', 'bot');
    }
}

function addBotMessage(text, sender) {
    const div = document.createElement('div');
    div.className = 'bot-msg ' + sender;
    div.textContent = text;
    const container = document.getElementById('bot-messages');
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
}
