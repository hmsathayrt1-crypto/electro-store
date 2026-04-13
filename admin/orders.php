<?php include 'partials/header.php';
$action = $_GET['action'] ?? 'list';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'status' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status, $id]);
    header('Location: orders.php'); exit;
}

$orders = $pdo->query("SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC")->fetchAll();
$order = null;
if ($action === 'view' && $id) {
    $o = $pdo->prepare("SELECT o.*, u.name as customer FROM orders o JOIN users u ON o.user_id=u.id WHERE o.id=?");
    $o->execute([$id]); $order = $o->fetch();
    $items = $pdo->prepare("SELECT * FROM order_items WHERE order_id=?"); $items->execute([$id]); $orderItems = $items->fetchAll();
}
?>
<?php if($order): ?>
<div class="admin-form">
    <h2>📋 طلب <?= sanitize($order['order_number']) ?></h2>
    <p><strong>العميل:</strong> <?= sanitize($order['customer']) ?></p>
    <p><strong>العنوان:</strong> <?= sanitize($order['address']) ?></p>
    <p><strong>الإجمالي:</strong> <?= number_format($order['total']) ?> ل.س</p>
    <p><strong>طريقة الدفع:</strong> <?= PAYMENT_MAP[$order['payment_method']] ?? $order['payment_method'] ?></p>
    <p><strong>التاريخ:</strong> <?= $order['created_at'] ?></p>
    <form method="post" action="?action=status&id=<?= $id ?>">
        <div class="form-group"><label>الحالة</label>
        <select name="status">
            <?php foreach(['reviewing'=>'قيد المراجعة','confirmed'=>'تم التأكيد','shipping'=>'قيد الشحن','delivered'=>'تم التوصيل'] as $k=>$v): ?>
            <option value="<?= $k ?>" <?= $order['status']===$k?'selected':'' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select></div>
        <button type="submit" class="btn btn-primary">💾 تحديث الحالة</button>
    </form>
    <h3 style="margin-top:20px">المنتجات</h3>
    <table class="admin-table"><thead><tr><th>المنتج</th><th>الكمية</th><th>السعر</th><th>المجموع</th></tr></thead><tbody>
    <?php foreach($orderItems as $i): ?>
    <tr><td><?= sanitize($i['product_name']) ?></td><td><?= $i['quantity'] ?></td><td><?= number_format($i['price_at_purchase']) ?></td>
    <td><?= number_format($i['price_at_purchase']*$i['quantity']) ?></td></tr>
    <?php endforeach; ?></tbody></table>
    <a href="orders.php" class="btn btn-secondary">← رجوع</a>
</div>
<?php else: ?>
<h2>📋 الطلبات (<?= count($orders) ?>)</h2>
<table class="admin-table"><thead><tr><th>رقم الطلب</th><th>العميل</th><th>الإجمالي</th><th>الدفع</th><th>الحالة</th><th>التاريخ</th><th>إجراء</th></tr></thead><tbody>
<?php foreach($orders as $o): ?>
<tr><td><?= sanitize($o['order_number']) ?></td><td><?= sanitize($o['customer']) ?></td>
<td><?= number_format($o['total']) ?></td><td><?= PAYMENT_MAP[$o['payment_method']]??$o['payment_method'] ?></td>
<td><span class="badge badge-<?= $o['status'] ?>"><?= STATUS_MAP[$o['status']]??$o['status'] ?></span></td>
<td><?= $o['created_at'] ?></td>
<td><a href="?action=view&id=<?= $o['id'] ?>" class="btn btn-sm btn-primary">عرض</a></td></tr>
<?php endforeach; ?></tbody></table>
<?php endif; ?>
<?php include 'partials/footer.php'; ?>
