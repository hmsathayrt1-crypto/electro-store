<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
if (!isLoggedIn()) jsonResponse(['error' => 'يجب تسجيل الدخول'], 401);

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = max(1, (int)($data['quantity'] ?? 1));

$stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE user_id = ? AND product_id = ?");
$stmt->execute([$quantity, $_SESSION['user_id'], $productId]);
jsonResponse(['success' => true]);
