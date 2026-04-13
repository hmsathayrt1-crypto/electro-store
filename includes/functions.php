<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'use_strict_mode' => true,
        ]);
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user_role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ' . APP_URL . '/admin/login.php');
        exit;
    }
}

function loginUser($userId, $userName, $role) {
    startSession();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $userName;
    $_SESSION['user_role'] = $role;
}

function logoutUser() {
    startSession();
    session_destroy();
    $_SESSION = [];
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateOrderNumber() {
    $date = date('Ymd');
    $random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    return "ORD-{$date}-{$random}";
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function sanitize($input) {
    return htmlspecialchars($input ?? '', ENT_QUOTES, 'UTF-8');
}

function generateCSRF() {
    startSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRF($token) {
    startSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function formatPrice($price) {
    return number_format($price, 0) . ' ' . CURRENCY;
}

function getCartCount($pdo) {
    if (!isLoggedIn()) return 0;
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart_items WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return (int)$stmt->fetchColumn() ?: 0;
}

function getCartItems($pdo) {
    if (!isLoggedIn()) return [];
    $stmt = $pdo->prepare("
        SELECT ci.*, p.name, p.price, p.stock,
               (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order LIMIT 1) as image
        FROM cart_items ci
        JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchAll();
}

function getProductImage($pdo, $productId) {
    $stmt = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = ? ORDER BY sort_order LIMIT 1");
    $stmt->execute([$productId]);
    return $stmt->fetchColumn() ?: 'https://placehold.co/400x400/2C3E50/FFFFFF?text=صورة';
}
