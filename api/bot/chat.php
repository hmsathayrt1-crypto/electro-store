<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();

$data = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message'] ?? '');
$userId = isLoggedIn() ? $_SESSION['user_id'] : null;
$sessionId = session_id();

if (!$message) jsonResponse(['error' => 'رسالة فارغة'], 400);

$response = '';
$source = 'default';

// 1. Check for order number
if (preg_match('/ORD-\d{8}-\d{3}/', $message, $matches)) {
    $stmt = $pdo->prepare("SELECT status, total, created_at FROM orders WHERE order_number = ?");
    $stmt->execute([$matches[0]]);
    $order = $stmt->fetch();
    if ($order) {
        $response = "طلبك {$matches[0]} حالته: " . (STATUS_MAP[$order['status']] ?? $order['status']) . " | الإجمالي: " . formatPrice($order['total']) . " | تاريخ: {$order['created_at']}";
        $source = 'order_status';
    }
}

// 2. Search knowledge base
if (!$response) {
    $words = array_filter(explode(' ', $message), fn($w) => mb_strlen($w) > 2);
    if (!empty($words)) {
        $conditions = array_map(fn($w) => "keywords LIKE ?", $words);
        $params = array_map(fn($w) => "%$w%", $words);
        $where = implode(' OR ', $conditions);
        $stmt = $pdo->prepare("SELECT answer FROM bot_knowledge WHERE ($where) AND is_active = 1 LIMIT 1");
        $stmt->execute($params);
        $kb = $stmt->fetch();
        if ($kb) { $response = $kb['answer']; $source = 'knowledge_base'; }
    }
}

// 3. Default
if (!$response) {
    $response = "عذراً، لم أتمكن من فهم سؤالك. يمكنك:\n• كتابة رقم الطلب (ORD-XXXXXXXX-XXX) لمعرفة حالته\n• سؤالي عن المنتجات والأسعار\n• السؤال عن ساعات العمل والتوصيل";
}

$pdo->prepare("INSERT INTO chat_logs (user_id, session_id, message, response, source) VALUES (?,?,?,?,?)")
    ->execute([$userId, $sessionId, $message, $response, $source]);

jsonResponse(['response' => $response]);
