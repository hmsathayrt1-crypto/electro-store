<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
if (!isLoggedIn()) jsonResponse(['error' => 'يجب تسجيل الدخول'], 401);

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);
$quantity = max(1, (int)($data['quantity'] ?? 1));

$stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id = ? AND status = 'active'");
$stmt->execute([$productId]);
if (!$stmt->fetch()) jsonResponse(['error' => 'منتج غير موجود'], 404);

$stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = quantity + ?");
$stmt->execute([$_SESSION['user_id'], $productId, $quantity, $quantity]);
jsonResponse(['success' => true]);
