<?php
require_once __DIR__ . '/../../includes/functions.php';
startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Method not allowed'], 405);

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

if (empty($name) || empty($email) || empty($password)) jsonResponse(['error' => 'جميع الحقول مطلوبة'], 400);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonResponse(['error' => 'إيميل غير صالح'], 400);
if (strlen($password) < 6) jsonResponse(['error' => 'كلمة المرور قصيرة (6 أحرف)'], 400);

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) jsonResponse(['error' => 'الإيميل مسجل مسبقاً'], 409);

$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $hash, $phone]);

loginUser($pdo->lastInsertId(), $name, 'customer');
jsonResponse(['success' => true]);
