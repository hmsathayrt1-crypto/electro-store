<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$stats = [
    'sales' => $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM orders WHERE status='reviewing'")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn(),
    'products' => $pdo->query("SELECT COUNT(*) FROM products WHERE status='active'")->fetchColumn(),
];
$recent = $pdo->query("SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 10")->fetchAll();
$topProducts = $pdo->query("SELECT name, sales_count FROM products WHERE status='active' ORDER BY sales_count DESC LIMIT 5")->fetchAll();
?>
<?php include 'partials/header.php'; ?>
<div class="admin-stats">
    <div class="stat-card"><h3>💰 المبيعات</h3><p><?= number_format($stats['sales']) ?> ل.س</p></div>
    <div class="stat-card"><h3>📦 الطلبات</h3><p><?= $stats['orders'] ?></p></div>
    <div class="stat-card"><h3>⏳ بانتظار المراجعة</h3><p><?= $stats['pending'] ?></p></div>
    <div class="stat-card"><h3>👥 العملاء</h3><p><?= $stats['users'] ?></p></div>
    <div class="stat-card"><h3>📦 المنتجات</h3><p><?= $stats['products'] ?></p></div>
</div>

<h2>آخر الطلبات</h2>
<table class="admin-table">
    <thead><tr><th>#</th><th>العميل</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th><th>إجراء</th></tr></thead>
    <tbody>
    <?php foreach($recent as $o): ?>
    <tr>
        <td><?= sanitize($o['order_number']) ?></td>
        <td><?= sanitize($o['customer']) ?></td>
        <td><?= number_format($o['total']) ?> ل.س</td>
        <td><span class="badge badge-<?= $o['status'] ?>"><?= STATUS_MAP[$o['status']] ?? $o['status'] ?></span></td>
        <td><?= $o['created_at'] ?></td>
        <td><a href="orders.php?action=view&id=<?= $o['id'] ?>" class="btn btn-sm btn-primary">عرض</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>🏆 الأكثر مبيعاً</h2>
<table class="admin-table">
    <thead><tr><th>المنتج</th><th>المبيعات</th></tr></thead>
    <tbody><?php foreach($topProducts as $p): ?>
    <tr><td><?= sanitize($p['name']) ?></td><td><?= $p['sales_count'] ?></td></tr>
    <?php endforeach; ?></tbody>
</table>
<?php include 'partials/footer.php'; ?>
