<?php require_once 'includes/header.php';
requireLogin();

$items = getCartItems($pdo);
if (empty($items)) { header('Location: cart.php'); exit; }

$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
$total = $subtotal + DELIVERY_FEE;

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$balance = (float)$stmt->fetchColumn();
?>
<section class="page-header"><h1>💳 إتمام الشراء</h1></section>
<section class="checkout">
    <form id="checkout-form" onsubmit="return handleCheckout(event)">
        <div class="form-group">
            <label>📍 عنوان التوصيل</label>
            <textarea name="address" required placeholder="المدينة، الحي، الشارع، رقم المبنى..."></textarea>
        </div>
        <div class="form-group">
            <label>💵 طريقة الدفع</label>
            <div class="payment-options">
                <label class="radio-card"><input type="radio" name="payment_method" value="cash" checked> 💵 نقداً عند الاستلام</label>
                <label class="radio-card"><input type="radio" name="payment_method" value="wallet" <?= $balance < $total ? 'disabled' : '' ?>> 💰 المحفظة (رصيدك: <?= formatPrice($balance) ?>)</label>
                <label class="radio-card"><input type="radio" name="payment_method" value="gateway"> 🏦 بوابة الدفع الإلكتروني</label>
            </div>
        </div>
        <div class="order-summary">
            <h3>ملخص الطلب</h3>
            <?php foreach($items as $item): ?>
            <div class="summary-item"><?= sanitize($item['name']) ?> × <?= $item['quantity'] ?> = <?= formatPrice($item['price'] * $item['quantity']) ?></div>
            <?php endforeach; ?>
            <hr>
            <p>المجموع: <?= formatPrice($subtotal) ?></p>
            <p>التوصيل: <?= formatPrice(DELIVERY_FEE) ?></p>
            <p class="total">الإجمالي: <strong><?= formatPrice($total) ?></strong></p>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block">✅ تأكيد الطلب</button>
    </form>
    <div id="checkout-msg"></div>
</section>
<script>
async function handleCheckout(e) {
    e.preventDefault();
    const fd = new FormData(e.target);
    const body = {address: fd.get('address'), payment_method: fd.get('payment_method')};
    const res = await fetch('api/orders/create.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify(body)
    });
    const data = await res.json();
    if (data.success) { window.location.href = 'order-tracking.php?order=' + data.order_number; }
    else { document.getElementById('checkout-msg').innerHTML = '<p class="error">'+data.error+'</p>'; }
    return false;
}
</script>
<?php require_once 'includes/footer.php'; ?>
