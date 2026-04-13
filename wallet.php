<?php require_once 'includes/header.php';
requireLogin();

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$balance = (float)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 30");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>
<section class="page-header"><h1>💰 المحفظة الإلكترونية</h1></section>
<section class="wallet-page">
    <div class="balance-card">
        <h2>رصيدك الحالي</h2>
        <p class="big-balance"><?= formatPrice($balance) ?></p>
        <button class="btn btn-primary" onclick="document.getElementById('charge-modal').style.display='flex'">💳 شحن المحفظة</button>
    </div>

    <div id="charge-modal" class="modal" style="display:none">
        <div class="modal-content">
            <h3>شحن المحفظة</h3>
            <form onsubmit="return chargeWallet(event)">
                <input type="number" name="amount" placeholder="المبلغ" min="1000" required>
                <button type="submit" class="btn btn-primary">شحن</button>
                <button type="button" class="btn btn-secondary" onclick="this.closest('.modal').style.display='none'">إلغاء</button>
            </form>
        </div>
    </div>

    <h3>سجل الحركات</h3>
    <table class="cart-table">
        <thead><tr><th>التاريخ</th><th>النوع</th><th>المبلغ</th><th>الوصف</th></tr></thead>
        <tbody>
            <?php foreach($transactions as $t):
                $types = ['charge'=>'شحن','purchase'=>'شراء','refund'=>'استرجاع','deposit'=>'إيداع'];
                $color = in_array($t['type'],['charge','refund','deposit']) ? 'green' : 'red';
            ?>
            <tr>
                <td><?= $t['created_at'] ?></td>
                <td><?= $types[$t['type']] ?? $t['type'] ?></td>
                <td class="<?= $color ?>"><?= ($t['type']==='purchase'?'-':'+') . formatPrice(abs($t['amount'])) ?></td>
                <td><?= sanitize($t['description'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<script>
async function chargeWallet(e) {
    e.preventDefault();
    const amount = e.target.amount.value;
    const res = await fetch('api/wallet/charge.php', {
        method:'POST', headers:{'Content-Type':'application/json'},
        body: JSON.stringify({amount})
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert(data.error || 'خطأ');
    return false;
}
</script>
<?php require_once 'includes/footer.php'; ?>
