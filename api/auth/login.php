<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Method not allowed'], 405);

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    jsonResponse(['error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'], 401);
}

loginUser($user['id'], $user['name'], $user['role']);
jsonResponse(['success' => true, 'role' => $user['role']]);
