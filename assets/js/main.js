// Main JS
const APP_URL = window.location.origin;

// Cart functions
async function addToCart(productId, qty = 1) {
    const res = await fetch(APP_URL + '/api/cart/add.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({product_id: productId, quantity: parseInt(qty)})
    });
    const data = await res.json();
    if (data.success) {
        const el = document.getElementById('cart-count');
        if (el) el.textContent = parseInt(el.textContent || 0) + parseInt(qty);
        showToast('✅ تمت الإضافة للسلة');
    } else {
        showToast('❌ ' + (data.error || 'خطأ'));
    }
}

async function updateCart(productId, qty) {
    await fetch(APP_URL + '/api/cart/update.php', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({product_id: productId, quantity: parseInt(qty)})
    });
    location.reload();
}

async function removeFromCart(productId) {
    await fetch(APP_URL + '/api/cart/remove.php', {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({product_id: productId})
    });
    location.reload();
}

function showToast(msg) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);background:#2C3E50;color:white;padding:12px 24px;border-radius:8px;z-index:9999;font-size:14px;';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

function searchProducts() {
    const q = document.getElementById('search-input')?.value;
    if (q && q.length >= 2) window.location.href = APP_URL + '/category.php?q=' + encodeURIComponent(q);
}
