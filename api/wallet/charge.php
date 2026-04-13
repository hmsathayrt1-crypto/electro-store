<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();
requireLogin();

$data = json_decode(file_get_contents('php://input'), true);
$amount = (float)($data['amount'] ?? 0);
if ($amount <= 0) jsonResponse(['error' => 'مبلغ غير صالح'], 400);

// In production: integrate with payment gateway here
$pdo->beginTransaction();
$pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")->execute([$amount, $_SESSION['user_id']]);
$pdo->prepare("INSERT INTO wallet_transactions (user_id, amount, type, description) VALUES (?, ?, 'charge', ?)")
    ->execute([$_SESSION['user_id'], $amount, "شحن المحفظة عبر بوابة الدفع"]);
$pdo->commit();
jsonResponse(['success' => true]);
