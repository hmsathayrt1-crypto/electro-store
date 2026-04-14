<?php include 'partials/header.php';
$totalSales = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$byStatus = $pdo->query("SELECT status, COUNT(*) as c FROM orders GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$topProducts = $pdo->query("SELECT name, sales_count FROM products ORDER BY sales_count DESC LIMIT 10")->fetchAll();
$byMethod = $pdo->query("SELECT payment_method, COUNT(*) as c FROM orders GROUP BY payment_method")->fetchAll();
?>
<div class="admin-stats">
    <div class="stat-card"><h3>💰 إجمالي المبيعات</h3><p><?= number_format($totalSales) ?> ل.س</p></div>
    <div class="stat-card"><h3>📦 عدد الطلبات</h3><p><?= $totalOrders ?></p></div>
</div>

<h2>📊 حسب الحالة</h2>
<table class="admin-table"><thead><tr><th>الحالة</th><th>العدد</th></tr></thead><tbody>
<?php foreach($byStatus as $s=>$c): ?>
<tr><td><?= STATUS_MAP[$s]??$s ?></td><td><?= $c ?></td></tr>
<?php endforeach; ?></tbody></table>

<h2>🏆 أكثر 10 منتجات مبيعاً</h2>
<table class="admin-table"><thead><tr><th>المنتج</th><th>المبيعات</th></tr></thead><tbody>
<?php foreach($topProducts as $p): ?>
<tr><td><?= sanitize($p['name']) ?></td><td><?= $p['sales_count'] ?></td></tr>
<?php endforeach; ?></tbody></table>

<h2>💳 حسب طريقة الدفع</h2>
<table class="admin-table"><thead><tr><th>الطريقة</th><th>العدد</th></tr></thead><tbody>
<?php foreach($byMethod as $m): ?>
<tr><td><?= PAYMENT_MAP[$m['payment_method']]??$m['payment_method'] ?></td><td><?= $m['c'] ?></td></tr>
<?php endforeach; ?></tbody></table>
<?php include 'partials/footer.php'; ?>
