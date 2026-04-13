<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
if (!isLoggedIn()) jsonResponse(['error' => 'يجب تسجيل الدخول'], 401);

$items = getCartItems($pdo);
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
jsonResponse(['items' => $items, 'subtotal' => $subtotal, 'delivery' => DELIVERY_FEE, 'total' => $subtotal + DELIVERY_FEE]);
