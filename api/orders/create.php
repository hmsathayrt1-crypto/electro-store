<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
requireLogin();

$data = json_decode(file_get_contents('php://input'), true);
$address = trim($data['address'] ?? '');
$paymentMethod = $data['payment_method'] ?? '';

if (!$address) jsonResponse(['error' => 'عنوان التوصيل مطلوب'], 400);
if (!in_array($paymentMethod, ['cash','wallet','gateway'])) jsonResponse(['error' => 'طريقة دفع غير صالحة'], 400);

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("
        SELECT ci.*, p.price, p.stock, p.name as product_name, p.sales_count
        FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $items = $stmt->fetchAll();
    if (empty($items)) throw new Exception('السلة فارغة');

    foreach ($items as $item) {
        if ($item['stock'] < $item['quantity']) throw new Exception("المنتج {$item['product_name']} غير متوفر");
    }

    $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
    $delivery = DELIVERY_FEE;
    $total = $subtotal + $delivery;
    $paymentStatus = 'pending';

    if ($paymentMethod === 'wallet') {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ? FOR UPDATE");
        $stmt->execute([$_SESSION['user_id']]);
        $balance = (float)$stmt->fetchColumn();
        if ($balance < $total) throw new Exception('رصيد المحفظة غير كافٍ');
        $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")->execute([$total, $_SESSION['user_id']]);
        $pdo->prepare("INSERT INTO wallet_transactions (user_id, amount, type, description) VALUES (?, ?, 'purchase', ?)")
            ->execute([$_SESSION['user_id'], $total, "شراء"]);
        $paymentStatus = 'paid';
    }

    $orderNumber = generateOrderNumber();
    $pdo->prepare("INSERT INTO orders (order_number, user_id, subtotal, delivery_fee, total, payment_method, payment_status, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
        ->execute([$orderNumber, $_SESSION['user_id'], $subtotal, $delivery, $total, $paymentMethod, $paymentStatus, $address]);
    $orderId = $pdo->lastInsertId();

    $insertItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price_at_purchase) VALUES (?, ?, ?, ?, ?)");
    $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ?, sales_count = sales_count + ? WHERE id = ?");
    foreach ($items as $item) {
        $insertItem->execute([$orderId, $item['product_id'], $item['product_name'], $item['quantity'], $item['price']]);
        $updateStock->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
    }

    $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?")->execute([$_SESSION['user_id']]);
    $pdo->commit();
    jsonResponse(['success' => true, 'order_number' => $orderNumber]);
} catch (Exception $e) {
    $pdo->rollBack();
    jsonResponse(['error' => $e->getMessage()], 400);
}
