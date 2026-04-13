<?php require_once __DIR__ . '/../../includes/functions.php'; requireAdmin();
$csrf = generateCSRF();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم — متجري</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/admin.css">
</head>
<body>
<nav class="admin-nav">
    <div class="container" style="display:flex;align-items:center;justify-content:space-between">
        <a href="index.php" class="logo">⚡ لوحة التحكم</a>
        <div class="admin-links">
            <a href="index.php">🏠 الرئيسية</a>
            <a href="categories.php">📂 الأقسام</a>
            <a href="manufacturers.php">🏭 الشركات</a>
            <a href="products.php">📦 المنتجات</a>
            <a href="orders.php">📋 الطلبات</a>
            <a href="users.php">👥 المستخدمين</a>
            <a href="wallets.php">💰 المحافظ</a>
            <a href="bot-knowledge.php">🤖 البوت</a>
            <a href="chat-logs.php">💬 المحادثات</a>
            <a href="reports.php">📊 التقارير</a>
            <a href="<?= APP_URL ?>/" target="_blank">🌐 الموقع</a>
            <a href="<?= APP_URL ?>/logout.php">🚪 خروج</a>
        </div>
        <button class="mobile-menu-btn" onclick="document.querySelector('.admin-links').classList.toggle('show')" style="background:none;border:none;color:white;font-size:24px;cursor:pointer">☰</button>
    </div>
</nav>
<main class="container admin-content">
