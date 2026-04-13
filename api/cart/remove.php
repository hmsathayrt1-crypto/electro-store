<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
if (!isLoggedIn()) jsonResponse(['error' => 'يجب تسجيل الدخول'], 401);

$data = json_decode(file_get_contents('php://input'), true);
$productId = (int)($data['product_id'] ?? 0);

$pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?")
    ->execute([$_SESSION['user_id'], $productId]);
jsonResponse(['success' => true]);
