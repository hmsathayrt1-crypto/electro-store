<?php include 'partials/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)$_POST['user_id']; $amount = (float)$_POST['amount']; $desc = trim($_POST['description'] ?? 'إيداع من المدير');
    $pdo->beginTransaction();
    $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$amount, $userId]);
    $pdo->prepare("INSERT INTO wallet_transactions (user_id, amount, type, description) VALUES (?, ?, 'deposit', ?)")
        ->execute([$userId, $amount, $desc]);
    $pdo->commit();
    header('Location: wallets.php?ok=1'); exit;
}
$wallets = $pdo->query("SELECT id, name, email, balance FROM users WHERE role='customer' ORDER BY balance DESC")->fetchAll();
?>
<?php if(isset($_GET['ok'])): ?><p class="success">✅ تم الإيداع</p><?php endif; ?>
<h2>💰 المحافظ</h2>
<table class="admin-table"><thead><tr><th>العميل</th><th>الرصيد</th><th>إيداع</th></tr></thead><tbody>
<?php foreach($wallets as $w): ?>
<tr><td><?= sanitize($w['name']) ?> (<?= sanitize($w['email']) ?>)</td><td><?= number_format($w['balance']) ?> ل.س</td>
<td><form method="post" style="display:flex;gap:5px;align-items:center">
    <input type="hidden" name="user_id" value="<?= $w['id'] ?>">
    <input type="number" name="amount" placeholder="مبلغ" step="0.01" min="1" style="width:100px" required>
    <input type="text" name="description" placeholder="سبب" style="width:150px">
    <button type="submit" class="btn btn-sm btn-success">إيداع</button>
</form></td></tr>
<?php endforeach; ?></tbody></table>
<?php include 'partials/footer.php'; ?>
