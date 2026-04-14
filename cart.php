<?php require_once 'includes/header.php';
requireLogin();

$items = getCartItems($pdo);
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
$total = $subtotal + DELIVERY_FEE;
?>

<section class="page-header"><h1>🛒 سلة المشتريات</h1></section>

<section class="cart-page">
    <?php if(empty($items)): ?>
    <p class="empty">السلة فارغة — <a href="category.php">تصفح المنتجات</a></p>
    <?php else: ?>
    <table class="cart-table">
        <thead><tr><th>المنتج</th><th>السعر</th><th>الكمية</th><th>المجموع</th><th></th></tr></thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr id="cart-row-<?= $item['id'] ?>">
                <td><a href="product.php?id=<?= $item['product_id'] ?>"><?= sanitize($item['name']) ?></a></td>
                <td><?= formatPrice($item['price']) ?></td>
                <td>
                    <input type="number" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>"
                           onchange="updateCart(<?= $item['product_id'] ?>, this.value)" style="width:60px;">
                </td>
                <td class="row-total"><?= formatPrice($item['price'] * $item['quantity']) ?></td>
                <td><button class="btn btn-danger btn-sm" onclick="removeFromCart(<?= $item['product_id'] ?>)">✕</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="cart-summary">
        <p>المجموع الفرعي: <strong><?= formatPrice($subtotal) ?></strong></p>
        <p>التوصيل: <strong><?= formatPrice(DELIVERY_FEE) ?></strong></p>
        <p class="total">الإجمالي: <strong><?= formatPrice($total) ?></strong></p>
        <a href="checkout.php" class="btn btn-primary btn-lg">إتمام الشراء →</a>
    </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
