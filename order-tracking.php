<?php require_once 'includes/header.php';
requireLogin();

$orderNum = $_GET['order'] ?? '';
$order = null;
$orders = [];

if ($orderNum) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = ? AND user_id = ?");
    $stmt->execute([$orderNum, $_SESSION['user_id']]);
    $order = $stmt->fetch();
    if ($order) {
        $stmt2 = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt2->execute([$order['id']]);
        $order['items'] = $stmt2->fetchAll();
    }
}

if (!$orderNum) {
    $stmt = $pdo->prepare("
        SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
        FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC LIMIT 20
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
}
?>
<section class="page-header"><h1>📦 تتبع الطلبات</h1></section>

<section class="tracking">
    <form method="get" class="search-order">
        <input type="text" name="order" placeholder="أدخل رقم الطلب (مثل ORD-20260413-001)" value="<?= sanitize($orderNum) ?>">
        <button type="submit" class="btn btn-primary">بحث</button>
    </form>

    <?php if($order): ?>
    <div class="order-detail">
        <h2>طلب رقم: <?= sanitize($order['order_number']) ?></h2>
        <div class="order-status-bar">
            <?php
            $statuses = ['reviewing' => 'قيد المراجعة', 'confirmed' => 'تم التأكيد', 'shipping' => 'قيد الشحن', 'delivered' => 'تم التوصيل'];
            $current = array_search($order['status'], array_keys($statuses));
            foreach ($statuses as $key => $label): ?>
            <div class="status-step <?= $key === $order['status'] ? 'active' : ($current > array_search($key, array_keys($statuses)) ? 'done' : '') ?>">
                <span class="dot"></span> <?= $label ?>
            </div>
            <?php endforeach; ?>
        </div>
        <table class="cart-table">
            <thead><tr><th>المنتج</th><th>الكمية</th><th>السعر</th><th>المجموع</th></tr></thead>
            <tbody>
                <?php foreach($order['items'] as $item): ?>
                <tr>
                    <td><?= sanitize($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= formatPrice($item['price_at_purchase']) ?></td>
                    <td><?= formatPrice($item['price_at_purchase'] * $item['quantity']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-summary">
            <p>المجموع: <?= formatPrice($order['subtotal']) ?></p>
            <p>التوصيل: <?= formatPrice($order['delivery_fee']) ?></p>
            <p class="total">الإجمالي: <strong><?= formatPrice($order['total']) ?></strong></p>
            <p>طريقة الدفع: <?= PAYMENT_MAP[$order['payment_method']] ?? $order['payment_method'] ?></p>
            <p>التاريخ: <?= $order['created_at'] ?></p>
        </div>
    </div>
    <?php elseif($orderNum): ?>
    <p class="empty">لم يتم العثور على الطلب</p>
    <?php elseif(!empty($orders)): ?>
    <table class="cart-table">
        <thead><tr><th>رقم الطلب</th><th>الإجمالي</th><th>الحالة</th><th>التاريخ</th></tr></thead>
        <tbody>
            <?php foreach($orders as $o): ?>
            <tr>
                <td><a href="?order=<?= $o['order_number'] ?>"><?= $o['order_number'] ?></a></td>
                <td><?= formatPrice($o['total']) ?></td>
                <td><?= STATUS_MAP[$o['status']] ?? $o['status'] ?></td>
                <td><?= $o['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
